<?php

declare(strict_types=1);

namespace CDS\Modules\Site;

class SettingsFunctions
{
    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_filter('body_class', [$this, 'addBodyClasses']);
    }

    public function addBodyClasses($classes)
    {
        $showWetMenu = get_option('show_wet_menu');

        if ($showWetMenu === 'off') {
            $classes[] = 'hide-wet-menu';
        }

        $showSearch = get_option('show_search');

        if ($showSearch === 'off') {
            $classes[] = 'hide-search-bar';
        }

        $showBreadcrumbs = get_option('show_breadcrumbs');

        if ($showBreadcrumbs === 'off') {
            $classes[] = 'hide-breadcrumbs';
        }

        return $classes;
    }
}
