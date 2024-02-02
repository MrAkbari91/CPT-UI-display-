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
	 * @since 1.8.6 Added `$icon` parameter.
	 *
	 * @param string $text     Badge text.
	 * @param string $size     Badge size.
	 * @param string $position Badge position.
	 * @param string $color    Badge color.
	 * @param string $shape    Badge shape.
	 * @param string $icon     Badge icon name in Font Awesome "format", e.g. `fa-check`, defaults to empty string.
	 *
	 * @return string
	 */
	public static function get_badge(
		string $text,
		string $size = 'sm',
		string $position = 'inline',
		string $color = 'titanium',
		string $shape = 'rounded',
		string $icon = ''
	): string {

		if ( ! empty( $icon ) ) {
			$icon = self::get_inline_icon( $icon );
		}

		return sprintf(
			'<span class="wpforms-badge wpforms-badge-%1$s wpforms-badge-%2$s wpforms-badge-%3$s wpforms-badge-%4$s">%5$s%6$s</span>',
			esc_attr( $size ),
			esc_attr( $position ),
			esc_attr( $color ),
			esc_attr( $shape ),
			wp_kses(
				$icon,
				[
					'i' => [
						'class'       => [],
						'aria-hidden' => [],
					],
				]
			),
			esc_html( $text )
		);
	}

	/**
	 * Print badge HTML.
	 *
	 * @since 1.8.5
	 * @since 1.8.6 Added `$icon` parameter.
	 *
	 * @param string $text     Badge text.
	 * @param string $size     Badge size.
	 * @param string $position Badge position.
	 * @param string $color    Badge color.
	 * @param string $shape    Badge shape.
	 * @param string $icon     Badge icon name in Font Awesome "format", e.g. `fa-check`, defaults to empty string.
	 */
	public static function print_badge(
		string $text,
		string $size = 'sm',
		string $position = 'inline',
		string $color = 'titanium',
		string $shape = 'rounded',
		string $icon = ''
	) {

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo self::get_badge( $text, $size, $position, $color, $shape, $icon );
	}

	/**
	 * Get inline icon HTML.
	 *
	 * @since 1.8.6
	 *
	 * @param string $name Font Awesome icon name, e.g. `fa-check`.
	 *
	 * @return string HTML markup for the icon element.
	 */
	public static function get_inline_icon( string $name ): string {
		// phpcs:ignore WPForms.Formatting.EmptyLineBeforeReturn.RemoveEmptyLineBeforeReturnStatement
		return sprintf( '<i class="fa %1$s" aria-hidden="true"></i>', esc_attr( $name ) );
	}
}
