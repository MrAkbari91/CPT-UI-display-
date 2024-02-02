<?php
/**
 * Displays footer widgets if assigned
 *
 * @package cube_blog
 */

?>

<aside class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'cube-blog' ); ?>">
	<?php
	if ( is_active_sidebar( 'sidebar-2' ) ) {
		?>
		<div class="widget-column footer-widget-1">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</div>
		<?php
	}
	if ( is_active_sidebar( 'sidebar-3' ) ) {
		?>
		<div class="widget-column footer-widget-2">
			<?php dynamic_sidebar( 'sidebar-3' ); ?>
		</div>
		<?php 
	}

	if ( is_active_sidebar( 'sidebar-4' ) ) {
		?>
		<div class="widget-column footer-widget-3">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
		</div>
		<?php 
	}
	?>
</aside><!-- .widget-area -->


