<?php

/**
 * Plugin Name:     Cds Js Test
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     cds-js-test
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Cds_Js_Test
 */

// Your code starts here.

add_action('init', 'loadTextdomain');

function loadTextdomain()
{
    return load_plugin_textdomain('cds-js-test', false, 'cds-js-test/languages');
}


/**
 * Init all
 */
function run()
{
    //plugins_url('script.js', __FILE__),
    wp_register_script(
        'script',
        plugins_url('sample-react-app/dist/bundle.js', __FILE__),
        array('wp-i18n'),
        false,
        true
    );
    wp_enqueue_script('script');

    wp_set_script_translations('script', 'cds-js-test', plugin_dir_path(__FILE__) . 'languages/');
    load_plugin_textdomain('cds-js-test', false, plugin_dir_path(__FILE__) . 'languages/');
}
add_action('init', 'run');

/**
 * Register a custom menu page.
 */
function register_my_custom_menu_page()
{
    add_menu_page(
        'Custom Menu Title',
        __('Custom Menu', 'cds-js-test'),
        'manage_options',
        'my_custom',
        'callback'
    );
}
add_action('admin_menu', 'register_my_custom_menu_page');

/**
 * Display a custom menu page
 */
function callback()
{
    esc_html_e('Admin Page', 'cds-js-test'); ?>
    <h1 id="h1"></h1>
    <div id="app"></div>
<?php }
