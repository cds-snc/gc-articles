<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Plugin Name: CDS-SNC Base
 * Plugin URI: https://github.com/cds-snc/platform-mvp
 * Description: Custom Block setup and other overrides
 * Version: 1.0.0
 * Author: Tim Arney
 *
 * @package cds-snc-base
 */

defined('ABSPATH') || exit();

if (!defined('BASE_PLUGIN_NAME')) {
    define('BASE_PLUGIN_NAME', 'cds-base');
}

require_once __DIR__ . '/admin-cleaner/index.php';
require_once __DIR__ . '/subscriptions/index.php';

require_once __DIR__ . '/email/NotifyTemplateSender.php';

function cds_plugin_images_url($filename)
{
    return plugin_dir_url(__FILE__) . 'images/' . $filename;
}

function cds_base_style_admin(): void
{
    if (is_super_admin()) {
        return;
    }
    // add stylesheet to the wp admin
    wp_enqueue_style(
        'cds-base-style',
        plugin_dir_url(__FILE__) . 'css/admin.css',
        [],
        1,
    );
}

add_action('admin_head', 'cds_base_style_admin');

function cds_base_js_admin(): void
{
    if (is_super_admin()) {
        return;
    }
    // add stylesheet to the wp admin
    wp_enqueue_script(
        'blog-customizer',
        plugins_url('js/admin.js', __FILE__),
        ['jquery'],
        '1.0.0',
        true,
    );
}

add_action('admin_enqueue_scripts', 'cds_base_js_admin');

/**
 * Load all translations for our plugin from the MO file.
 */
add_action('init', 'cds_textdomain');

function cds_textdomain(): void
{
    load_plugin_textdomain('cds-snc', false, basename(__DIR__) . '/languages');
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function cds_register_block(): void
{
    // automatically load dependencies and version
    $asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';

    wp_register_script(
        'cds-snc',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
    );

    /* blocks */

    register_block_type('cds-snc/expander', [
        'editor_script' => 'cds-snc',
    ]);

    register_block_type('cds-snc/alert', [
        'editor_script' => 'cds-snc',
    ]);

    /* table styles */
    register_block_style('core/table', [
        'name' => 'bordered-table',
        'label' => 'Bordered Table',
    ]);

    register_block_style('core/table', [
        'name' => 'filterable',
        'label' => 'Filterable Table',
    ]);

    register_block_style('core/table', [
        'name' => 'responsive-cards',
        'label' => 'Responsive Cards Table',
    ]);

    if (function_exists('wp_set_script_translations')) {
        /**
         * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
         * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
         * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
         */
        wp_set_script_translations('cds-snc-base', 'cds-snc');
    }
}

add_action('init', 'cds_register_block');
