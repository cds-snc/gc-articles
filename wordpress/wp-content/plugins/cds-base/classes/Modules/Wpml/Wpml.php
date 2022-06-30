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
        add_action('save_post', [$instance, 'saveTridToPostMeta'], 10, 2);
        add_filter('wpml_save_post_trid_value', [$instance, 'retrieveTridFromPostMeta'], 10, 2);
    }

    /**
     * @param $post_ID
     * @param $post
     *
     * On a new translation, grab TRID from $_GET and attach directly to the new Post meta
     * on first save, usuallly an auto-draft save triggered by wp on load of post edit.
     *
     * This is due to an issue in WPML where trid was not making it through to
     * the icl_translations table.
     */
    public function saveTridToPostMeta($post_ID, $post)
    {
        if (isset($_GET['trid'])) {
            $trid = intval($_GET['trid']);
            add_post_meta($post_ID, 'wpml_trid', $trid);
        }
    }

    /**
     * @param $trid
     * @param $post_status
     *
     * @return mixed
     *
     * This hook is called from wpml-admin-post-actions.class.php->get_save_post_trid. It attempts to retrieve
     * the TRID from GET/POST or Referer. For some reason it has been failing and not retrieving the id.
     *
     * In combination with the method above, we are now saving the TRID to the translated post meta and
     * retrieving it here and forcing it at the hook.
     */
    public function retrieveTridFromPostMeta($trid, $post_status)
    {
        global $post;
        if (isset($post) && isset($post->ID)) {
            $trid = get_post_meta($post->ID, 'wpml_trid', true);
        }

        return $trid;
    }

    public function getTranslatedPost($post_id, $lang)
    {
        return apply_filters('wpml_object_id', $post_id, 'post', false, $lang);
    }

    public function addTranslatedIDsToPages()
    {
        /**
         * Add an 'slug_en' field to the REST response for a page
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
         * Add an 'slug_fr' field to the REST response for a page
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
         * Add an 'id_en' field to the REST response for a page
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
         * Add a 'lang' field to the REST response for a page
         * Returns a string locale, or 'null' if no language has been assigned
         */
        register_rest_field('page', 'lang', array(
            'get_callback' => function ($post, $field_name, $request) {
                $locale_array = apply_filters('wpml_post_language_details', null, $post['id']);
                return $locale_array['language_code'] ?? null;
            },
            'update_callback' => null,
            'schema' => array(
                'description' => __('Language of page', 'cds-snc'),
                'type'        => 'string'
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

    public function wpmlTranslatePost()
    {
        try {
            $post_id = intval($_POST['post_id']);
            $post_type = "page";

            // Include WPML API
            include_once(WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php');

            // Define title & content of translated post
            $post_translated_title = sanitize_text_field($_POST['translated_title']);
            $post_translated_content = sanitize_text_field($_POST['translated_content']);

            // Insert translated post
            $post_translated_id = wp_insert_post(
                array(
                    'post_title' => $post_translated_title,
                    'post_content' => $post_translated_content,
                    'post_type' => $post_type,
                    "post_status" => "publish"
                )
            );
            // Get trid of original post
            $trid = wpml_get_content_trid('post_' . $post_type, $post_id);

            // Associate original post and translated post
            global $wpdb;
            $wpdb->update(
                $wpdb->prefix . 'icl_translations',
                array( 'trid' => $trid, 'element_type' => "post_" . $post_type, 'language_code' => "fr", 'source_language_code' => "en" ),
                array( 'element_id' => $post_translated_id, 'element_type' => 'post_page')
            );

            // Return translated post ID
            return [ "post_id" => $post_id, "post_translated_id" =>  $post_translated_id , "trid" =>  $trid];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
