<?php

/**
 * Plugin Name: CDS-SNC Base
 * Plugin URI: https://github.com/cds-snc/gc-articles
 * Description: Custom Block setup and other overrides
 * Version: 2.19.0
 * Update URI: false
 * Author: CDS-SNC
 * Text Domain: cds-snc
 * Domain Path: /languages
 *
 * @package cds-snc-base
 */

declare(strict_types=1);

use CDS\Modules\Notify\NotifyTemplateSender;
use CDS\Setup;

defined('ABSPATH') || exit();

if (!defined('BASE_PLUGIN_NAME')) {
    define('BASE_PLUGIN_NAME', 'cds-base');
}

if (!defined('BASE_PLUGIN_NAME_VERSION')) {
    define('BASE_PLUGIN_NAME_VERSION', '2.19.0');
}

if (is_multisite()) {
    define(
        'MU_PLUGIN_URL',
        network_site_url('/wp-content/mu-plugins', 'relative'),
    );
} else {
    define('MU_PLUGIN_URL', content_url('/mu-plugins'));
}

function cds_plugin_images_url($filename)
{
    return plugin_dir_url(__FILE__) . 'images/' . $filename;
}

/**
 * Load all translations for our plugin from the MO file.
 */
add_action('init', 'cds_textdomain');

function cds_textdomain(): void
{
    load_plugin_textdomain('cds-snc', false, basename(__DIR__) . '/languages');
}

function cds_admin_js(): void
{
    // automatically load dependencies and version
    $asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';

    wp_enqueue_script(
        'cds-snc-admin-js',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
        true,
    );

    $notifyListIds = [];
    try {
        $notifyListIds = NotifyTemplateSender::parseJsonOptions(
            get_option('list_values'),
        );
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    wp_localize_script('cds-snc-admin-js', 'CDS_VARS', [
        'rest_url' => esc_url_raw(rest_url()),
        'rest_nonce' => wp_create_nonce('wp_rest'),
        'notify_list_ids' => $notifyListIds,
    ]);
}

add_action('admin_enqueue_scripts', 'cds_admin_js');

$setupComponents = new Setup();
