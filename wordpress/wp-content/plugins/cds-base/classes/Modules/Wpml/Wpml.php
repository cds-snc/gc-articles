<?php

declare(strict_types=1);

namespace CDS\Modules\Wpml;

class Wpml
{
    public static function register()
    {
        Installer::register();
        Cleanup::register();

        self::addTranslatedIDsToPages();
    }

    private static function addTranslatedIDsToPages()
    {
        add_action('rest_api_init', function () {

            /**
             * Add an 'id_en' field field to the REST response for a page
             * Returns an integer id, or 'null' if no translation provided
             */
            register_rest_field('page', 'id_en', array(
                'get_callback' => function ($post, $field_name, $request) {
                    return apply_filters('wpml_object_id', $post['id'], 'post', false, 'en');
                },
                'update_callback' => null,
                'schema' => array(
                    'description' => __('ID for English page.', 'cds-snc'),
                    'type'        => 'integer'
                ),
            ));

            /**
             * Add an 'id_fr' field to the REST response for a page
             * Returns an integer id, or 'null' if no translation provided
             */
            register_rest_field('page', 'id_fr', array(
                'get_callback' => function ($post, $field_name, $request) {
                    return apply_filters('wpml_object_id', $post['id'], 'post', false, 'fr');
                },
                'update_callback' => null,
                'schema' => array(
                    'description' => __('ID for French page.', 'cds-snc'),
                    'type'        => 'integer'
                ),
            ));
        });
    }
}
