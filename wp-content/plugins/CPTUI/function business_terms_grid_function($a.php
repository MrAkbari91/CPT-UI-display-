function business_terms_grid_function($atts)
{
    $grid_value = get_option('business_terms_display_grid');
    $total_posts = $grid_value['column'] * $grid_value['rows'];

    // Shortcode attributes
    $category = isset($atts['category']) ? $atts['category'] : ''; // Category slug
    $category_id = isset($atts['category_id']) ? $atts['category_id'] : ''; // Category ID

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $total_posts,
        'paged' => $paged,
        'post_status' => 'publish',
        'order' => 'ASC'
    );
    if (!empty($category)) {
        $args['category_name'] = $category;
    }

// Add category ID filter if provided in shortcode
    if (!empty($category_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'your_taxonomy_name', // Replace with your actual taxonomy name
                'field' => 'id',
                'terms' => $category_id,
            ),
        );
    }
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