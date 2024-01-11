<?php
/*Template Name: News*/
get_header();
query_posts(array(
    'post_type' => 'business-term',
)); ?>
<?php
while (have_posts()) : the_post(); ?>
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
    <p><?php the_excerpt(); ?></p>
<?php endwhile;
get_footer();
?>