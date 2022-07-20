<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

use WP_REST_Request;
use WP_Post;

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
        register_rest_route($this->namespace, '/(?P<type>pages|posts)/(?P<id>[\d]+)', [
            'methods' => 'POST',
            'callback' => [$this, 'saveTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        // Get a page's associated translation
        register_rest_route($this->namespace, '/(?P<type>pages|posts)/(?P<id>[\d]+)', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);
    }

    public function getLanguageCodeOfPostObject(WP_Post $post): string
    {
        $wpmlLanguageDetails = apply_filters('wpml_post_language_details', null, $post->ID);
        return $wpmlLanguageDetails['language_code'];
    }

    public function filterPostsByLanguage(array $posts, string $language_code): array
    {
        return array_filter($posts, function ($post) use ($language_code) {
            $postLanguage = $this->getLanguageCodeOfPostObject($post);

            return $postLanguage === $language_code;
        });
    }

    public function buildResponseObject(WP_Post $post, string $language_code): array
    {
        $tempPostObj = [];

        // post ID
        $tempPostObj['ID'] = $post->ID;

        // post_title
        $tempPostObj['post_title'] = $post->post_title;

        // post_type
        $tempPostObj['post_type'] = $post->post_type;

        // language_code
        $tempPostObj['language_code'] = $this->getLanguageCodeOfPostObject($post);

        // translated_post_id
        $altLanguage = $language_code === 'en' ? 'fr' : 'en';
        $translatedPostID = apply_filters('wpml_object_id', $post->ID, 'post', false, $altLanguage);
        $tempPostObj['translated_post_id'] = $translatedPostID;

        // is_translated
        $tempPostObj['is_translated'] = !is_null($translatedPostID);

        return $tempPostObj;
    }

    /**
     * Retrieve untranslated pages of the specified language.
     *
     * @param  WP_REST_Request  $request
     *
     * @return mixed
     */
    public function getAvailablePages(WP_REST_Request $request)
    {
        $response = [];

        $post_type = $request['type'] === 'pages' ? 'page' : 'post';
        $args = array(
            'post_type' => $post_type
        );
        $posts = $this->filterPostsByLanguage(get_posts($args), $request['language']);

        foreach ($posts as $post) {
            array_push($response, $this->buildResponseObject($post, $request['language']));
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
        // disassociate any existing translations
        // create new entry in translations table

        return [
            $request['language'],
            $request['type'],
        ];
    }

    /**
     * Get the existing translation for a given page.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array
     */
    public function getTranslation(WP_REST_Request $request)
    {
        return [
            $request['language'],
            $request['type'],
        ];
    }
}
