<?php

declare(strict_types=1);

namespace CDS\Modules\Wpml;

class Wpml
{
    public static function register()
    {
        $instance = new self();

        add_action('wp_initialize_site', [$instance, 'onInit']);
    }

    public function onInit($newSite)
    {
        switch_to_blog( $newSite->id );

        // Installs WPML database tables for new site
        if (function_exists('icl_sitepress_activate')) {
            icl_sitepress_activate();
        }

        restore_current_blog();
    }
}