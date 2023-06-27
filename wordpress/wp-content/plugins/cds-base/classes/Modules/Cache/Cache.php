<?php

declare(strict_types=1);

namespace CDS\Modules\Cache;

class Cache
{
    const LOCALE_FR = 'fr_CA';

    public static function register()
    {
        $instance = new self();

        $instance->registerInvalidationPaths();
    }

    protected function registerInvalidationPaths()
    {
        // Invalidate the entire site's cache on change.  This is to deal with a bug
        // in the plugin that is failing to provide the correct paths for some
        // page update types.
        add_filter('c3_invalidation_items', function ($items, $post) {
            $sitePrefix = $this->getSitePrefixForPost();
            return array("{$sitePrefix}*");
        }, 10, 2);
    }

    protected function getSitePrefixForPost(): string
    {
        global $blog_id;
        $site = get_blog_details(array('blog_id' => $blog_id));

        return $site->path;
    }
}
