<?php

declare(strict_types=1);

namespace CDS\Modules\Cache;

class Cache
{
    public static function register()
    {
        $instance = new self();

        add_filter( 'c3_invalidation_items', function($items, $post) {
            return array_merge($items, [
                "/site-name/wp-json/wp-v2/" . $post->post_name
            ]);
        }, 10, 2);
    }
}