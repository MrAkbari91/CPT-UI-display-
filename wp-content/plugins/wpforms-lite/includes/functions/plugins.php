<?php
/**
 * Helper functions to perform various plugins and addons related actions.
 *
 * @since 1.8.2.2
 */

use WPForms\Requirements\Requirements;

/**
 * Check if addon met requirements.
 *
 * @since 1.8.2.2
 *
 * @param array $requirements Addon requirements.
 *
 * @return bool
 */
function wpforms_requirements( array $requirements ): bool {

	// phpcs:ignore WPForms.Formatting.EmptyLineBeforeReturn.RemoveEmptyLineBeforeReturnStatement
	return Requirements::get_instance()->validate( $requirements );
}

/**
 * Check addon requirements and activate addon or plugin.
 *
 * @since 1.8.4
 *
 * @param string $plugin Path to the plugin file relative to the plugins' directory.
 *
 * @return null|WP_Error Null on success, WP_Error on invalid file.
 */
function wpforms_activate_plugin( string $plugin ) {

	$activate = activate_plugin( $plugin );

	if ( is_wp_error( $activate ) ) {
		return $activate;
	}

	$requirements = Requirements::get_instance();

	if ( ! $requirements->deactivate_not_valid_addon( $plugin ) ) {
		return null;
	}

	// Addon was deactivated due to requirements issues.
	return new WP_Error(
		'wpforms_addon_incompatible',
		implode(
			"\n",
			$requirements->get_notices()
		)
	);
}
