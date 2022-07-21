<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

use WP_Post;

class FormatResponse
{
    public function __construct()
    {
    }

    public function getPostIDFromRequestBody(array $request_body, string $key): int
    {
        $id = -1;

        if (isset($request_body[$key]) && is_numeric($request_body[$key])) {
            $id = intval($request_body[$key]); // can be zero or 1
        }

        return $id;
    }

    public function getLanguageCodeOfPostObject(WP_Post $post): string
    {
        $wpmlLanguageDetails = apply_filters('wpml_post_language_details', null, $post->ID);
        return $wpmlLanguageDetails['language_code'];
    }

    public function getTranslatedPostID(WP_Post $post, ?string $altLanguage = null): int|null
    {
        if (is_null($altLanguage)) {
            $language_code = $this->getLanguageCodeOfPostObject($post);
            $altLanguage = $language_code === 'en' ? 'fr' : 'en';
        }
        // translated_post_id
        return apply_filters('wpml_object_id', $post->ID, 'post', false, $altLanguage);
    }

    public function filterPostsByLanguage(array $posts, string $language_code): array
    {
        return array_filter($posts, function ($post) use ($language_code) {
            $postLanguage = $this->getLanguageCodeOfPostObject($post);

            return $postLanguage === $language_code;
        });
    }

    public function buildResponseObject(WP_Post $post, ?string $language_code = null): array
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

        if (!is_null($language_code)) {
            // translated_post_id
            $altLanguage = $language_code === 'en' ? 'fr' : 'en';
            $translatedPostID = $this->getTranslatedPostID($post, $altLanguage);
            $tempPostObj['translated_post_id'] = $translatedPostID;

            // is_translated
            $tempPostObj['is_translated'] = !is_null($translatedPostID);
        }


        return $tempPostObj;
    }
}
