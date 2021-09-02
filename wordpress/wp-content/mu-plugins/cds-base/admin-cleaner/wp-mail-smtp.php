<?php

declare(strict_types=1);

function hide_wp_mail_smtp_menus(): void
{
    if (super_admin()) {
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

add_filter('wp_mail_smtp_admin_adminbarmenu_has_access', '__return_false');
