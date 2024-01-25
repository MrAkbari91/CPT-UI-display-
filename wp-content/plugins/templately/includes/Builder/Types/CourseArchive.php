<?php

namespace Templately\Builder\Types;

class CourseArchive extends Archive {
	public static function get_type(): string {
		return 'course_archive';
	}

	public static function get_title(): string {
		return __( 'Course Archive', 'templately' );
	}

	public static function get_plural_title(): string {
		return __( 'Course Archives', 'templately' );
	}

	public static function get_properties(): array {
		$properties = parent::get_properties();

		$properties['condition'] = 'include/archive/sfwd-courses_archive';
		$properties['builder']   = post_type_exists( 'sfwd-courses' );

		return $properties;
	}
}