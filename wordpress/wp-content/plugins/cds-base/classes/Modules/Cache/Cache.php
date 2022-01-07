<?php

declare(strict_types=1);

namespace CDS\Modules\Cache;

class Cache
{
    public static function register()
    {
        add_filter('c3_invalidation_items', function ($items, $post) {
            global $blog_id;
            $site = get_blog_details(array( 'blog_id' => $blog_id ));
            $sitePrefix = $site->path;

            return array_merge($items, [
                "{$sitePrefix}wp-json/wp/v2/{$post->post_type}s/?slug={$post->post_name}",
            ]);
        }, 10, 2);
    }
}
