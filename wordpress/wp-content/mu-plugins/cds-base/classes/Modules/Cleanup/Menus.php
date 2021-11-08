<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Menus
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'removeMenuPages'], 2147483647);
        add_filter('wp_mail_smtp_admin_adminbarmenu_has_access', '__return_false');
    }

    public function removeMenuPages(): void
    {
        if (is_super_admin()) {
            return;
        }

        global $menu, $submenu;

        /* add items to keep here */
        $allowed = [
            __('Pages'),
            __('Posts'),
            __('Articles', 'cds'),
            __('Users'),
            __('Settings'),
        ];

        //  __('Settings'), __('Appearance')
        // http://localhost/wp-admin/options-reading.php
        end($menu);
        while (prev($menu)) {
            $value = explode(' ', $menu[key($menu)][0]);
            if (! in_array($value[0] !== null ? $value[0] : '', $allowed)) {
                unset($menu[key($menu)]);
            }
        }

        $this->hideWPMailSmtpMenus();
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
}
