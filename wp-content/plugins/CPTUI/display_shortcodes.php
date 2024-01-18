<?php

//  grid view shortcode
add_shortcode('business_terms_grid', 'business_terms_grid_function');

function business_terms_grid_function($atts)
{
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 2,
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

        <div id="business-terms-wrapper" class="business-terms-grid-view">
            <div class="grid" style="">
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
                                <?php the_excerpt(); ?>
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
            'posts_per_page' => $list_value['post'],
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

function business_terms_carousel_function($atts)
{
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 4,
        ),
        $atts,
        'custom_post_type'
    );

    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $atts['posts_per_page'],
    );

    $custom_query = new WP_Query($args);

    ob_start();

    if ($custom_query->have_posts()):
        ?>

        <div id="business-terms-wrapper" class="business-terms-carousel-view">
            <div id="slider-container">
                <span onclick="slideRight()" class="btn"></span>
                <div id="carousel-slider">
                    <?php
                    while ($custom_query->have_posts()):
                        $custom_query->the_post();
                        ?>
                        <article class="slide">
                            <div class="card">
                                <img src="<?php echo get_the_post_thumbnail_url(); ?>"/>
                                <div class="content">
                                    <a href="<?php the_permalink(); ?>">
                                        <h2 class="title">
                                            <?php the_title(); ?>
                                        </h2>
                                    </a>
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </article>
                    <?php
                    endwhile;
                    ?>
                </div>
                <span onclick="slideLeft()" class="btn"></span>
            </div>
        </div>
        <?php

        wp_reset_postdata(); // Reset post data to the main query
    else:
        echo 'No custom posts found.';
    endif;

    return ob_get_clean();
}


?>
