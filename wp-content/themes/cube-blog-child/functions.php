<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array('cube-blog-blocks'));
        // add script file
        wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/script.js', array(), null, true);
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

// END ENQUEUE PARENT ACTION







add_shortcode('custom_slider_section', 'custom_slider_section_callback');

function custom_slider_section_callback()
{
    $post_id = get_the_id();
    $value = get_field("custom_slider", $post_id);
    ob_start();
    if ($value) {
        ?>
        <section id="screenshots">
            <div class="screenshots-container">
                <h3 class="black-section-title sal-animate" data-sal="slide-up" data-sal-duration="300" data-sal-delay="5">
                    Engage with your audience</h3>
                <div class="module">
                    <div class="module-text">
                        <div data-sal="slide-up" data-sal-duration="300" data-sal-delay="200" class="sal-animate">
                            <?php
                            $counter = 0;
                            foreach ($value as $item) {
                                $slider_title = $item['slider_title'];
                                $slider_text = $item['slider_description'];
                                $screenshot_image = $item['screenshot_image'];
                                $is_first_iteration = ($counter === 0);
                                ?>

                                <div class="feature-row <?php echo ($is_first_iteration) ? 'open-feature' : ''; ?>"
                                    data-feature-img="<?php echo $screenshot_image ?>">
                                    <button>
                                        <h4>
                                            <?php echo $slider_title ?>
                                        </h4>
                                        <div class="feature-icon">
                                            <i class="fa fa-angle-down"></i>
                                            <i class="fa fa-angle-up"></i>
                                        </div>
                                    </button>
                                    <div class="feature-text">
                                        <div>
                                            <p>
                                                <?php echo $slider_text ?>
                                            </p>
                                        </div>
                                        <div class="screenshot mobile-screenshot lozad sal-animate"
                                            data-background-image="<?php echo $screenshot_image ?>" data-sal="slide-left"
                                            data-sal-once="false" data-sal-duration="600" data-sal-delay="200" data-loaded="true"
                                            style="background-image: url(&quot;<?php echo $screenshot_image ?>&quot;); opacity: 1; transform: none;">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $counter++;
                            }
                            ?>
                        </div>
                    </div>



                    <div class="screenshot-container no-accent">
                        <div>
                            <div class="top-accent lozad sal-animate" data-background-image="" data-sal="zoom-in"
                                data-sal-duration="300" data-sal-delay="200" data-loaded="true"></div>
                            <div class="screenshot-frame">
                                <div class="screenshot lozad sal-animate" data-background-image="" data-sal="slide-left"
                                    data-sal-once="false" data-sal-duration="300" data-sal-delay="200" data-loaded="true"
                                    style="background-image: url(&quot;http://localhost/wp-cptui/wp-content/uploads/2024/01/ai-generated-lion-feline-8493395.jpg&quot;); opacity: 1; transform: none;">
                                </div>
                            </div>
                            <div class="bottom-accent lozad sal-animate" data-background-image="" data-sal="zoom-in"
                                data-sal-duration="300" data-sal-delay="200"
                                style="background-image: url(&quot;http://localhost/wp-cptui/wp-content/uploads/2024/01/ai-generated-lion-feline-8493395.jpg&quot;);"
                                data-loaded="true"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




        <section id="home-features" class="report buddy-report">
            <div class="container">
                <h3 class="black-section-title sal-animate" data-sal="slide-up" data-sal-duration="600" data-sal-delay="5">
                    Limitless growth, powerful solutions</h3>
                <div class="module">
                    <div class="module-text">
                        <div data-sal="slide-up" data-sal-duration="600" data-sal-delay="200" class="sal-animate">





                            <div class="feature-row" data-feature-img="" data-feature-skin="inbox" data-feature-icon="">
                                <button>
                                    <h4>Simplify handling multiple clients effortlessly</h4>
                                    <div class="feature-icon">
                                        <i class="chevron-down"></i>
                                        <i class="chevron-up"></i>
                                    </div>
                                </button>
                                <div class="feature-text">
                                    <p>Experience seamless cross-platform publishing across Instagram, Facebook, YouTube,
                                        TikTok, X (Twitter), and LinkedIn. Manage scheduling for Reels, Stories, TikTok videos,
                                        and more content types within a single platform.
                                    </p>
                                    <div class="screenshot mobile-screenshot lozad" data-background-image="" data-loaded="true"
                                        style="background-image: url(&quot;/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;); opacity: 1; transform: none;">
                                    </div>
                                </div>
                            </div>



                            <div class="feature-row"
                                data-feature-img="https://www.agorapulse.com/assets/social-media-publishing/Request_Changes_EN_1.png"
                                data-feature-skin="publish" data-feature-icon="">
                                <button>
                                    <h4>Share the big picture</h4>
                                    <div class="feature-icon">
                                        <i class="chevron-down"></i>
                                        <i class="chevron-up"></i>
                                    </div>
                                </button>
                                <div class="feature-text">
                                    <p>Easily incorporate your clients into the social media scheduling process and give them
                                        visibility on upcoming posts and overall social strategy.
                                    </p>
                                    <div class="screenshot mobile-screenshot lozad"
                                        data-background-image="https://www.agorapulse.com/assets/social-media-publishing/Request_Changes_EN_1.png"
                                        style="opacity: 1; transform: none; background-image: url(&quot;/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;);"
                                        data-loaded="true"></div>
                                </div>
                            </div>



                            <div class="feature-row"
                                data-feature-img="https://www.agorapulse.com/assets/social-media-publishing/Inbox_Assign_EN_1.png"
                                data-feature-skin="report" data-feature-icon="">
                                <button>
                                    <h4>Easily collaborate as a team</h4>
                                    <div class="feature-icon">
                                        <i class="chevron-down"></i>
                                        <i class="chevron-up"></i>
                                    </div>
                                </button>
                                <div class="feature-text">
                                    <p>Delegate approval processes with workflows for enhanced accountability and transparency.
                                        Utilize bulk actions. Collaborate through shared notes, track action items, and monitor
                                        real-time communication to stay updated on progress.
                                    </p>
                                    <div class="screenshot mobile-screenshot lozad"
                                        data-background-image="https://www.agorapulse.com/assets/social-media-publishing/Inbox_Assign_EN_1.png"
                                        style="opacity: 1; transform: none; background-image: url(&quot;/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;);">
                                    </div>
                                </div>
                            </div>



                            <div class="feature-row"
                                data-feature-img="https://www.agorapulse.com/assets/social-media-inbox/Inbox2-Saved-Replies-EN.png"
                                data-feature-skin="analytics-1" data-feature-icon="">
                                <button>
                                    <h4>Manage your social inbox efficiently</h4>
                                    <div class="feature-icon">
                                        <i class="chevron-down"></i>
                                        <i class="chevron-up"></i>
                                    </div>
                                </button>
                                <div class="feature-text">
                                    <p>Use inbox filters, saved replies, and one-click translations to achieve more in less
                                        time. Label, assign, and bookmark items, so all team members can easily manage inbox
                                        activities.
                                    </p>
                                    <div class="screenshot mobile-screenshot lozad"
                                        data-background-image="https://www.agorapulse.com/assets/social-media-inbox/Inbox2-Saved-Replies-EN.png"
                                        style="opacity: 1; transform: none; background-image: url(&quot;/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;);"
                                        data-loaded="true"></div>
                                </div>
                            </div>



                            <div class="feature-row"
                                data-feature-img="https://www.agorapulse.com/assets/screenshots/Social-Media-ROI-EN.png"
                                data-feature-skin="analytics-2" data-feature-icon="">
                                <button>
                                    <h4>Insightful reporting</h4>
                                    <div class="feature-icon">
                                        <i class="chevron-down"></i>
                                        <i class="chevron-up"></i>
                                    </div>
                                </button>
                                <div class="feature-text">
                                    <p>Effortlessly generate comprehensive reports delivered to inboxes, using labels, tags, and
                                        content from your inbox or published materials. Streamline reporting with automated
                                        white-label reports. Customize metrics, date ranges, and profiles.
                                    </p>
                                    <div class="screenshot mobile-screenshot lozad"
                                        data-background-image="https://www.agorapulse.com/assets/screenshots/Social-Media-ROI-EN.png"
                                        style="opacity: 1; transform: none; background-image: url(&quot;/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;);"
                                        data-loaded="true"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- 
                    <div class="module-img">
                        <div class="screenshot-container">
                            <div>
                                <div class="screenshot lozad sal-animate" data-sal="slide-left" data-sal-once="false"
                                    data-sal-duration="600" data-sal-delay="400" data-background-image="" data-loaded="true"
                                    style="background-image: url(&quot;https://www.agorapulse.com/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;); opacity: 1; transform: none;">
                                </div>
                                <div class="bottom-accent style2 lozad sal-animate" data-sal="zoom-in" data-sal-duration="300"
                                    data-sal-delay="700" data-background-image="" data-loaded="true"
                                    style="transform: none; background-image: url(&quot;https://www.agorapulse.com/assets/social-media-publishing/Inbox_Assign_EN_1.png&quot;); opacity: 1;"></div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </section>
        <?php
    }
    return ob_get_clean();
}



add_shortcode("custom_file_info", "custom_file_info");
function custom_file_info($post)
{
    $attechment = get_field('pdf_file');
    $pdf_pages = get_field('pdf_pages');
    $file_size = size_format($attechment['filesize']);
    $file_name = $attechment['filename'];
    $file = $attechment['url'];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $contents = "";
    //open the file for reading
    if ($ext == "pdf") {
        $icon = "fa fa-file-pdf-o red";
    } else if ($ext == "docx") {
        $icon = "fa fa-file-word-o blue";
    } else if ($ext == "doc") {
        $icon = "fa fa-file-word-o blue";
    }

    ob_start();
    ?>
    <div class="file-info">
        <ul>
            <li>File type:<i class="<?php echo $icon; ?>"></i>
                (<?php echo $ext; ?>)
            </li>
            <li>File Size:
                <?php echo $file_size; ?>
            </li>
            <li>total pages:
                <?php echo $pdf_pages; ?>
            </li>

        </ul>
    </div>
    <?php

    return ob_get_clean();
}