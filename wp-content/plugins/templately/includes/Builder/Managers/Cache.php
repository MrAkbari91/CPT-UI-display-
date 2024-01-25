<?php

namespace Templately\Builder\Managers;

use Templately\Builder\Source;
use Templately\Builder\Types\ThemeTemplate;
use WP_Query;

class Cache {
	const OPTION_NAME = 'templately_builder_conditions';

	protected $conditions = [];

	public function __construct() {
		$this->refresh();
	}

	public function refresh(): Cache {
		$this->conditions = get_option( self::OPTION_NAME, [] );

		return $this;
	}

	/**
	 * @param  $document
	 * @param array $conditions
	 *
	 * @return $this
	 */
	public function update( $document, array $conditions ): Cache {
		return $this->remove( $document->get_main_id() )->add( $document, $conditions );
	}

	/**
	 * @param ThemeTemplate $document
	 * @param array $conditions
	 *
	 * @return $this
	 */
	public function add( ThemeTemplate $document, array $conditions ): Cache {
		$location = $document->get_location();
		if ( $location ) {
			if ( ! isset( $this->conditions[ $location ] ) ) {
				$this->conditions[ $location ] = [];
			}
			$this->conditions[ $location ][ $document->get_main_id() ] = $conditions;
		}

		return $this;
	}

	/**
	 * @param int $post_id
	 *
	 * @return $this
	 */
	public function remove( int $post_id ): Cache {
		$post_id = absint( $post_id );

		foreach ( $this->conditions as $location => $templates ) {
			foreach ( $templates as $id => $template ) {
				if ( $post_id === $id ) {
					unset( $this->conditions[ $location ][ $id ] );
				}
			}
		}

		return $this;
	}

	public function save(): bool {
		return update_option( self::OPTION_NAME, $this->conditions );
	}

	public function clear(): Cache {
		$this->conditions = [];

		return $this;
	}

	public function get_by_location( $location ) {
		if ( isset( $this->conditions[ $location ] ) ) {
			return $this->conditions[ $location ];
		}

		return [];
	}

	public function regenerate(): Cache {
		$this->clear();

		$query_args = [
			'posts_per_page' => - 1,
			'post_type'      => Source::CPT,
			'fields'         => 'ids',
			'meta_key'       => '_templately_conditions',
		];

		$query = new WP_Query( $query_args );

		foreach ( $query->posts as $post_id ) {
			$document = templately()->theme_builder->get_template( $post_id );

			if ( $document ) {
				$conditions = $document->get_meta( '_templately_conditions' );
				$this->add( $document, $conditions );
			}
		}

		$this->save();

		return $this;
	}
}