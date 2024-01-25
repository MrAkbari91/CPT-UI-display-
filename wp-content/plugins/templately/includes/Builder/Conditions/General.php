<?php

namespace Templately\Builder\Conditions;

class General extends Condition {
	protected $sub_conditions = [
		'archive',
		'singular'
	];

	public function get_name(): string {
		return 'general';
	}

	public function get_label(): string {
		return __( "General", 'templately' );
	}

	public function get_type(): string {
		return 'general';
	}

	public function get_all_label(): string {
		return esc_html__( 'Entire Site', 'templately' );
	}
}