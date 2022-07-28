<?php

/**
 * Plugin Name: CDS-SNC Base
 * Plugin URI: https://github.com/cds-snc/gc-articles
 * Description: Custom Block setup and other overrides
 * Version: 3.5.10
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
use CDS\Utils;
use Illuminate\Support\Str;

defined('ABSPATH') || exit();

if (!defined('BASE_PLUGIN_NAME')) {
    define('BASE_PLUGIN_NAME', 'cds-base');
}

if (!defined('BASE_PLUGIN_NAME_VERSION')) {
    define('BASE_PLUGIN_NAME_VERSION', '3.5.10');
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

function extractServiceIdFromApiKey($apiKey): string
{
    if (!$apiKey) {
        return $apiKey;
    }
    return substr($apiKey, -73, 36);
}

function getNotifyListIds()
{
    if (Utils::isWpEnv()) {
        return [];
    }

    // @TODO: Refactor out to GC Lists and consider caching this data
    try {
        if (get_option('NOTIFY_API_KEY')) {
            $url = LIST_MANAGER_ENDPOINT . '/lists/' . extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));

            $args = [
                'method'  => 'GET',
                'headers' => [
                    'Authorization' => DEFAULT_LIST_MANAGER_API_KEY,
                    'Content-Type'  => 'application/json'
                ],
            ];

            // Proxy request to list-manager
            $response      = json_decode(wp_remote_retrieve_body(wp_remote_request($url, $args)));
            $notifyListIds = array_map(function ($item) {
                return [
                    'label' => $item->name,
                    'id'    => $item->id,
                ];
            }, $response);

            return $notifyListIds;
        }

        return [];
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
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

    wp_localize_script('cds-snc-admin-js', 'CDS_VARS', [
        'rest_url' => esc_url_raw(rest_url()),
        'rest_nonce' => wp_create_nonce('wp_rest'),
        'notify_list_ids' => getNotifyListIds(),
    ]);
}

add_action('admin_enqueue_scripts', 'cds_admin_js');


if (! function_exists('wp_verify_nonce')) :
    /**
     * Modified for GC Articles to ensure the current user
     * is a member of the current blog
     *
     * Verifies that a correct security nonce was used with time limit.
     *
     * A nonce is valid for 24 hours (by default).
     *
     * @since 2.0.3
     *
     * @param string     $nonce  Nonce value that was used for verification, usually via a form field.
     * @param string|int $action Should give context to what is taking place and be the same when nonce was created.
     * @return int|false 1 if the nonce is valid and generated between 0-12 hours ago,
     *                   2 if the nonce is valid and generated between 12-24 hours ago.
     *                   False if the nonce is invalid.
     */
    function wp_verify_nonce($nonce, $action = -1)
    {
        /**
         * Fail nonce check on all these actions
         */
        if (
            Str::startsWith($action, 'WPML\TranslationRoles') ||
            Str::startsWith($action, 'WPML\TM\ATE\AutoTranslate') ||
            Str::startsWith($action, 'WPML\TM\ATE\TranslateEverything')
        ) {
            die('{ "success": false, "data": "403 Forbidden" }');
        }

        $nonce = (string) $nonce;
        $user  = wp_get_current_user();

        $uid   = (int) $user->ID;

        if ($uid && !is_super_admin()) {
            $bid = get_current_blog_id();

            if (!is_user_member_of_blog($uid, $bid)) {
                return false;
            }
        }

        if (! $uid) {
            /**
             * Filters whether the user who generated the nonce is logged out.
             *
             * @since 3.5.0
             *
             * @param int    $uid    ID of the nonce-owning user.
             * @param string $action The nonce action.
             */
            $uid = apply_filters('nonce_user_logged_out', $uid, $action);
        }

        if (empty($nonce)) {
            return false;
        }

        $token = wp_get_session_token();
        $i     = wp_nonce_tick();

        // Nonce generated 0-12 hours ago.
        $expected = substr(wp_hash($i . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
        if (hash_equals($expected, $nonce)) {
            return 1;
        }

        // Nonce generated 12-24 hours ago.
        $expected = substr(wp_hash(( $i - 1 ) . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
        if (hash_equals($expected, $nonce)) {
            return 2;
        }

        /**
         * Fires when nonce verification fails.
         *
         * @since 4.4.0
         *
         * @param string     $nonce  The invalid nonce.
         * @param string|int $action The nonce action.
         * @param WP_User    $user   The current user object.
         * @param string     $token  The user's session token.
         */
        do_action('wp_verify_nonce_failed', $nonce, $action, $user, $token);

        // Invalid nonce.
        return false;
    }
endif;

/**
 * Disable XMLRPC as authentication bypasses 2fa if configured.
 */
add_filter('xmlrpc_enabled', '__return_false');

$setupComponents = new Setup();
