<?php

namespace Templately\Builder\Conditions;

use Templately\Builder\ThemeBuilder;

/**
 * Code Inspiration from Elementor
 */
abstract class Condition {
	/**
	 * @var ThemeBuilder
	 */
	protected $builder;

	protected $controls = [];
	protected $sub_conditions = [];

	public function __construct( $args = [] ) {
		$this->builder = templately()->theme_builder;

		$this->register_sub_conditions();
		$this->register_controls();
	}

	protected function register_sub_conditions() {
	}

	protected function register_controls() {
	}

	public function get_priority(): int {
		return 100;
	}

	public function get_config(): array {
		$config = [];

		$config['label']          = $this->get_label();
		$config['plural_label']   = $this->get_all_label();
		$config['sub_conditions'] = $this->get_sub_conditions();
		$config['type']           = $this->get_type();
		$config['name']           = $this->get_name();

		if ( ! empty( $this->get_controls() ) ) {
			$config['controls'] = $this->get_controls();
		}

		return $config;
	}

	abstract public function get_label(): string;

	public function get_all_label(): string {
		return $this->get_label();
	}

	public function get_sub_conditions() {
		return $this->sub_conditions;
	}

	abstract public function get_type(): string;

	abstract public function get_name(): string;

	private function get_controls() {
		return $this->controls;
	}

	public function check( $args = [] ): bool {
		return true;
	}

	public function add_control( $control_name, $control ) {
		$this->controls[ $control_name ] = $control;
	}

	/**
	 * @param Condition $condition
	 */
	public function register_sub_condition( Condition $condition ) {
		$this->sub_conditions[] = $condition->get_name();
		$this->builder::$conditions_manager->register_condition_instance( $condition );
	}
}