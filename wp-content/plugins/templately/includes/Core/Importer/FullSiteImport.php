<?php

namespace Templately\Core\Importer;

use EbStyleHandler;
use Exception;
use Templately\Utils\Base;
use Templately\Utils\Helper;
use Templately\Utils\Installer;
use Templately\Utils\Options;

class FullSiteImport extends Base {
	use LogHelper;

	const SESSION_OPTION_KEY = 'templately_import_session';
	public    $manifest;
	protected $export;

	private $version = '1.0.0';

	public    $session_id;
	public    $dir_path;
	protected $filePath;
	protected $tmp_dir        = null;
	protected $dev_mode       = false;
	protected $api_key        = '';
	public    $request_params = [];
	protected $documents_data = [];
	protected $dependency_data = [];

	public function __construct() {
		$this->dev_mode = defined( 'TEMPLATELY_DEV' ) && TEMPLATELY_DEV;
		$this->api_key  = Options::get_instance()->get( 'api_key' );

		add_action( 'wp_ajax_templately_import_settings', [ $this, 'import_settings' ] );
		add_action( 'wp_ajax_templately_pack_import', [ $this, 'import' ] );

		if ( $this->dev_mode ) {
			add_filter( 'http_request_host_is_external', '__return_true' );
			add_filter( 'http_request_args', function ( $args ) {
				$args['sslverify'] = false;

				return $args;
			} );
		}
		add_action( 'eb_frontend_assets', [ $this, 'enqueue_template_assets' ], 10, 2 );
		add_filter( 'the_content', [$this, 'filter_content'], 10 ,1);
	}

	public function import_settings() {
		if( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( __( 'Unauthorized action.', 'templately' ) );
		}

		$data = wp_unslash( $_POST );

		// Upload the logo and add its URL to the data
		$logo_url = $this->upload_logo();
		if ($logo_url !== null) {
			$data['logo'] = $logo_url;
		}

		wp_send_json_success( update_option( self::SESSION_OPTION_KEY, $data ) );
	}

	private function update_session_data( $data ): bool {
		$old_data = get_option( self::SESSION_OPTION_KEY, [] );

		return update_option( self::SESSION_OPTION_KEY, wp_parse_args( $data, $old_data ) );
	}

	public function get_session_data(): array {
		$data = get_option( self::SESSION_OPTION_KEY, [] );

		$options = [];

		if ( is_array( $data ) && ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				$json            = json_decode( $value, true );
				$options[ $key ] = $json !== null ? $json : $value;
			}
		}

