<?php

declare(strict_types=1);

namespace CDS\Wpml;

use WP_Post;

class Post
{
    /**
     * Get available content by type and language
     *
     * @param  array  $args
     * @param  mixed  $language_code
     *
     * @return array
     */
    public function getAvailable(string $post_type, string $language_code): array
    {
        $args = array(
            'post_status' => 'any',
            'post_type' => $post_type
        );

        $posts = array_filter(get_posts($args), function ($post) use ($language_code) {
            global $sitepress;
            $postLanguage = $sitepress->get_language_for_element($post->ID, 'post_' . $post->post_type);

            return $postLanguage === $language_code;
        });

        return array_map(function ($post) use ($language_code) {
            return $this->buildResponseObject($post, $language_code);
        }, $posts);
    }

    public function getLanguageCodeOfPostObject(WP_Post $post): string
    {
        global $sitepress;
        return $sitepress->get_language_for_element($post->ID, 'post_' . $post->post_type);
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
