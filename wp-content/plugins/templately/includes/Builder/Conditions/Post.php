<?php

namespace Templately\Builder\Conditions;

class Post extends Condition {
	private $post_type;

	public function __construct( $args = [] ) {
		$this->post_type = get_post_type_object( $args['post_type'] );

		parent::__construct( $args );
	}

	public function get_priority(): int {
		return 40;
	}

	public function get_type(): string {
		return 'singular';
	}

	public function get_label(): string {
		return $this->post_type->labels->singular_name;
	}

	public function get_all_label(): string {
		return $this->post_type->label;
	}

	public function get_name(): string {
		return $this->post_type->name;
	}

	public function check( $args = [] ): bool {
		if ( isset( $args['id'] ) ) {
			$id = (int) $args['id'];
			if ( $id ) {
				return is_singular() && get_queried_object_id() === $id;
			}
		}

		return is_singular( $this->post_type->name );
	}

	public function register_sub_conditions() {
//		foreach ( $this->post_taxonomies as $slug => $object ) {
//			$in_taxonomy = new In_Taxonomy( [
//				'object' => $object,
//			] );
//			$this->register_sub_condition( $in_taxonomy );
//
//			if ( $object->hierarchical ) {
//				$in_sub_term = new In_Sub_Term( [
//					'object' => $object,
//				] );
//				$this->register_sub_condition( $in_sub_term );
//			}
//		}
		
		$by_author = new PostByAuthor( $this->post_type );
		$this->register_sub_condition( $by_author );
	}

	protected function register_controls() {
		$this->add_control( "posts_query_" . $this->get_name(), [
			'field'      => 'id',
			'query_type' => 'posts',
			'options'    => [ '' => 'All' ],
			'query'      => [ 'post_type' => $this->get_name() ]
		] );
	}
}