		return $options;
	}

	private function clear_session_data(): bool {
		return delete_site_option( self::SESSION_OPTION_KEY );
	}

	public function import() {
		 if ( ! $this->dev_mode && ! wp_doing_ajax() ) {
		 	exit;
		 }

		header( "Cache-Control: no-store, no-cache" );
		header( 'Content-Type: text/event-stream, charset=UTF-8' );
		header( "Connection: Keep-Alive" );

		if ( $GLOBALS['is_nginx'] ) {
			header( 'X-Accel-Buffering: no' );
			header( 'Content-Encoding: none' );
		}

		register_shutdown_function( [ $this, 'register_shutdown' ] );

		// Time to run the import!
		set_time_limit( 0 );

		flush();
		wp_ob_end_flush_all();

		try {
			// TODO: Need to check if user is connected or not

			$this->request_params = $this->get_session_data();

			$_id = isset( $this->request_params['id'] ) ? (int) $this->request_params['id'] : null;

			if ( $_id === null ) {
				$this->throw( __( 'Invalid Pack ID.', 'templately' ) );
			}

			/**
			 * Check Writing Permission
			 */
			$this->check_writing_permission();

			/**
			 * Download the zip
			 */
			$this->download_zip( $_id );

			/**
			 * Reading Manifest File
			 */
			$this->read_manifest();

			/**
			 * Version Check
			 */
			if ( ! empty( $this->manifest['version'] ) && version_compare( $this->manifest['version'], $this->version, '>' ) ) {
				/**
				 * FIXME: The message should be re-written (by content/support team).
				 */
				$this->throw( __( 'Please update the templately plugin.', 'templately' ) );
			}

			/**
			 * Checking & Installing Plugin Dependencies
			 */
			$this->install_dependencies();

			/**
			 * Should Revert Old Data
			 */
			$this->revert();

			/**
			 * Platform Based Templates Import
			 */
			$this->start_content_import();
		} catch ( Exception $e ) {
			$this->sse_message( [
				'action'   => 'error',
				'status'   => 'error',
				'type'     => "error",
				'title'    => __("Oops!", "templately"),
				'message'  => $e->getMessage()
			] );
		}

		// TODO: cleanup
		$this->clear_session_data();
	}

	/**
	 * @throws Exception
	 */
	private function throw( $message, $code = 0 ) {
		if ( $this->dev_mode ) {
			error_log( print_r( $message, 1 ) );
		}
		throw new Exception( $message );
	}

	/**
	 * @throws Exception
	 */
	private function check_writing_permission() {
		$upload_dir = wp_upload_dir();

		if ( ! is_writable( $upload_dir['basedir'] ) ) {
			$this->throw( __( 'Upload directory is not writable.', 'templately' ) );
		}

		$this->tmp_dir = trailingslashit( $upload_dir['basedir'] ) . 'templately' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;

		if ( ! is_dir( $this->tmp_dir ) ) {
			wp_mkdir_p( $this->tmp_dir );
		}

		$this->sse_log( 'writing_permission_check', __( 'Permission Passed', 'templately' ), 100 );
	}

	private function get_api_url( $id ): string {
		return $this->dev_mode ? 'https://app.templately.dev/api/v1/import/pack/' . $id : 'https://app.templately.com/api/v1/import/pack/' . $id;
	}

	/**
	 * @throws Exception
	 */
	private function download_zip( $id ) {
		// $this->sse_log( 'download', __( 'Downloading Template Pack', 'templately' ), 1 );
		$response = wp_remote_get( $this->get_api_url( $id ), [
			'headers' => [
				'Authorization'    => 'Bearer ' . $this->api_key,
				'x-templately-ip'  => Helper::get_ip(),
				'x-templately-url' => home_url( '/' )
			]
		] );

		if ( is_wp_error( $response ) ) {
			$this->throw( __( 'Template pack download failed', 'templately' ) . $response->get_error_message() );
		}

		$this->sse_log( 'download', __( 'Template is getting ready', 'templately' ), 57 );

		$session_id       = uniqid();
		$this->dir_path   = $this->tmp_dir . $session_id . DIRECTORY_SEPARATOR;
		$this->filePath   = $this->tmp_dir . "{$session_id}.zip";
		$this->session_id = $session_id;

		$this->update_session_data( [ 'session_id' => $this->session_id ] );

		if ( file_put_contents( $this->filePath, $response['body'] ) ) { // phpcs:ignore
			$this->sse_log( 'download', __( 'Template is getting ready', 'templately' ), 100 );

			$this->unzip();
		} else {
			$this->throw( __( 'Downloading Failed. Please try again', 'templately' ) );
		}
	}

	/**
	 * @throws Exception
	 */
	protected function unzip() {
		if ( ! WP_Filesystem() ) {
			$this->throw( __( 'WP_Filesystem cannot be initialized', 'templately' ) );
		}

		$unzip = unzip_file( $this->filePath, $this->dir_path );

		if ( is_wp_error( $unzip ) ) {
			$this->throw( sprintf( __( 'Unzipping failed: %s', 'templately' ), $unzip->get_error_message() ) );
		}

		if ( $unzip ) {
			unlink( $this->filePath );
		}
	}

	/**
	 * @throws Exception
	 */
	private function read_manifest() {
		$manifest_content = file_get_contents( $this->dir_path . DIRECTORY_SEPARATOR . 'manifest.json' );
		if ( empty( $manifest_content ) ) {
			$this->throw( __( 'Cannot be imported, as the manifest file is corrupted', 'templately' ) );
		}

		$this->manifest = json_decode( $manifest_content, true );
		$this->removeLog( 'temp' );

		// TODO: Read & Broadcast the LOG for waiting list
		// $this->sse_log( 'plugin', 'Installing required plugins', '--', 'updateLog', 'processing' );
		// // $this->sse_log( 'extra-content', 'Import Extra Contents (i.e: Forms)', '--', 'updateLog', 'processing' );
		// $this->sse_log( 'templates', 'Import Templates (i.e: Header, Footer etc)', '--', 'updateLog', 'processing' );
		// // $this->sse_log( 'content', 'Import Pages, Posts etc', '--', 'updateLog', 'processing' );
		// $this->sse_log( 'wp-content', 'Importing Pages, Posts, Navigation, etc', '--', 'updateLog', 'processing' );
		// $this->sse_log( 'finalize', 'Finalizing Your Imports', '--', 'updateLog', 'processing' );
	}

	private function skipped_plugin(): bool {
		return empty( $this->request_params['plugins'] ) || ! is_array( $this->request_params['plugins'] );
	}

	private function install_dependencies() {
		if ( ! empty( $this->request_params['theme'] ) && is_array( $this->request_params['theme'] ) ) {
			// activate theme
			$theme = $this->request_params['theme'];

			$this->before_install_hook();

			if (isset($theme['stylesheet'])) {
				// do_action('before_theme_activation', $theme); // Trigger action before theme activation
				$this->sse_log( 'theme', 'Installing and activating theme: ' . $theme['name'], 0 );
				$plugin_status      = Installer::get_instance()->install_and_activate_theme( $theme['stylesheet'] );

				if (!$plugin_status['success']) {
					$this->sse_message( [
						'action'   => 'updateLog',
						'status'   => 'error',
						'message'  => "Failed to activate theme: " . $theme['name'],
						'type'     => "theme",
						'progress' => 0
					] );
					$this->dependency_data['theme'] = [
						'success' => false,
						'name'    => $theme['name'],
						'slug'    => $theme['stylesheet'],
						'message' => $plugin_status['message'] ?? ''
					];
				} else {
					// do_action('after_theme_activation', $theme); // Trigger action after theme activation

					$this->sse_message( [
						'action'   => 'updateLog',
						'status'   => 'complete',
						'message'  => "Activated theme: " . $theme['name'],
						'type'     => "theme",
						'progress' => 100
					] );
					$this->dependency_data['theme'] = [
						'success' => true,
						'name'    => $theme['name'],
						'slug'    => $theme['stylesheet'],
						'message' => $plugin_status['message'] ?? ''
					];
				}
			}

			$this->after_install_hook();
		}
		else {
			$this->removeLog( 'theme' );
		}
		if ( ! empty( $this->request_params['plugins'] ) && is_array( $this->request_params['plugins'] ) ) {
			// $this->sse_log( 'plugin', 'Installing Plugins', 1 );
			$total_plugin = count( $this->request_params['plugins'] );

			$total_plugin_installed = $total_plugin;
			$_installed_plugins     = 0;

			$this->before_install_hook();

			foreach ( $this->request_params['plugins'] as $dependency ) {
				$this->sse_log( 'plugin', 'Installing required plugins: ' . $dependency['name'], floor( ( 100 * $_installed_plugins / $total_plugin ) ) );

				$dependency['slug'] = $dependency['plugin_original_slug'];
				$plugin_status      = Installer::get_instance()->install( $dependency );

				if ( ! $plugin_status['success'] ) {
					$this->sse_message( [
						'position' => 'plugin',
						'action'   => 'updateLog',
						'status'   => 'error',
						'message'  => 'Installation Failed: ' . $dependency['name'] . ' (' . ( $plugin_status['message'] ?? '' ) . ')',
						'type'     => "plugin_{$dependency['plugin_original_slug']}",
						'progress' => 0
					] );
					$this->dependency_data['plugins']['failed'][] = [
						'name'    => $dependency['name'],
						'slug'    => $dependency['slug'],
						'link'    => $dependency['link'],
						'message' => $plugin_status['message'] ?? ''
					];
				} else {
					$_installed_plugins++;
					$total_plugin_installed--;
				}
			}

			$this->after_install_hook();

			$this->sse_message( [
				'action'   => 'updateLog',
				'status'   => 'complete',
				'message'  => "Installed required plugins ($_installed_plugins/$total_plugin)",
				'type'     => "plugin",
				'progress' => 100
			] );
			$this->dependency_data['plugins']['total'] = $total_plugin;
			$this->dependency_data['plugins']['succeed'] = $_installed_plugins;
		} else {
			$this->removeLog( 'plugin' );
		}

		// $this->sse_log( 'plugin', 'Skipped Installing Plugins', '--', 'updateLog', 'skipped' );
	}

	private function before_install_hook() {
		remove_all_actions( 'wp_loaded' );
		remove_all_actions( 'after_setup_theme' );
		remove_all_actions( 'plugins_loaded' );
		remove_all_actions( 'init' );

		// making sure so that no redirection happens during plugin installation and hooks triggered bellow.
		add_filter('wp_redirect', '__return_false', 999);
	}

	private function after_install_hook() {
		do_action( 'wp_loaded' );
		do_action( 'after_setup_theme' );
		do_action( 'plugins_loaded' );
		do_action( 'init' );
	}

	/**
	 * @throws Exception
	 */
	private function start_content_import() {
		add_filter('upload_mimes', array($this, 'allow_svg_upload'));
		add_filter('elementor/files/allow_unfiltered_upload', '__return_true');

		$import        = new Import( $this );
		$imported_data = $import->run();

		$this->sse_message( [
			'type'    => 'complete',
			'action'  => 'complete',
			'results' => $this->normalize_imported_data( $imported_data )
		] );
	}

	private function normalize_imported_data( $data ) {
		$templates = ! empty( $data['templates']['succeed'] ) ? count( $data['templates']['succeed'] ) : 0;
		$template_types = ! empty( $data['templates']['template_types'] ) ? $data['templates']['template_types'] : [];

		$post_types = [];
		$content_templates = [];
		if ( ! empty( $data['content'] ) && is_array( $data['content'] ) ) {
			foreach ( $data['content'] as $type => $type_data ) {
				$content_templates[ $type ] = ! empty( $type_data['succeed'] ) ? count( $type_data['succeed'] ) : 0;
				$post_types[] = $this->get_post_type_label_by_slug($type);
			}
		}

		$contents = [];
		if ( ! empty( $data['wp-content'] ) && is_array( $data['wp-content'] ) ) {
			foreach ( $data['wp-content'] as $type => $type_data ) {
				$contents[ $type ] = ! empty( $type_data['succeed'] ) ? count( $type_data['succeed'] ) : 0;
				if(!in_array($type, ['wp_navigation', 'nav_menu_item'])){
					$post_types[] = $this->get_post_type_label_by_slug($type);
				}
			}
		}

		return [
			'templates'         => $templates,
			'contents'          => $content_templates,
			'wp-content'        => $contents,
			'post_types'        => $post_types,
			'template_types'    => $template_types,
			'dependency_data'   => $this->dependency_data,
		];
	}

	public function get_request_params() {
		return $this->request_params;
	}

	private function revert() {
		// $request = $this->get_request_params();
		// if ( isset( $request['revert'] ) && $request['revert'] ) {
		// 	// TODO: Implement the Revert Process.
		// }
	}

	public function redirect_for_archives( $link, $post_id ) {
		$archive_settings = get_option( 'templately_post_archive' );
		if ( ! empty( $archive_settings ) && intval( $archive_settings['post_id'] ) === intval( $post_id ) ) {
			$link = str_replace( $post_id, $archive_settings['archive_id'], $link );
		}

		return $link;
	}

	public function enqueue_template_assets( $path, $url ) {
		$using_templately_builder = get_query_var( 'using_templately_template' );
		if ( $using_templately_builder && function_exists( 'templately' ) ) {
			$template_locations = [ 'header', 'footer', 'archive', 'single' ];
			foreach ( $template_locations as $location ) {
				$template = templately()->theme_builder::$conditions_manager->get_templates_by_location( $location );
				if ( empty( $template ) ) {
					return;
				}
				$template = array_pop( $template );
				if ( $template->platform == 'gutenberg' ) {
					$template = is_array( $template ) ? array_pop( $template ) : $template;
					if ( ! file_exists( $path . 'eb-style-' . $template->get_main_id() . '.min.css' ) ) {
						$st   = EbStyleHandler::init();
						$post = get_post( $template->get_main_id() );
						$st->eb_write_css_from_content( $post, $post->ID, parse_blocks( $post->post_content ) );
					}
					wp_enqueue_style( 'templately-' . $location . '-' . $template->get_main_id(), $url . 'eb-style-' . $template->get_main_id() . '.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
				}
			}
		}
	}

	public function upload_logo() {
		// Check if the upload file exists
		if (isset($_FILES['logo'])) {
			// Require the needed files if not already loaded
			if (!function_exists('wp_handle_upload')) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
			}

			// Manually upload the file
			$uploadedfile = $_FILES['logo'];
			$upload_overrides = array('test_form' => false);
			$movefile = wp_handle_upload($uploadedfile, $upload_overrides);

			if ($movefile && !isset($movefile['error'])) {
				// The file was uploaded successfully, now we need to resize it
				if (!function_exists('wp_get_image_editor')) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
				}

				$filetype = wp_check_filetype($movefile['file']);

				if ($filetype['ext'] == 'jpg' || $filetype['ext'] == 'jpeg' || $filetype['ext'] == 'png') {
					$image = wp_get_image_editor($movefile['file']);
					if (!is_wp_error($image)) {
						$size = $image->get_size();
						if ($size['width'] > 200 || $size['height'] > 80) {
							$image->resize(200, 80, false);
							$image->save($movefile['file']);
						}
					}
				}

				// Prepare an array of post data for the attachment
				$attachment = array(
					'guid'           => $movefile['url'],
					'post_mime_type' => $movefile['type'],
					'post_title'     => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment
				$attach_id = wp_insert_attachment($attachment, $movefile['file']);

				// Generate the metadata for the attachment and update the database record
				$attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
				wp_update_attachment_metadata($attach_id, $attach_data);

				// Return the URL of the uploaded file
				return json_encode( [
					'id'  => $attach_id,
					'url' => $movefile['url']
				]);
			} else {
				/**
				 * Error generated by _wp_handle_upload()
				 * @see _wp_handle_upload() in wp-admin/includes/file.php
				 */
				// return $movefile['error'];
			}
		}

		return null;
	}

	public function allow_svg_upload($mimes) {
		// Allow SVG
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function register_shutdown(){
		$last_error = error_get_last();
		if ($last_error && $last_error['type'] === E_ERROR) {
			if (defined('WP_DEBUG') && WP_DEBUG && !empty($last_error['message'])) {
				$full_message = $last_error['message'];

				// Split the message at the first newline to remove the stack trace
				$message_parts = preg_split('/\n/', $full_message);

				// The error message is the first part
				$error_message = $message_parts[0];
			} else {
				// Generic error message
				$error_message = sprintf(__("It seems we're experiencing technical difficulties. Please try again or contact <a href='%s' target='_blank'>support</a>.", "templately"), 'https://wpdeveloper.com/support');
			}

			// Handle the error, e.g. log it or display a message to the user
			$this->sse_message( [
				'action'   => 'error',
				'status'   => 'error',
				'type'     => "error",
				'title'    => __("Oops!", "templately"),
				'message'  => $error_message,
				// 'position' => 'plugin',
				// 'progress' => '--',
			] );
		}

		Helper::log( "Shutdown:....." );
		Helper::log( "connection_status: " . $this->getConnectionStatusText() );
		Helper::log( $last_error );
	}

	protected function getConnectionStatusText() {
		$status = connection_status();
		switch ($status) {
			case CONNECTION_NORMAL:
				return "Normal";
			case CONNECTION_ABORTED:
				return "Aborted";
			case CONNECTION_TIMEOUT:
				return "Timeout";
			default:
				return "Unknown";
		}
	}

	protected function get_post_type_label_by_slug($slug) {
		$post_type_obj = get_post_type_object($slug);
		if($post_type_obj) {
			return $post_type_obj->label;
		}
		return null;
	}

	public function filter_content( $content ) {
		if(is_singular()){
			global $post;
			if(!empty($post) && $post->post_type === 'templately_library'){
				if(in_array(get_post_meta($post->ID, '_templately_template_type', true), ['header', 'footer'])){
					return '';
				}
			}
		}
		return $content;
	}
}