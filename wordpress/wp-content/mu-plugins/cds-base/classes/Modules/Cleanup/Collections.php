<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Collections
{
    public function __construct()
    {
        add_filter('gettext', [$this, 'sitesToCollections']);
    }

    public function sitesToCollections($text): string
    {
        $text = str_ireplace('Sites', 'Collections', $text);
        $text = str_ireplace('Site', 'Collection', $text);
        return $text;
    }
}
