<?php
/**
 * @package Templately
 * @since 3.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="profile" href="<?php echo is_ssl() ? 'https://' : 'http://'; ?>gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
/**
 * WP Defaults
 */
do_action( 'wp_body_open' );

/**
 * Header Template Actions For Templately
 */
do_action( 'templately_builder_header_before' );
do_action( 'templately_builder_header' );
do_action( 'templately_builder_header_after' );

/**
 * Print Headers if needed.
 */
templately()->theme_builder::$location_manager->do_location( 'header' );
