<?php

declare(strict_types=1);

function cds_login_logo(): void
{ ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo cds_plugin_images_url('site-login-logo.svg'); ?>);
            width: 300px;
            height: 59px;
            background-size: contain;
            margin-bottom: 10px;
        }

        .wp-core-ui .button-primary, .wp-core-ui .button-primary:focus {
            --bg-opacity: 1;
            background-color: #284162 !important;
            background-color: rgba(40, 65, 98, var(--bg-opacity)) !important;
        }

        body {
            --bg-opacity: 1;
            background-color: #eee;
            background-color: rgba(238, 238, 238, var(--bg-opacity));
        }


    </style>
<?php }

add_action('login_enqueue_scripts', 'cds_login_logo');

function cds_login_logo_url()
{
    return home_url();
}

add_filter('login_headerurl', 'cds_login_logo_url');

function cds_customize_login_headertext($headertext)
{
    return esc_html__('Canadian Digital Service', 'cds');
}

add_filter('login_headertext', 'cds_customize_login_headertext');

function cds_favicon(): void
{
    $favicon_url = cds_plugin_images_url('favicon.ico');
    echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}

add_action('login_head', 'cds_favicon');
add_action('admin_head', 'cds_favicon');
