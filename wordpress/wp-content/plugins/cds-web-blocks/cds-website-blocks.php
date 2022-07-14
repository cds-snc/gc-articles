<?php

/**
 * Plugin Name:     CDS Website Blocks
 * Plugin URI:      Adds custom blocks
 * Description:     Adds custom blocks
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     cds-web
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         cds-web
 */

define('CDS_WEBSITE_BLOCKS_PLUGIN_FILE_PATH', __FILE__);
define('CDS_WEBSITE_BLOCKS_PLUGIN_BASE_PATH', __DIR__);

class Blocks
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        add_action('init', [$this, 'registerBlocks']);
    }

    public function registerBlocks()
    {
        register_block_type(__DIR__ . '/blocks/build/team/');
    }
}

/**
 * Helpers
 */

function get_cds_web_asset_directory()
{
    return plugin_dir_path(__FILE__);
}

function get_cds_web_asset_url()
{
    return plugin_dir_url(__FILE__);
}

// register custom meta tag field
function cds_web_register_post_meta()
{
    register_meta(
        'post',
        'cds_web_team_member_name', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_title_en', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_title_fr', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_email', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_twitter', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_github', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );

    register_meta(
        'post',
        'cds_web_team_member_linkedin', // meta key
        array(
            'type'           => 'string',
            'single'         => true,
            'show_in_rest'   => true,
        )
    );
}
add_action('init', 'cds_web_register_post_meta');

$blocks = new Blocks();
