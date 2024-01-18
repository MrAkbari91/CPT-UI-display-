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

function business_terms_carousel_function($atts)
{
    $carousel_value = get_option('business_terms_display_carousel');
    $atts = shortcode_atts(
        array(
            'posts_per_page' => $carousel_value['display_terms'],
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
            <main class="cptui-carousel">
                <ul class='cptui-slider'>
                    <?php
                    while ($custom_query->have_posts()):
                        $custom_query->the_post();
                        ?>
                        <li class='cptui-item'
                            style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>')">
                            <div class='cptui-content'>
                                <h2 class='cptui-title'><?php the_title(); ?></h2>
                                <?php the_excerpt(); ?>
                                <button><a href='<?php the_permalink(); ?>'>Read More</a></button>
                            </div>
                        </li>
                    <?php
                    endwhile;
                    ?>
                </ul>
                <nav class='cptui-nav'>
                    <span class='btn prev' name="arrow-back-outline"><<</span>
                    <span class='btn next' name="arrow-forward-outline">>></span>
                </nav>
            </main>
        </div>
        <?php

        wp_reset_postdata(); // Reset post data to the main query
    else:
        echo 'No custom posts found.';
    endif;

    return ob_get_clean();
}


?>
