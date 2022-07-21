<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

use WP_REST_Request;
use WP_Post;
use WP_Error;

class Endpoints extends BaseEndpoint
{
    protected static $instance;

    public static function getInstance(): Endpoints
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

    public function hasPermission($pageId = null): bool
    {
        // if an optional page_id is provided, check that the user has permissions to it (ie same site)
        return true; // current_user_can('delete_posts');
    }

    public function registerRestRoutes()
    {
        // Get available pages by language
        register_rest_route($this->namespace, '/(?P<type>pages|posts)/(?P<language>en|fr)', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getAvailablePages'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        // Associate a page as a translation to a given page
        register_rest_route($this->namespace, 'posts/(?P<id>[\d]+)/translation', [
            'methods' => 'POST',
            'callback' => [$this, 'saveTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        // Get a page's associated translation
        register_rest_route($this->namespace, '/posts/(?P<id>[\d]+)/translation', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);
    }

    /**
     * Retrieve untranslated pages of the specified language.
     *
     * @param  WP_REST_Request  $request
     *
     * @return mixed
     */
    public function getAvailablePages(WP_REST_Request $request): array
    {
        $response = [];

        $post_type = $request['type'] === 'pages' ? 'page' : 'post';
        $args = array(
            'post_type' => $post_type
        );
        $posts = $this->formatResponse->filterPostsByLanguage(get_posts($args), $request['language']);

        foreach ($posts as $post) {
            array_push($response, $this->formatResponse->buildResponseObject($post, $request['language']));
        }

        return $response;
    }

    /**
     * Associate a page as a translation of a given page.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array
     */
    public function saveTranslation(WP_REST_Request $request)
    {
        // Assume the page and the other page already have records in the database.
        $response = [];

        $post_id = $request['id'];
        // No idea what to call this variable
        $other_id = $this->formatResponse->getPostIDFromRequestBody($request->get_body_params(), 'other_id');

        if ($other_id <= 1) {
            return new WP_Error('bad_other_id', __('Translation post ID invalid', 'cds-wp-mods'), array( 'status' => 400 ));
        }

        if ($post_id === $other_id) {
            return new WP_Error('same_post_id', __('Can’t assign a post’s translation to itself', 'cds-wp-mods'), array( 'status' => 400 ));
        }

        $post = get_post($post_id);
        $otherPost = get_post($other_id);

        if (is_null($post)) {
            return new WP_Error('post_not_found', __('The post you are looking for does not exist', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        if (is_null($otherPost)) {
            return new WP_Error('post_not_found', __('The post you want to set as the translation does not exist', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        if ($post->post_type !== $otherPost->post_type) {
            return new WP_Error('different_post_types', __('Posts are not the same post_type', 'cds-wp-mods'), array( 'status' => 400 ));
        }

        $language_code = $this->formatResponse->getLanguageCodeOfPostObject($post);
        $altLang = $this->formatResponse->getLanguageCodeOfPostObject($otherPost);

        if ($language_code === $altLang) {
            return new WP_Error('same_post_language', __('Can’t assign a post’s translation to another post in the same language', 'cds-wp-mods'), array( 'status' => 400 ));
        }

        // see if current post already has translation
        $translatedPostID = $this->formatResponse->getTranslatedPostID($post);

        if ($translatedPostID) {
            // reset trid of existing translation
            $args_translatedPost = array(
                'element_id'    => $translatedPostID,
                'element_type'  => 'post_' . $post->post_type,
                'trid'          => false,
                'language_code' => $altLang,
                'source_language_code' => null
            );

            do_action('wpml_set_element_language_details', $args_translatedPost);
        }

        // see if other post already has translation
        $otherTranslatedPostID = $this->formatResponse->getTranslatedPostID($otherPost);

        if ($otherTranslatedPostID) {
            // reset trid of existing translation
            $args_otherTranslatedPost = array(
                'element_id'    => $otherTranslatedPostID,
                'element_type'  => 'post_' . $otherPost->post_type,
                'trid'          => false,
                'language_code' => $language_code,
                'source_language_code' => null
            );

            do_action('wpml_set_element_language_details', $args_otherTranslatedPost);
        }

        // Note that post_trid is a string (normal ids are integers)
        $post_trid = apply_filters('wpml_element_trid', null, $post->ID, 'post_' . $post->post_type);

        // Set source_lang to null for original post
        $args_post = array(
            'element_id'    => $post->ID,
            'element_type'  => 'post_' . $post->post_type,
            'trid'          => $post_trid,
            'language_code' => $language_code,
            'source_language_code' => null
        );

        do_action('wpml_set_element_language_details', $args_post);

        // assign post trid to otherPost
        $args_otherPost = array(
            'element_id'    => $otherPost->ID,
            'element_type'  => 'post_' . $otherPost->post_type,
            'trid'          => $post_trid,
            'language_code' => $altLang,
            'source_language_code' => $language_code
        );

        do_action('wpml_set_element_language_details', $args_otherPost);

        return $this->formatResponse->buildResponseObject($post, $language_code);
    }

    /**
     * Get the existing translation for a given page.
     * Returns a WP_Error if no post is found for the id given, or if no translation is found.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array | WP_Error
     */
    public function getTranslation(WP_REST_Request $request)
    {
        $response = [];

        $post = get_post($request['id']);
        if (is_null($post)) {
            return new WP_Error('post_not_found', __('No post you are looking for does not exist', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        $translatedPostID = $this->formatResponse->getTranslatedPostID($post);
        if (is_null($translatedPostID)) {
            return new WP_Error('no_translation', __('No translation exists for this post.', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        $translatedPost = get_post($translatedPostID);

        return $this->formatResponse->buildResponseObject($translatedPost);
    }
}
