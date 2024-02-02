<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cube_blog
 */

get_header();
?>

<div id="content-wrap" class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="single-post-wrap">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'single' );

					the_post_navigation(
						array(
							'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'cube-blog' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'cube-blog' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . cube_blog_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
							'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'cube-blog' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'cube-blog' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . cube_blog_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
						)
					);

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div><!-- .single-post-wrap -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>

</div><!-- .container -->

<?php
get_footer();
