<?php

namespace WPForms\Admin\Education;

/**
 * Helpers class.
 *
 * @since 1.8.5
 */
class Helpers {

	/**
	 * Get badge HTML.
	 *
	 * @since 1.8.5
	 *
	 * @param string $text     Badge text.
	 * @param string $size     Badge size.
	 * @param string $position Badge position.
	 * @param string $color    Badge color.
	 * @param string $shape    Badge shape.
	 *
	 * @return string
	 */
	public static function get_badge(
		string $text,
		string $size = 'sm',
		string $position = 'inline',
		string $color = 'titanium',
		string $shape = 'rounded'
	): string {

		// phpcs:ignore WPForms.Formatting.EmptyLineBeforeReturn.RemoveEmptyLineBeforeReturnStatement
		return sprintf(
			'<span class="wpforms-badge wpforms-badge-%1$s wpforms-badge-%2$s wpforms-badge-%3$s wpforms-badge-%4$s">%5$s</span>',
			esc_attr( $size ),
			esc_attr( $position ),
			esc_attr( $color ),
			esc_attr( $shape ),
			esc_html( $text )
		);
	}

	/**
	 * Print badge HTML.
	 *
	 * @since 1.8.5
	 *
	 * @param string $text     Badge text.
	 * @param string $size     Badge size.
	 * @param string $position Badge position.
	 * @param string $color    Badge color.
	 * @param string $shape    Badge shape.
	 */
	public static function print_badge(
		string $text,
		string $size = 'sm',
		string $position = 'inline',
		string $color = 'titanium',
		string $shape = 'rounded'
	) {

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::get_badge( $text, $size, $position, $color, $shape );
	}
}
