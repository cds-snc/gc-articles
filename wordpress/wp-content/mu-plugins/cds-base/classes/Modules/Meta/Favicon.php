<?php

declare(strict_types=1);

namespace CDS\Modules\Meta;

class Favicon
{
    public function __construct()
    {
        add_action('wp_head', [$this, 'addFavicon']);
    }

    public function addFavicon()
    {
        $favicon_url = plugin_dir_url(__FILE__) . 'img/favicon.ico';
        printf("<link href='%s' rel='icon' type='image/x-icon' />", $favicon_url);
    }
}
