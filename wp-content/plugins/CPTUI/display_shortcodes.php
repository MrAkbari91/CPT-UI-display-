<?php

//  grid view shortcode
add_shortcode('business_terms_grid', 'business_terms_grid_function');

function business_terms_grid_function($atts)
{
    $grid_value = get_option('business_terms_display_grid');
    $atts = shortcode_atts(
        array(
            'posts_per_page' => $grid_value['column'] * $grid_value['rows'],
        ),
        $atts,
        'custom_post_type'
    );
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => $paged,
    );

    $custom_query = new WP_Query($args);

    ob_start();

    if ($custom_query->have_posts()):
        ?>
        <style>
            .business-terms-grid-view .grid {
                display: grid;
                grid-template-columns: repeat(<?php echo $grid_value['column']; ?>, minmax(0, 1fr));
                gap: 20px;
            }
            @media screen and (max-width: 1024px) {
                .business-terms-grid-view .grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
            @media screen and (max-width: 768px) {
                .business-terms-grid-view .grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            @media screen and (max-width: 480px) {
                .business-terms-grid-view .grid {
                    grid-template-columns: repeat(1, minmax(0, 1fr));
                }
            }
        </style>

        <div id="business-terms-wrapper" class="business-terms-grid-view">
            <div class="grid">
                <?php
                while ($custom_query->have_posts()):
                    $custom_query->the_post();
                    ?>
                    <article class="card">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="url">
                            <div class="content">
                                <h2 class="title">
                                    <?php the_title(); ?>
                                </h2>
                                <div class="post-description">
                                <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php
                endwhile;
                ?>
            </div>
        </div>
        <?php

        // Pagination
        echo "<div class='pagination'>" . paginate_links(
                array(
                    'total' => $custom_query->max_num_pages,
                    'prev_text' => __('« Previous'),
                    'next_text' => __('Next »'),
                )
            ) . "</div>";

        wp_reset_postdata(); // Reset post data to the main query
    else:
        echo 'No custom posts found.';
    endif;

    return ob_get_clean();
}


//  list view shortcode
add_shortcode('business_terms_list', 'business_terms_list_function');

function business_terms_list_function($atts)
{
    $list_value = get_option('business_terms_display_list');
    $atts = shortcode_atts(
        array(
            'posts_per_page' => $list_value['display_terms'],
        ),
        $atts,
        'custom_post_type'
    );

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => $paged,
    );

    $custom_query = new WP_Query($args);

    ob_start();

    if ($custom_query->have_posts()):
        ?>

        <div id="business-terms-list-wrapper" class="business-terms-list-view">
            <div class="list">
                <?php
                while ($custom_query->have_posts()):
                    $custom_query->the_post();
                    ?>
                    <article class="card">
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail_url(); ?>"/></a>
                        <div class="content">
                            <a href="<?php the_permalink(); ?>">
                                <h2 class="title">
                                    <?php the_title(); ?>
                                </h2>
                            </a>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php
                endwhile;
                ?>
            </div>
        </div>
        <?php

        // Pagination
        echo "<div class='pagination'>" . paginate_links(
                array(
                    'total' => $custom_query->max_num_pages,
                    'prev_text' => __('« Previous'),
                    'next_text' => __('Next »'),
                )
            ) . "</div>";

        wp_reset_postdata(); // Reset post data to the main query
    else:
        echo 'No custom posts found.';
    endif;

    return ob_get_clean();
}


//  carousel view shortcode
add_shortcode('business_terms_carousel', 'business_terms_carousel_function');

function business_terms_carousel_function()
{
    $carousel_value = get_option('business_terms_display_carousel');

    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $carousel_value['display_terms'],
        'orderby' => 'date', // Order by date to get the latest posts
        'order' => 'DESC',  // Order in descending order
    );

    $business_terms_posts = get_posts($args);

    ob_start();

    if ($business_terms_posts) {
        ?>
        <div id="owl-carousel-slider" class="owl-carousel">
            <?php
            foreach ($business_terms_posts as $post) {
                $post_permalink = get_permalink($post->ID);
                $post_title = $post->post_title;
                $post_excerpt = $post->post_excerpt;
                $post_thumbnail = get_the_post_thumbnail_url($post->ID);
                ?>
                <div class="terms-slide">
                    <div class="post-img">
                        <img src="<?php echo $post_thumbnail; ?>" alt=""/>
                    </div>
                    <div class="post-content">
                        <a href="<?php echo $post_permalink; ?>">
                            <h3 class="post-title">
                                <?php echo $post_title; ?>
                            </h3>
                        </a>
                        <p class="post-description">
                            <?php echo $post_excerpt; ?>
                        </p>
                        <a href="<?php echo $post_permalink; ?>" class="read-more">read more</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        wp_reset_postdata(); // Reset post data to the main query
    } else {
        echo 'No custom posts found.';
    }

    return ob_get_clean();
}


?>
