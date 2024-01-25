<?php

namespace Templately\Builder\Conditions;

use Templately\Builder\Conditions\Condition;

class WooCommerce extends Condition {

	public function get_type(): string {
		return 'woocommerce';
	}

	public function get_label(): string {
		return __( 'WooCommerce', 'templately' );
	}

	public function get_name(): string {
		return 'woocommerce';
	}

	protected function register_sub_conditions() {
		if( ! class_exists( '\woocommerce' ) ) {
			return;
		}

		$product_archive_condition = new PostTypeArchive( [
			'post_type' => 'product',
		] );

		$single_product = new Post([ 'post_type' => 'product' ]);

		$this->register_sub_condition( $product_archive_condition );
		$this->register_sub_condition( $single_product );
	}
}