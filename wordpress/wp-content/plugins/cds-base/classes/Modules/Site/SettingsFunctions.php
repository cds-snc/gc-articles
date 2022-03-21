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
        add_action('admin_head', [$this, 'dequeuePrimaryMenu']);

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

    public function dequeuePrimaryMenu()
    {
        $showWetMenu = get_option('show_wet_menu');
        $locations = get_nav_menu_locations();

        if (
            $showWetMenu === 'off' &&                   // if we _don't_ want to show the "wet menu"
            array_key_exists('header', $locations) &&   // if there _is_ a 'header' location
            is_int($locations['header'])                // if 'header' is assigned to an integer
        ) {
            $current_page_path = $_SERVER['REQUEST_URI'];

            // if we are explicitly setting a menu, turn on the "show canada.ca menu" option
            if (str_contains($current_page_path, "nav-menus.php")) {
                update_option('show_wet_menu', 'on');
            } else {
                // if not, remove 'header' menu
                $locations['header'] = null; // remove assigned menu
                set_theme_mod('nav_menu_locations', $locations); // save the new "locations" array
            }
        }
    }
}
