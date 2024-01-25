<?php

namespace Templately\Builder\Types;

class CourseSingle extends Single {
	public static function get_type(): string {
		return 'course_single';
	}

	public static function get_title(): string {
		return __( 'Course Single', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Courses Single', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['condition'] = 'include/singular/sfwd-courses';
		$properties['builder']   = post_type_exists( 'sfwd-courses' );

		return $properties;
	}
}