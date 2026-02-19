<?php

/**
 * Plugin Name:     GC Post Meta
 * Plugin URI:      Adds sidebar post meta custom fields
 * Description:     Adds sidebar post meta custom fields
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     gc-post-meta
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         gc-post-meta
 */

define('GC_POST_META_PLUGIN_FILE_PATH', __FILE__);
define('GC_POST_META_PLUGIN_BASE_PATH', __DIR__);

/**
 * Helpers
 */

function get_asset_directory()
{
    return plugin_dir_path(__FILE__);
}

function get_asset_url()
{
    return plugin_dir_url(__FILE__);
}


/**
 * Actions
 */

 add_action('init', function () {

    // Fields

    register_meta(
        'post',
        'gc_author_name', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'gc_lever_id', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'gc_job_archived', // meta key
        array(
            'type'           => 'boolean',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'gc_team_member_archived', // meta key
        array(
            'type'           => 'boolean',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'gc_team_member_key_contact', // meta key
        array(
            'type'           => 'boolean',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );
 });

 add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'gc-post-meta',
        get_asset_url() . 'resources/js/build/sidebar.js',
        array( 'wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data' ),
        filemtime(get_asset_directory() . 'resources/js/build/sidebar.js')
    );

    wp_set_script_translations('gc-post-meta', 'gc-post-meta', GC_POST_META_PLUGIN_BASE_PATH . '/resources/languages/');
 });
