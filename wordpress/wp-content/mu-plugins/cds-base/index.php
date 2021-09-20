<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use CDS\Modules\Notify\NotifyTemplateSender;
use CDS\Modules\Setup;

/**
 * Plugin Name: CDS-SNC Base
 * Plugin URI: https://github.com/cds-snc/platform-mvp
 * Description: Custom Block setup and other overrides
 * Version: 1.2.0
 * Author: Tim Arney
 *
 * @package cds-snc-base
 */

defined('ABSPATH') || exit();

if ( ! defined('BASE_PLUGIN_NAME')) {
    define('BASE_PLUGIN_NAME', 'cds-base');
}

if ( ! defined('BASE_PLUGIN_NAME_VERSION')) {
    define('BASE_PLUGIN_NAME_VERSION', '1.2.0');
}

if (is_multisite()) {
    define('MU_PLUGIN_URL', network_site_url('/wp-content/mu-plugins', 'relative'));
} else {
    define('MU_PLUGIN_URL', content_url('/mu-plugins'));
}

function cds_plugin_images_url($filename)
{
    return plugin_dir_url(__FILE__).'images/'.$filename;
}


function cds_base_style_admin(): void
{

    // add stylesheet to the wp admin
    wp_enqueue_style(
        'cds-base-style-main',
        plugin_dir_url(__FILE__).'css/main.css',
        [],
        BASE_PLUGIN_NAME_VERSION,
    );
}

add_action('admin_enqueue_scripts', 'cds_base_style_admin');

/**
 * Load all translations for our plugin from the MO file.
 */
add_action('init', 'cds_textdomain');

function cds_textdomain(): void
{
    load_plugin_textdomain('cds-snc', false, basename(__DIR__).'/languages');
}

function cds_admin_js(): void
{
    // automatically load dependencies and version
    $asset_file = include plugin_dir_path(__FILE__).'build/index.asset.php';

    wp_enqueue_script(
        'cds-snc-admin-js',
        plugins_url('build/index.js', __FILE__),
        $asset_file['dependencies'],
        $asset_file['version'],
        true,
    );

    wp_localize_script("cds-snc-admin-js", "CDS_VARS", array(
            "rest_url"        => esc_url_raw(rest_url()),
            "rest_nonce"      => wp_create_nonce("wp_rest"),
            "notify_list_ids" => NotifyTemplateSender::parse_json_options(get_option('list_values'))
        )
    );
}

add_action('admin_enqueue_scripts', 'cds_admin_js');

$setupComponents = new Setup();