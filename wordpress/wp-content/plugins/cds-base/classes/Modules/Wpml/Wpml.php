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

    private static function getTranslatedPost($post_id, $lang)
    {
        return apply_filters('wpml_object_id', $post_id, 'post', false, $lang);
    }

    private static function addTranslatedIDsToPages()
    {
        add_action('rest_api_init', function () {
            /**
             * Add an 'slug_en' field field to the REST response for a page
             * Returns a string, or 'null' if no translation provided
             */
            register_rest_field('page', 'slug_en', array(
                'get_callback' => function ($post, $field_name, $request) {
                    $translatedPostId = self::getTranslatedPost($post['id'], 'en');
                    return is_null($translatedPostId) ? null : get_post_field('post_name', $translatedPostId);
                },
                'update_callback' => null,
                'schema' => array(
                    'description' => __('Post slug for English page.', 'cds-snc'),
                    'type'        => 'string'
                ),
            ));

            /**
             * Add an 'slug_fr' field field to the REST response for a page
             * Returns an string, or 'null' if no translation provided
             */
            register_rest_field('page', 'slug_fr', array(
                'get_callback' => function ($post, $field_name, $request) {
                    $translatedPostId = self::getTranslatedPost($post['id'], 'fr');
                    return is_null($translatedPostId) ? null : get_post_field('post_name', $translatedPostId);
                },
                'update_callback' => null,
                'schema' => array(
                    'description' => __('Post slug for French page.', 'cds-snc'),
                    'type'        => 'string'
                ),
            ));


            /**
             * Add an 'id_en' field field to the REST response for a page
             * Returns an integer id, or 'null' if no translation provided
             */
            register_rest_field('page', 'id_en', array(
                'get_callback' => function ($post, $field_name, $request) {
                    return self::getTranslatedPost($post['id'], 'en');
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
                    return self::getTranslatedPost($post['id'], 'fr');
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
