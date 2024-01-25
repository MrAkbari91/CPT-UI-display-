<?php
/**
 * @package Templately
 * @since 3.0.0
 */

/**
 * Print Footers if needed.
 */
templately()->theme_builder::$location_manager->do_location( 'footer' );

/**
 * Footer Template Actions For Templately
 */
do_action( 'templately_builder_footer_before' );
do_action( 'templately_builder_footer' );
do_action( 'templately_builder_footer_after' );
wp_footer();
?>
</body>
</html>
