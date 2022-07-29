<?php

namespace CDS\Wpml;

use CDS\Wpml\Api\Endpoints;

class Wpml
{
    protected static $instance;

    protected Endpoints $endpoints;

    public static function getInstance(): Wpml
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

    public function setup()
    {
        $this->endpoints = Endpoints::getInstance();

        $this->addHooks();
    }

    public function addHooks()
    {
        add_action('rest_api_init', [$this->endpoints, 'registerRestRoutes']);

        add_action('enqueue_block_editor_assets', function () {
            wp_enqueue_script(
                'cds-wpml-mods',
                plugin_dir_url(__FILE__) . '../resources/js/build/sidebar.js',
                array( 'wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data' ),
                filemtime(plugin_dir_path(__FILE__) . '../resources/js/build/sidebar.js')
            );

            wp_localize_script('cds-wpml-mods', 'CDS_VARS', [
                'rest_url' => esc_url_raw(rest_url()),
            ]);

            wp_set_script_translations('cds-wpml-mods', 'cds-wpml-mods', CDS_WPML_PLUGIN_BASE_PATH . '/resources/languages/');
        });
    }
}
