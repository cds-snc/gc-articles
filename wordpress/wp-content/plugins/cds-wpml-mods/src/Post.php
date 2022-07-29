<?php

declare(strict_types=1);

namespace CDS\Wpml;

use WP_Post;

class Post
{
    protected static $instance;

    public static function getInstance(): Post
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

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

        return array_map(function ($post) {
            return $this->buildResponseObject($post, withTranslations: true);
        }, $posts, array_keys($posts));
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

        global $sitepress;
        return $sitepress->get_object_id($post->ID, 'post', false, $altLanguage);
    }

    /**
     * @param $postId
     * @param $postType
     * @param $languageCode
     * @param  false  $trid
     * @param  null  $sourceLanguageCode
     */
    public function setTranslationForPost($postId, $postType, $languageCode, $trid = false, $sourceLanguageCode = null)
    {
        global $sitepress;

        $sitepress->set_element_language_details_action([
            'element_id'    => $postId,
            'element_type'  => 'post_' . $postType,
            'trid'          => $trid,
            'language_code' => $languageCode,
            'source_language_code' => $sourceLanguageCode
        ]);
    }

    public function buildResponseObject(WP_Post $post, bool $withTranslations = false): array
    {
        $tempPostObj = [];

        // post ID
        $tempPostObj['ID'] = $post->ID;

        // post_title
        $tempPostObj['post_title'] = $post->post_title;

        // post_type
        $tempPostObj['post_type'] = $post->post_type;

        // language_code
        $language_code = $this->getLanguageCodeOfPostObject($post);
        $tempPostObj['language_code'] = $language_code;

        if ($withTranslations) {
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
