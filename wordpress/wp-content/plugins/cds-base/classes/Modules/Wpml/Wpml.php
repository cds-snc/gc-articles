<?php

declare(strict_types=1);

namespace CDS\Modules\Wpml;

class Wpml
{
    public static function register()
    {
        Installer::register();
        Cleanup::register();

        $instance = new self();

        add_action('rest_api_init', [$instance, 'addTranslatedIDsToPages']);
    }

    protected function getTranslatedPost($post_id, $lang)
    {
        return apply_filters('wpml_object_id', $post_id, 'post', false, $lang);
    }

    protected function addTranslatedIDsToPages()
    {
        /**
         * Add an 'slug_en' field field to the REST response for a page
         * Returns a string, or 'null' if no translation provided
         */
        register_rest_field('page', 'slug_en', array(
            'get_callback' => function ($post, $field_name, $request) {
                $translatedPostId = $this->getTranslatedPost($post['id'], 'en');
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
                $translatedPostId = $this->getTranslatedPost($post['id'], 'fr');
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
                return $this->getTranslatedPost($post['id'], 'en');
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
                return $this->getTranslatedPost($post['id'], 'fr');
            },
            'update_callback' => null,
            'schema' => array(
                'description' => __('ID for French page.', 'cds-snc'),
                'type'        => 'integer'
            ),
        ));

        /**
         * Adds a new endpoint to handle creating a linked page or post
        */

        register_rest_route('cds-wpml/v1', '/translate', [
            'methods'             => 'POST',
            'callback'            => [$this, 'wpmlTranslatePost'],
            'permission_callback' => function () {
                return current_user_can('delete_posts');
            }
        ]);
    }

    protected function wpmlTranslatePost()
    {
        try {
            $post_id = intval($_POST['post_id']);
            $post_type = "page";

            // Include WPML API
            include_once(WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php');

            // Define title of translated post
            $post_translated_title = get_post($post_id)->post_title . '(fr)';

            // Insert translated post
            $post_translated_id = wp_insert_post(array( 'post_title' => $post_translated_title, 'post_type' => $post_type, "post_status" => "publish" ));

            // Get trid of original post
            $trid = wpml_get_content_trid('post_' . $post_type, $post_id);

            // Associate original post and translated post
            global $wpdb;
            $wpdb->update(
                $wpdb->prefix . 'icl_translations',
                array( 'trid' => $trid, 'element_type' => "post_" . $post_type, 'language_code' => "fr", 'source_language_code' => "en" ),
                array( 'element_id' => $post_translated_id )
            );

            // Return translated post ID
            return [ "post_id" => $post_id, "post_translated_id" =>  $post_translated_id , "trid" =>  $trid];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
