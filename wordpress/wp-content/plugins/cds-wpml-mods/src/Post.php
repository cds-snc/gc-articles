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
            'post_status'   => 'any',
            'post_type'     =>  $post_type,
            'numberposts'   =>  100,
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
        $language_code = $sitepress->get_language_for_element($post->ID, 'post_' . $post->post_type);
        return is_null($language_code) ? 'en' : $language_code;
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

    public function getTRID(int $postID, string $postType): string
    {
        global $sitepress;
        return $sitepress->get_element_trid($postID, $postType);
    }

    /**
     * @param int $postId  Use term_taxonomy_id for taxonomies, post_id for posts
     * @param string $postType The type of an element. Can be a post type: post_post, post_page, post_attachment, post_nav_menu_item, post_{custom post key} or taxonomy: tax_category, tax_post_tag, tax_nav_menu, tax_{custom taxonomy key}. Defaults to post_post if not set.
     * @param string $languageCode  The language code for the element
     * @param int  $trid   The trid to which the element is to be assigned to. If set to FALSE it will create a new trid for the element causing any potential translation relations to/from it to disappear.
     * @param null|string  $sourceLanguageCode  The source language code for the element. NULL is reserved for original elements i.e. elements that are not a translation of another. Defaults to NULL when not set.
     *
     * This function uses the sitepress method directly, but the args from the hook are all passed in.
     * Reference: https://wpml.org/wpml-hook/wpml_set_element_language_details/
     */
    public function setTranslationForPost($postID, $postType, $languageCode, $trid = false, $sourceLanguageCode = null)
    {
        global $sitepress;

        $elementType = str_starts_with($postType, 'post_') ? $postType : 'post_' . $postType;

        $sitepress->set_element_language_details_action([
            'element_id'    => $postID,
            'element_type'  => $elementType,
            'trid'          => $trid,
            'language_code' => $languageCode,
            'source_language_code' => $sourceLanguageCode
        ]);

        if (get_post_meta($postID, 'wpml_trid', true)) {
            // get the trid for the current post
            $trid = $trid ?: $this->getTRID($postID, $elementType);

            // update the "wpml_trid" post_meta field if it exists
            update_post_meta($postID, 'wpml_trid', $trid);
        }
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
