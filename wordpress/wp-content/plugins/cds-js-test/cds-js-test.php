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

/**
 * Init all
 */
function run()
{
    load_plugin_textdomain('cds-js-test', false, 'cds-js-test/languages/');
    // https://developer.wordpress.org/reference/classes/wp_scripts/set_translations/

    wp_register_script(
        'script',
        plugins_url('script.js', __FILE__),
        array('wp-element','wp-i18n'),
        false,
        true
    );
    wp_enqueue_script('script');

    wp_set_script_translations('script', 'cds-js-test', plugin_dir_path(__FILE__) . 'languages/');


    wp_register_script(
        'script1',
        plugins_url('sample-react-app/dist/bundle.js', __FILE__),
        array('wp-element','wp-i18n'),
        false,
        true
    );

    wp_enqueue_script('script1');
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
    <div id="app1"></div>
    <hr>
    <div id="app"></div>
<?php }
