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
