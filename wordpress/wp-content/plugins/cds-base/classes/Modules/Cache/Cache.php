<?php

declare(strict_types=1);

namespace CDS\Modules\Cache;

class Cache
{
    const locale_fr = 'fr_FR';

    public static function register()
    {
        $instance = new self();

        $instance->registerInvalidationPaths();
    }

    protected function registerInvalidationPaths()
    {
        add_filter('c3_invalidation_items', function ($items, $post) {
            global $blog_id;
            $site       = get_blog_details(array('blog_id' => $blog_id));
            $sitePrefix = $site->path;

            $localePrefix = $this->getLocalePrefixForPost($post);

            return array_merge($items, [
                "{$sitePrefix}{$localePrefix}wp-json/wp/v2/{$post->post_type}s/?slug={$post->post_name}",
            ]);
        }, 10, 2);
    }

    protected function getLocalePrefixForPost($post): string
    {
        if($language_information = apply_filters('wpml_post_language_details', null, $post->ID)) {
            $locale = $language_information['locale'];

            if ($locale === self::locale_fr) {
                return 'fr/';
            }
        }

        return '';
    }
}
