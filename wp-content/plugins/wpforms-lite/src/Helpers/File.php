<?php

namespace WPForms\Helpers;

use WP_Filesystem_Base; // phpcs:ignore WPForms.PHP.UseStatement.UnusedUseStatement

/**
 * Class File.
 *
 * @since 1.6.5
 */
class File {

	/**
	 * Remove UTF-8 BOM signature if it presents.
	 *
	 * @since 1.6.5
	 *
	 * @param string $str String to process.
	 *
	 * @return string
	 */
	public static function remove_utf8_bom( $str ): string {

		if ( strpos( bin2hex( $str ), 'efbbbf' ) === 0 ) {
			$str = substr( $str, 3 );
		}

		return $str;
	}

	/**
	 * Get current filesystem.
	 *
	 * @since 1.8.6
	 *
	 * @return WP_Filesystem_Base|null
	 */
	public static function get_filesystem() {

		global $wp_filesystem;

		// We have to start the buffer to prevent output
		// when the file system is ssh/FTP but not configured.
		ob_start();

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		// The current page URL.
		$url = home_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ) );

		$credentials = request_filesystem_credentials( $url, '', false, false );

		ob_end_clean();

		if ( $credentials === false || ! WP_Filesystem( $credentials ) ) {
			wpforms_log(
				'WP_Filesystem Error',
				'File system isn\'t configured.',
				[ 'type' => [ 'error' ] ]
			);

			return null;
		}

		return $wp_filesystem;
	}

	/**
	 * Get file contents.
	 *
	 * @since 1.8.6
	 *
	 * @param string $file File path.
	 *
	 * @return string|false
	 */
	public static function get_contents( $file ) {

		$filesystem = self::get_filesystem();

		if (
			! $filesystem
			|| ! $filesystem->is_readable( $file )
			|| $filesystem->is_dir( $file )
		) {
			return false;
		}

		return $filesystem->size( $file ) > 0 ? $filesystem->get_contents( $file ) : '';
	}

	/**
	 * Save file contents.
	 *
	 * @since 1.8.6
	 *
	 * @param string $file    File path.
	 * @param string $content File content.
	 *
	 * @return bool
	 */
	public static function put_contents( $file, $content ): bool {

		$filesystem = self::get_filesystem();

		if ( ! $filesystem ) {
			return false;
		}

		return $filesystem->put_contents( $file, $content );
	}

	/**
	 * Get cache directory path.
	 *
	 * @since 1.8.6
	 *
	 * @return string
	 */
	public static function get_cache_dir(): string {

		static $cache_dir;

		if ( $cache_dir ) {
			/**
			 * Since wpforms_upload_dir() relies on hooks, and hooks can be added unpredictably,
			 * we need to cache the result of this method.
			 * Otherwise, it is the risk to save cache file to one dir and try to get from another.
			 */
			return $cache_dir;
		}

		$upload_dir  = wpforms_upload_dir();
		$upload_path = ! empty( $upload_dir['path'] )
			? trailingslashit( wp_normalize_path( $upload_dir['path'] ) )
			: trailingslashit( WP_CONTENT_DIR ) . 'uploads/wpforms/';

		$cache_dir = $upload_path . 'cache/';

		return $cache_dir;
	}
}
