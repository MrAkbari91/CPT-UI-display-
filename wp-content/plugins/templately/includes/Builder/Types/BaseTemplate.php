<?php

namespace Templately\Builder\Types;

use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Exception;
use Templately\Builder\Source;
use WP_Error;
use WP_Post;

abstract class BaseTemplate {
	const TYPE_META_KEY     = '_templately_template_type';
	const PLATFORM_META_KEY = '_templately_template_platform';
	public $platform;

	protected $post;

	private $platform_data;
	/**
	 * @var int
	 */
	private $main_id;
	/**
	 * @var bool
	 */
	private $is_published = false;

	abstract static public function get_type(): string;

	abstract static public function get_title(): string;

	abstract static public function get_plural_title(): string;

	/**
	 * @throws Exception
	 */
	public function __construct( $data ) {
		if ( $data ) {
			if ( empty( $data['post_id'] ) ) {
				$this->post = new WP_Post( (object) [] );
			} else {
				$this->post = get_post( $data['post_id'] );

				if ( ! $this->post ) {
					throw new Exception( sprintf( 'Post ID #%s does not exist.', $data['post_id'] ) );
				}
			}
		}

		if ( $this->post ) {
			$platform = $this->get_platform();

			if ( $platform === 'elementor' && class_exists('Elementor\Plugin') ) {
				// \Essential_Addons_Elementor\Classes\Bootstrap::instance();
				// if ( Plugin::$instance->documents == null ) {
				// 	Plugin::instance()->init();
				// }

				$this->platform_data = Plugin::$instance->documents->get( $this->post->ID );
			} else {
				$this->platform_data = &$this;
			}
		}
	}

	public static function get_properties(): array {
		return [];
	}

	final public static function get_property( $key ) {
		$properties = static::get_properties();

		return $properties[ $key ] ?? null;
	}

	public function __get( $name ) {
		if ( $name == 'page_template' ) {
			return $this->post->page_template;
		}
		if ( $name == 'platform_data' ) {
			return $this->platform_data;
		}

		return null;
	}

	final public function get_platform() {
		if ( ! $this->platform ) {
			if ( $this->post->post_type == Source::CPT ) {
				$this->platform = get_post_meta( $this->post->ID, self::PLATFORM_META_KEY, true );
			} elseif ( $this->get_meta( '_elementor_edit_mode' ) == 'builder' || $this->post->post_type == 'elementor_library' ) {
				$this->platform = 'elementor';
			} else {
				$this->platform = 'gutenberg';
			}
		}

		return $this->platform;
	}

	/**
	 * Is it an elementor template?
	 *
	 * @return bool
	 */
	final public function is_elementor_template(): bool {
		return $this->get_platform() === 'elementor';
	}

	final public function get_meta( $key ) {
		return get_post_meta( $this->post->ID, $key, true );
	}

	final public function update_meta( $key, $value ) {
		return update_metadata( 'post', $this->post->ID, $key, $value );
	}

	final public function delete_meta( $key, $value = '' ): bool {
		return delete_metadata( 'post', $this->post->ID, $key, $value );
	}

	final public function is_published(): bool {
		if ( ! $this->is_published ) {
			$this->is_published = $this->post->post_status === 'publish';
		}

		return $this->is_published;
	}

	public function get_main_id(): int {
		if ( ! $this->main_id ) {
			$post_id = $this->post->ID;

			$parent_post_id = wp_is_post_revision( $post_id );

			if ( $parent_post_id ) {
				$post_id = $parent_post_id;
			}

			$this->main_id = $post_id;
		}

		return $this->main_id;
	}

	public function print_content() {
		echo $this->get_content( true );
	}

	public function get_content( $with_css = false ): string {
		if ( $this->is_elementor_template() && class_exists('Elementor\Plugin') ) {
			/**
			 * @var Document $document ;
			 */
			$document = &$this->platform_data;

			return $document->get_content( $with_css );
		}
		$post = get_post( $this->get_main_id() );

		return $post->post_content;
	}

	public function import( array $data ) {
		if ( $this->is_elementor_template() ) {
			/**
			 * @var Document $document
			 */
			$document = &$this->platform_data;
			$document->import( $data );
			$document->set_is_built_with_elementor( true );
		} else {
			$this->update_post( [
				'post_content' => wp_slash( $data['content'] )
			] );
		}

		if ( $this->get_property( 'condition' ) ) {
			$this->update_conditions( [ $this->get_property( 'condition' ) ] );
		}
	}

	protected function update_conditions( array $conditions = [] ) {
		if ( empty( $conditions ) || ! is_array( $conditions ) ) {
			return;
		}

		templately()->theme_builder::$conditions_manager->save_conditions( $this->get_main_id(), $conditions );
	}

	public function get_edit_url() {
		if ( $this->is_elementor_template() ) {
			return $this->platform_data->get_edit_url();
		}

		$link = get_edit_post_link( $this->get_main_id() );

		if ( ! $link ) {
			return new WP_Error( 'went_wrong', __( 'Something went wrong.' ) );
		}

		return $link;
	}

	public function update_post( $data ) {
		if ( ! isset( $data['ID'] ) ) {
			$data['ID'] = $this->post->ID;
		}
		$updated = wp_update_post( $data );
		if ( is_wp_error( $updated ) ) {
			return false;
		}

		return $updated;
	}
}