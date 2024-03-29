<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

use CDS\Wpml\Post;
use WP_REST_Request;
use WP_REST_Response;
use WP_Post;
use WP_Error;

class Endpoints extends BaseEndpoint
{
    protected static $instance;
    protected Post $post;

    public function __construct()
    {
        $this->post = Post::getInstance();

        parent::__construct();
    }

    public static function getInstance(): Endpoints
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

    public function hasPermission(): bool
    {
        return current_user_can('edit_posts');
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
            },
            'args' => [
                'translationId' => [
                    'required'          => true,
                    'type'              => 'number',
                    'description'       => 'ID of the translation',
                    'validate_callback' => function ($value, $request, $param) {
                        if (!is_numeric($value)) {
                            return new WP_Error('not_numeric', __('Translation post ID must be numeric', 'cds-wp-mods'));
                        }

                        if ($value < 1) {
                            return new WP_Error('invalid_id', __('Translation post ID invalid', 'cds-wp-mods'));
                        }

                        if ($value == $request['id']) {
                            return new WP_Error('same_ids', __('Translation post ID cannot be the same as original Post ID', 'cds-wp-mods'));
                        }

                        $originalPost = get_post(intval($request['id']));
                        if (is_null($originalPost)) {
                            return new WP_Error('post_not_found', __('The post you are looking for does not exist', 'cds-wp-mods'));
                        }

                        $targetPost = get_post($value);
                        if (is_null($targetPost)) {
                            return new WP_Error('post_not_found', __('The post you want to set as the translation does not exist', 'cds-wp-mods'));
                        }

                        if ($originalPost->post_type !== $targetPost->post_type) {
                            return new WP_Error('different_post_types', __('Posts are not the same post_type', 'cds-wp-mods'), array( 'status' => 400 ));
                        }

                        $languageCode = $this->post->getLanguageCodeOfPostObject($originalPost);
                        $altLang = $this->post->getLanguageCodeOfPostObject($targetPost);

                        if ($languageCode === $altLang) {
                            return new WP_Error('same_post_language', __('Can’t assign a post’s translation to another post in the same language', 'cds-wp-mods'), array( 'status' => 400 ));
                        }
                    }
                ],
            ],
        ]);

        // Get a page's associated translation
        register_rest_route($this->namespace, '/posts/(?P<id>[\d]+)/translation', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        // Unset a page's associated translation
        register_rest_route($this->namespace, '/posts/(?P<id>[\d]+)/translation', [
            'methods'             => 'DELETE',
            'callback'            => [$this, 'unsetTranslation'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);
    }

    /**
     * Retrieve available pages of the specified language.
     *
     * @param  WP_REST_Request  $request
     *
     * @return mixed
     */
    public function getAvailablePages(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $postType = $request['type'] === 'pages' ? 'page' : 'post';

        $language_code = $request['language'];

        try {
            $posts = $this->post->getAvailable($postType, $language_code);
        } catch (Exception $e) {
            return new WP_Error('internal_server_error', __('Internal server error', 'cds-wp-mods'), array( 'status' => 500 ));
        }

        $response = new WP_REST_Response($posts);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Associate a page as a translation of a given page.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array
     */
    public function saveTranslation(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        global $sitepress;

        $originalPost = get_post(intval($request['id']));
        $targetPost = get_post(intval($request['translationId']));

        try {
            $originalPostLanguage = $this->post->getLanguageCodeOfPostObject($originalPost);
            $targetPostLanguage = $this->post->getLanguageCodeOfPostObject($targetPost);
            $postType = 'post_' . $originalPost->post_type;

            // Unset existing translation if exists
            if ($translatedPostID = $this->post->getTranslatedPostID($originalPost)) {
                $this->post->setTranslationForPost($translatedPostID, $postType, $targetPostLanguage);
            }

            // Unset existing translation for the target post if exists
            if ($otherTranslatedPostID = $this->post->getTranslatedPostID($targetPost)) {
                $this->post->setTranslationForPost($otherTranslatedPostID, $postType, $originalPostLanguage);
            }

            // Note that post_trid is a string (normal ids are integers)
            $postTrid = $this->post->getTRID($originalPost->ID, $postType);

            // Set translations for each post
            $this->post->setTranslationForPost($originalPost->ID, $postType, $originalPostLanguage, $postTrid);
            $this->post->setTranslationForPost($targetPost->ID, $postType, $targetPostLanguage, $postTrid, $originalPostLanguage);
        } catch (Exception $e) {
            return new WP_Error('internal_server_error', __('Internal server error', 'cds-wp-mods'), array( 'status' => 500 ));
        }

        // Return the details
        $response = new WP_REST_Response($this->post->buildResponseObject(post: $originalPost, withTranslations: true));

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get the existing translation for a given page.
     * Returns a WP_Error if no post is found for the id given, or if no translation is found.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array | WP_Error
     */
    public function getTranslation(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $post = get_post(intval($request['id']));

        if (is_null($post)) {
            return new WP_Error('post_not_found', __('The post you are looking for does not exist', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        try {
            $response = new WP_REST_Response($this->post->buildResponseObject(post: $post, withTranslations: true));
        } catch (Exception $e) {
            return new WP_Error('internal_server_error', __('Internal server error', 'cds-wp-mods'), array( 'status' => 500 ));
        }

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get the existing translation for a given page.
     * Returns a WP_Error if no post is found for the id given.
     *
     * @param  WP_REST_Request  $request
     *
     * @return array | WP_Error
     */
    public function unsetTranslation(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $post = get_post(intval($request['id']));

        if (is_null($post)) {
            return new WP_Error('post_not_found', __('The post you are looking for does not exist', 'cds-wp-mods'), array( 'status' => 404 ));
        }

        try {
            $postType = 'post_' . $post->post_type;
            $language_code = $this->post->getLanguageCodeOfPostObject($post);

            // Unset existing translation if exists
            if ($translatedPostID = $this->post->getTranslatedPostID($post)) {
                $altLanguage = $language_code === 'en' ? 'fr' : 'en';
                $this->post->setTranslationForPost($translatedPostID, $postType, $altLanguage);
            }

            $this->post->setTranslationForPost($post->ID, $postType, $language_code);
        } catch (Exception $e) {
            return new WP_Error('internal_server_error', __('Internal server error', 'cds-wp-mods'), array( 'status' => 500 ));
        }

        $response = new WP_REST_Response($this->post->buildResponseObject(post: $post, withTranslations: true));

        $response->set_status(200);

        return rest_ensure_response($response);
    }
}
