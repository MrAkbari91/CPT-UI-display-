<?php
namespace Templately\Core\Platform;

use WP_Post;
use WP_Error;
use WP_Query;
use WP_REST_Response;

use Templately\API\Import;
use Templately\Core\Module;
use Templately\Utils\Helper;
use Templately\Utils\Options;
use Templately\Core\Platform;

use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Kit;
use Elementor\Core\Settings\Manager as SettingsManager;
use Templately\Core\Importer\Elementor as ElementorImporter;
use Elementor\TemplateLibrary\Source_Local as ElementorLocal;

class Elementor extends Platform {
	private $id = 'elementor';

	public function __construct() {
		Module::get_instance()->add( (object) [
			'id'     => $this->id,
			'object' => $this
		] );

		$this->hooks();
	}

	/**
	 * Initializing Hooks
	 * @return void
	 */
	public function hooks() {
		if ( class_exists( '\Elementor\Plugin' ) ) {
			add_action( 'elementor/preview/enqueue_styles', [ $this, 'styles' ] );
			add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'styles' ] );
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'scripts' ] );
		}
	}

	/**
	 * Assets Enqueueing
	 * @return void
	 */
	public function styles() {
		templately()->assets->enqueue( 'templately-elementor-preview', 'css/elementor.css' );
	}

	/**
	 * Assets Enqueueing
	 * @return void
	 */
	public function scripts() {
		templately()->assets->enqueue( 'templately-elementor', 'css/elementor.css' );
		templately()->assets->enqueue( 'templately-elementor', 'js/elementor.js', [ 'jquery' ], true );
		templately()->admin->scripts( 'elementor' );
	}

	/**
	 * Determine Active UI Theme
	 * @return string
	 */
	public function ui_theme() {
		$ui_theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		return 'light' !== $ui_theme ? 'dark' : 'light';
	}

	/**
	 * Checking the template eligibility for import.
	 *
	 * @return WP_Error|void
	 */
	protected function is_eligible( $template, $types = [ 'page', 'section' ] ) {
		if ( ! Helper::is_plugin_active( 'elementor-pro/elementor-pro.php' ) &&
			isset( $template['type'] ) &&
			! in_array( $template['type'], $types, true )
		) {
			$message = sprintf(
				__( 'You have to install/activate %s to import %s Template.', 'templately' ),
				sprintf(
					'<a target="_blank" href="%s">%s</a>',
					'https://wpdeveloper.com/go/elementor',
					'Elementor PRO'
				),
				$template['type']
			);

			return Helper::error( 'required_elementor_pro', $message, 'import/page', 404 );
		}
	}

	public function get_saved_templates( $params = [] ) {
		$_page_number = isset( $params['page'] ) ? intval( $params['page'] ) : 1;

		$args = [
			'post_type'   => 'elementor_library',
			'post_status' => [ 'publish', 'draft' ],
			'paged' => $_page_number,
			'page' => $_page_number,
			'posts_per_page' => isset( $params['per_page'] ) ? intval( $params['per_page'] ) : 15
		];

		$templates      = new WP_Query( $args );
		$templates_list = $templates->posts;
		$templateList   = [];
		$_templates = Options::get_instance()->get( 'templates_in_clouds', [] );

		if ( count( $templates_list ) > 0 ) :
			$date_format = get_option( 'date_format', 'F j, Y' );
			foreach ( $templates_list as $post ) {
				$templateList[] = [
					'id'          => $post->ID,
					'title'       => $post->post_title,
					'date'        => date( $date_format, strtotime( $post->post_date ) ),
					'type'        => ucwords( get_post_meta( $post->ID, '_elementor_template_type', true ) ),
					'created_by'  => get_the_author_meta( 'user_nicename', $post->post_author ),
					'preview_url' => get_permalink( $post->ID ),
					'export_url'  => self::get_export_link( $post->ID ),
					'is_pushed'   => array_key_exists( $post->ID, $_templates )
				];
			}
		endif;
		wp_reset_postdata();
		wp_reset_query();

		return array(
			'current_page' => $templates->query_vars['paged'],
			'total_page'   => $templates->max_num_pages,
			'items'        => $templateList,
		);
	}

	private function get_export_link( $template_id ) {
		return add_query_arg(
			[
				'action'         => 'elementor_library_direct_actions',
				'library_action' => 'export_template',
				'source'         => 'local',
				'_nonce'         => wp_create_nonce( 'elementor_ajax' ),
				'template_id'    => $template_id,
			],
			admin_url( 'admin-ajax.php' )
		);
	}

	public function is_kit( $post_id ) {
		$document = Plugin::$instance->documents->get( $post_id );

		return $document instanceof Kit && ! $document->is_revision();
	}

	/**
	 * Delete an item from Elementor Templates Library.
	 * Which also refers as Saved Templates.
	 *
	 * @param $params
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete( $params = [] ) {
		$id               = isset( $params['id'] ) ? intval( $params['id'] ) : false;
		$force_delete_kit = isset( $params['force_delete_kit'] ) && $params['force_delete_kit'];

		$is_kit = false;

		if ( $this->is_kit( $id ) ) {
			$is_kit = true;
			if ( ! $force_delete_kit ) {
				return Helper::error( 'cant_delete_kit', 'Cannot delete kit.', 'saved-templates/delete', 500, [ 'id' => $id ] );
			} else {
				$_GET['force_delete_kit'] = 1; // Fallback GET Ready!
			}
		}

		$post = wp_trash_post( $id );
		if ( $post instanceof WP_Post ) {
			return Helper::success( [
				'is_kit'  => $is_kit,
				'status'  => 'success',
				'message' => __( 'Successfully Deleted.', 'templately' )
			] );
		}

		return Helper::error(
			'cant_delete_kit',
			__( 'Something must went wrong.', 'templately' ),
			'saved-templates/delete', 500,
			[ 'id' => $id ]
		);

		// $cloud_map     = DB::get_option( '_templately_cloud_map', [] );
		// $new_cloud_map = [];
		// array_walk( $cloud_map, function ( $cloud ) use ( &$cloud_map, $id, &$new_cloud_map ) {
		// 	if ( $cloud['template_id'] !== $id ) {
		// 		$new_cloud_map[] = $cloud;
		// 	}
		// } );
		// DB::update_option( '_templately_cloud_map', $new_cloud_map );
		// Helper::send_success( __( 'Successfully Deleted.', 'templately' ) );
	}

	protected function get_template_from_library( $id ) {
		$local = new ElementorLocal();

		return $local->get_data( [ 'template_id' => $id ] );
	}

	public function get_template_data( $id ) {
		$data = $this->get_template_from_library( $id );
		if ( ! empty( $data ) && isset( $data['content'] ) ) {
			$name = get_the_title( $id );

			return [
				'name'         => $name,
				'file_content' => $data
			];
		}

		return null;
	}

	/**
	 * Creating an elementor page
	 *
	 * @param integer $id
	 * @param string $title
	 * @param Import $importer
	 *
	 * @since 2.0.0
	 *
	 * @return array|WP_Error array on success, WP_Error on failure.
	 */
	public function create_page( $id, $title, $importer = null ) {
		$template_data = $importer->get_content( $id );

		if ( is_wp_error( $template_data ) ) {
			return $template_data;
		}

		$importer      = new ElementorImporter;
		$template_data = $importer->get_data( $template_data );

		if ( ! empty( $title ) ) {
			$template_data['page_title'] = $title;
		}
		/**
		 * Checking the template eligibility for import.
		 */
		$eligible = $this->is_eligible( $template_data );
		if( is_wp_error( $eligible ) ) {
			return $eligible;
		}

		$inserted_ID = $importer->create_page( $template_data );

		if ( is_wp_error( $inserted_ID ) ) {
			return Helper::error(
				'import_failed',
				$inserted_ID->get_error_message(),
				'import/page',
				$inserted_ID->get_error_code(),
				[ 'code' => $inserted_ID->get_error_code() ]
			);
		}

		return [
			'post_id'             => $inserted_ID,
			'edit_link'           => get_edit_post_link( $inserted_ID, 'internal' ),
			'elementor_edit_link' => Plugin::$instance->documents->get( $inserted_ID )->get_edit_url(),
			'visit'               => get_permalink( $inserted_ID )
		];
	}

	public function import_in_library( $id, $importer = null ) {
		$template_data = $importer->get_content( $id, 'elementor', 'remote' );

		if ( is_wp_error( $template_data ) ) {
			return $template_data;
		}

		$importer          = new ElementorImporter;
		$imported_template = $importer->import_in_library( $template_data );

		if( is_wp_error( $imported_template ) ) {
			return $imported_template;
		}

		return [
			'post_id'             => $imported_template['template_id'],
			'edit_link'           => get_edit_post_link( $imported_template['template_id'], 'internal' ),
			'elementor_edit_link' => Plugin::$instance->documents->get( $imported_template['template_id'] )->get_edit_url(),
			'visit'               => $imported_template['url']
		];
	}

	public function get_content( $id ) {
		$local   = new ElementorLocal();
		$content = $local->get_data( [
			'template_id' => $id
		] );

		$_response         = [ 'status' => 'success' ];
		$_response['data'] = $content;

		if ( is_wp_error( $content ) ) {
			/**
			 * @var WP_Error $content
			 */
			unset( $_response['data'] );
			$_response['status']  = 'error';
			$_response['message'] = $content->get_error_message();
		}

		return $_response;
	}

	public function insert( $data ) {
		$importer = new ElementorImporter();

		return $importer->get_data( $data );
	}
}