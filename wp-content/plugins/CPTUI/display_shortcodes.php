<?php

//  grid view shortcode
add_shortcode('business_terms_grid', 'business_terms_grid_function');

function business_terms_grid_function($atts)
{
    $grid_value = get_option('business_terms_display_grid');
    $total_posts = $grid_value['column'] * $grid_value['rows'];

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    // Shortcode attributes
    $category = isset($atts['category']) ? $atts['category'] : ''; // Category slug
    $category_id = isset($atts['category_id']) ? $atts['category_id'] : ''; // Category ID

    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $total_posts,
        'paged' => $paged,
        'post_status' => 'publish',
        'order' => 'ASC'
    );

    // Add category slug filter if provided in shortcode
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }

    // Add category ID filter if provided in shortcode
    if (!empty($category_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category', // Corrected taxonomy parameter
                'field' => $category_id, // Corrected field parameter
                'terms' => $category_id,
            ),
        );
    }

    $custom_query = new WP_Query($args);

    ob_start();

    if ($custom_query->have_posts()):
        ?>
        <style>
            :root {
                --grid-column: <?php echo $grid_value['column']; ?>;
                --grid-row: <?php echo $grid_value['rows']; ?>;
            }
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

    $category = isset($atts['category']) ? $atts['category'] : ''; // Category Slug
    $category_id = isset($atts['category_id']) ? $atts['category_id'] : ''; // Category ID

    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => $paged,
    );

    // Add category slug filter if provided in shortcode
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }

    // Add category ID filter if provided in shortcode
    if (!empty($category_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category', // Corrected taxonomy parameter
                'field' => $category_id, // Corrected field parameter
                'terms' => $category_id,
            ),
        );
    }

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

    // Set up the query arguments
    $category = isset($atts['category']) ? $atts['category'] : ''; // Category Slug
    $category_id = isset($atts['category_id']) ? $atts['category_id'] : ''; // Category ID

    // Get the latest posts
    $args = array(
        'post_type' => 'business-terms',
        'posts_per_page' => $carousel_value['display_terms'],
        'orderby' => 'date', // Order by date to get the latest posts
        'order' => 'DESC',  // Order in descending order
    );

    // Add category slug filter if provided in shortcode
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }

    // Add category ID filter if provided in shortcode
    if (!empty($category_id)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business-category', // Corrected taxonomy parameter
                'field' => $category_id, // Corrected field parameter
                'terms' => $category_id,
            ),
        );
    }
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
        <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__); ?>public/js/owl.carousel.min.js "></script>
        <?php
        wp_reset_postdata(); // Reset post data to the main query
    } else {
        echo 'No custom posts found.';
    }

    return ob_get_clean();
}

/*
 * create shortcode for alpha filter
 * */

function business_terms_fetch_alphabets()
{
    // Custom Post Type Slug
    $post_type = 'business-terms';

    // Retrieve all posts of the custom post type
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );
    $posts = new WP_Query($args);

    // Initialize an array to store the alphabet sections
    $sections = array();

    // Iterate through each post and categorize them by alphabet
    if ($posts->have_posts()) {
        while ($posts->have_posts()):
            $posts->the_post();
            $first_char = strtoupper(substr(get_the_title(), 0, 1));
            $sections[$first_char][] = array(
                'name' => get_the_title(),
                'id' => get_the_ID(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID()),
                'excerpt' => get_the_excerpt(),
                'link' => get_permalink(),
            );
        endwhile;
    }

    // Reset the post data
    wp_reset_postdata();
    return $sections;
}


add_shortcode('terms_by_alphabet', 'display_terms_by_alphabet');
function display_terms_by_alphabet()
{
    $sections = business_terms_fetch_alphabets();

    echo do_shortcode( '[alphabet_navbar]' );
    // Render the alphabet sections and posts
    foreach ($sections as $letter => $section_posts) {
        // Check if the section index is even
        $is_even_section = (array_search($letter, array_keys($sections)) % 2 === 0);

        // Set the background color for even sections
        $section_style = ($is_even_section) ? 'background-color: #232323;' : '';
        // Output the alphabet section ID
        echo '<section id="' . $letter . '" class="alphabate-section section" style="' . $section_style . '"><div class="container">';

        // Output the alphabet section content
        echo '<div class="alphabate">' . $letter . '</div>';

        // Output the post cards within the alphabet section
        echo '<div class="terms-card-grid" >';
        foreach ($section_posts as $post) {
            echo '<div><a href="' . $post['link'] . '"><h3 class="term-name">' . $post['name'] . ' ›</h3></a><p>' . $post['excerpt'] . '</p></div>';
        }
        echo '</div></div></section>';
    }
}

add_shortcode('alphabet_navbar', 'display_alphabet_navbar');

function display_alphabet_navbar()
{

    $sections = business_terms_fetch_alphabets();
    echo '<nav id="navbar" class="tabs"><ul class="alphabate-navbar primary">';
    foreach ($sections as $letter => $section_posts) {
        echo '<li><a class="nav-link" href="#' . $letter . '">' . $letter . '</a></li>';
    }
    echo '</ul></nav>';
}

?>