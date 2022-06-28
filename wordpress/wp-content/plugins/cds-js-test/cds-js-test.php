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

add_action('admin_menu', 'addMenu');
add_action('init', 'loadTextdomain');

function addMenu()
{
    add_menu_page(
        __('Test', "cds-js-test"),
        __('Test', "cds-js-test"),
        'edit-post',
        'test-js',
        'render',
        'dashicons-email'
    );
}

function render()
{
    echo "<h1>" . __("Huzzah", "cds-js-test") . "</h1>";
}

function loadTextdomain()
{
    return load_plugin_textdomain('cds-js-test', false, 'cds-js-test/languages');
}
