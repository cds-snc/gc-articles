<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Menus
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'removeMenuPages'], 2147483647);
        add_filter('wp_mail_smtp_admin_adminbarmenu_has_access', '__return_false');

        // add "Menus" link for GC Admins and GC Editors
        add_action('admin_menu', [$this, 'addMenusLinkToAdmin'], 11);
        // BlockGC Admins and GC Editors from seeing "themes" or "customize" pages
        add_action('admin_init', [$this, 'blockAppearancePages']);
    }

    public function removeMenuPages(): void
    {
        if (is_super_admin()) {
            return;
        }

        global $menu;

        /* add items to keep here */
        $allowed = [
            // English menu items
            "Dashboard",
            "Articles",
            "Media",
            "Pages", // same in FR
            "Menus", // same in FR
            "Users",
            "Settings",
            "Bulk Send",
            "Lists",
            "Checklists",

            // French menu items
            "Tableau de bord",
            "Les Articles",
            "Média",
            "Utilisateurs",
            "Réglages",
            "Envoyer en masse",
        ];

        //  __('Settings'), __('Appearance')
        // http://localhost/wp-admin/options-reading.php
        end($menu);
        while (prev($menu)) {
            $value = $menu[key($menu)] ?? [];
            $label = $value[0] ?? '';
            $isSeparator = ($value[4] ?? null)  === "wp-menu-separator";

            // Unset menu items unless they are safelisted or separators
            if (!in_array($label, $allowed) && !$isSeparator) {
                unset($menu[key($menu)]);
            }
        }

        $this->hideWPMailSmtpMenus();
        $this->removeDashboardSubmenu();
        $this->removeSettingsPages();
    }

    public function blockAppearancePages(): void
    {
        if (is_super_admin() && !Utils::isWPEnvAdmin()) {
            return;
        }

        $appearanceSubPages = ["themes.php", "customize.php"];
        $currentUrlPath = $_SERVER['REQUEST_URI'];

        foreach ($appearanceSubPages as $subpage) {
            if (strpos($currentUrlPath, $subpage) !== false) { // If subpage URL appears in current URL path
                wp_die(
                    __("Sorry, you are not allowed to access this page."),
                    403 // status code
                );
            }
        }
    }

    public function addMenusLinkToAdmin(): void
    {
        // Super Admins can see the "Appearance" menu, so they don't need this.
        if (is_super_admin() && !Utils::isWPEnvAdmin()) {
            return;
        }

        $menusTitle = __('Menus');
        add_menu_page(
            $menusTitle,
            $menusTitle,
            'edit_theme_options',
            'nav-menus.php',
            '',
            'dashicons-editor-ul',
            60
        );
    }

    public function hideWPMailSmtpMenus(): void
    {
        if (is_super_admin()) {
            return;
        }

        //Hide "WP Mail SMTP".
        remove_menu_page('wp-mail-smtp');
        //Hide "WP Mail SMTP → Settings".
        remove_submenu_page('wp-mail-smtp', 'wp-mail-smtp');
        //Hide "WP Mail SMTP → Email Log".
        remove_submenu_page('wp-mail-smtp', 'wp-mail-smtp-logs');
        //Hide "WP Mail SMTP → Email Reports".
        remove_submenu_page('wp-mail-smtp', 'wp-mail-smtp-reports');
        //Hide "WP Mail SMTP → Tools".
        remove_submenu_page('wp-mail-smtp', 'wp-mail-smtp-tools');
        //Hide "WP Mail SMTP → About Us".
        remove_submenu_page('wp-mail-smtp', 'wp-mail-smtp-about');
    }

    public function removeDashboardSubmenu()
    {
        global $submenu;

        try {
            if ($submenu && isset($submenu["index.php"])) :
                $submenu["index.php"] = [];
            endif;
        } catch (Exception $e) {
            // no-op
        }
    }

    public function removeSettingsPages()
    {
        global $submenu;

        /* add items to keep here */
        $allowed = [
            __('Notify API Settings', 'cds-snc'),
            __('Site Settings', 'cds-snc'),
        ];

        try {
            if ($submenu && isset($submenu["options-general.php"])) :
                $options = $submenu["options-general.php"];
                foreach ($options as $key => $value) {
                    if (! in_array($value[0] !== null ? $value[0] : '', $allowed)) :
                        unset($submenu["options-general.php"][$key]);
                    endif;
                }
            endif;
        } catch (Exception $e) {
            // no-op
        }
    }
}
