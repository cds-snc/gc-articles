<?php

/**
 * Plugin Name: CDS-SNC Base
 * Plugin URI: https://github.com/cds-snc/platform-mvp
 * Description: Custom Block setup and other overrides
 * Version: 1.9.1
 * Author: Tim Arney
 *
 * @package cds-snc-base
 */

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Modules\Notify\NotifyTemplateSender;
use CDS\Setup;

defined('ABSPATH') || exit();

if (!defined('BASE_PLUGIN_NAME')) {
    define('BASE_PLUGIN_NAME', 'cds-base');
}

if (!defined('BASE_PLUGIN_NAME_VERSION')) {
    define('BASE_PLUGIN_NAME_VERSION', '1.9.1');
}

if (is_multisite()) {
    define('MU_PLUGIN_URL', network_site_url('/wp-content/mu-plugins', 'relative'));
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
        $notifyListIds = NotifyTemplateSender::parseJsonOptions(get_option('list_values'));
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    wp_localize_script("cds-snc-admin-js", "CDS_VARS", array(
        "rest_url" => esc_url_raw(rest_url()),
        "rest_nonce" => wp_create_nonce("wp_rest"),
        "notify_list_ids" => $notifyListIds
    ));
}

add_action('admin_enqueue_scripts', 'cds_admin_js');

$setupComponents = new Setup();

function setLoginLock()
{
    $unlock = true;
    $trackLogins = new TrackLogins();

    $users = get_users(array( 'fields' => array( 'id' ) ));
    $user_ids = array_map(
        function ($users) { return $users->id; },
        $users
    );

    foreach ($user_ids as $user_id) {
        $logins = $trackLogins->getUserLogins($user_id, $limit = 1);
        $login = $logins[0]?->time_login;

        // set to current time if they have never logged in 
        // (you can't be locked out before you log in)
        $login_time = $login ? strtotime($login) : time();
        $seconds = time() - $login_time;

        if ($unlock) {
            update_user_meta($user_id, '_is_disabled', false);
        } elseif ($seconds > 60) {
            update_user_meta($user_id, '_is_disabled', true);
        } else {
            update_user_meta($user_id, '_is_disabled', false);
        }
    }
}

function getLoginLock()
{
    $arr = array(
        [
            "user" => "admin",
            "_is_disabled" => get_user_meta(1, '_is_disabled', true)
        ],
        [
            "user" => "paul.craig+admin@cds-snc.ca",
            "_is_disabled" => get_user_meta(2, '_is_disabled', true)
        ],
        [
            "user" => "paul.craig+editor@cds-snc.ca",
            "_is_disabled" => get_user_meta(3, '_is_disabled', true)
        ],
        [
            "user" => "paul.craig+1@cds-snc.ca",
            "_is_disabled" => get_user_meta(4, '_is_disabled', true)
        ],
        [
            "user" => "paul.craig+2@cds-snc.ca",
            "_is_disabled" => get_user_meta(5, '_is_disabled', true)
        ],
        [
            "user" => "paul.craig+network@cds-snc.ca",
            "_is_disabled" => get_user_meta(7, '_is_disabled', true)
        ],
    );
    var_dump($arr);
}

// add_action('admin_head', 'setLoginLock');
// add_action('admin_head', 'getLoginLock');
