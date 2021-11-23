<?php

declare(strict_types=1);

namespace CDS\Modules\Meta;

class Favicon
{
    public string $favicon_url;

    public function __construct()
    {
        $this->favicon_url = plugin_dir_url(__FILE__) . 'img/favicon.ico';

        add_action('wp_head', [$this, 'addFavicon']);
        add_action('do_faviconico', [$this, 'removeDefaultFavicon']);
    }

    public function addFavicon()
    {
        printf("<link href='%s' rel='icon' type='image/x-icon' />", $this->favicon_url);
    }

    public function removeDefaultFavicon()
    {
        wp_redirect($this->favicon_url);
        exit;
    }
}
