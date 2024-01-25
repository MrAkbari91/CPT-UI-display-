<?php

namespace Templately\Core\Importer\Utils;

use Exception;
use Templately\Utils\Base;

class Utils extends Base {
	/**
	 * @throws Exception
	 */
	public static function read_json_file( $path ) {
		if ( ! file_exists( $path ) ) {
			throw new Exception( __( 'JSON file not exists. ' . basename( $path ), 'templately' ) );
		}

		$file_content = self::file_get_contents( $path );

		return $file_content ? json_decode( $file_content, true ) : [];
	}

	/**
	 * @param $file
	 * @param mixed ...$args
	 *
	 * @return false|string
	 */
	public static function file_get_contents( $file, ...$args ) {
		if ( ! is_file( $file ) || ! is_readable( $file ) ) {
			return false;
		}

		return file_get_contents( $file, ...$args );
	}

	public static function get_builtin_wp_post_types(): array {
		$post_type_args = [
			'show_in_nav_menus' => true,
			'public'            => true
		];
		$_post_types    = get_post_types( $post_type_args, 'objects' );

		return array_merge( array_keys( $_post_types ), [ 'nav_menu_item', 'wp_navigation' ] );
	}

	public static function map_old_new_post_ids( array $imported_data ) {
		$result = [];

		$result += $imported_data['templates']['succeed'] ?? [];

		if ( isset( $imported_data['content'] ) ) {
			foreach ( $imported_data['content'] as $post_type ) {
				$result += $post_type['succeed'] ?? [];
			}
		}

		if ( isset( $imported_data['wp-content'] ) ) {
			foreach ( $imported_data['wp-content'] as $post_type ) {
				$result += $post_type['succeed'] ?? [];
			}
		}

		return $result;
	}

	public static function map_old_new_term_ids( array $imported_data ) {
		$result = [];

		if ( isset( $imported_data['terms'] ) ) {
			foreach ( $imported_data['terms'] as $post_type ) {
				$result += $post_type['succeed'] ?? [];
			}
		}

		return $result;
	}

	public static function map_old_new_term_ids_el( array $imported_data ): array {
		$result = [];

		if ( ! isset( $imported_data['taxonomies'] ) ) {
			return $result;
		}

		foreach ( $imported_data['taxonomies'] as $post_type_taxonomies ) {
			foreach ( $post_type_taxonomies as $taxonomy ) {
				foreach ( $taxonomy as $term ) {
					$result[ $term['old_id'] ] = $term['new_id'];
				}
			}
		}

		return $result;
	}

	/**
	 * @param string $platform
	 *
	 * @return ImportHelper
	 */
	public static function get_json_helper( string $platform ) {
		return $platform === 'elementor' ? new ElementorHelper() : new GutenbergHelper();
	}

	public static function update_option( $key, $value, $autoload = 'no' ){
		$bk_value = get_option("__templately_$key");
		if($bk_value === false){
			$old_value = get_option($key);
			add_option( "__templately_$key", $old_value, '', $autoload );
		}
		return update_option( $key, $value, $autoload );
	}

	public static function import_page_settings( $id, $settings ) {
		$extra_settings = [
			'page_on_front' => [
				'show_on_front' => 'page'
			]
		];
		if ( isset( $settings['page_for_posts'] ) && $settings['page_for_posts'] ) {
			self::update_option( 'page_for_posts', $id );
		}
		if ( isset( $settings['show_on_front'] ) && $settings['show_on_front'] ) {
			self::update_option( 'page_on_front', $id );
			self::update_option( 'show_on_front', 'page' );
		}
		if ( ! empty( $settings['page_settings'] ) ) {
			foreach ( $settings['page_settings'] as $option_name => $val ) {
				self::update_option( $option_name, $id );
				if ( array_key_exists( $option_name, $extra_settings ) ) {
					foreach ( $extra_settings[ $option_name ] as $name => $value ) {
						self::update_option( $name, $value );
					}
				}
			}
		}
	}
}