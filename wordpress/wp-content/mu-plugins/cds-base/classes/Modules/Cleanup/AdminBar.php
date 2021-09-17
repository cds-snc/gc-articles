<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class AdminBar
{
    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'removeFromAdminBar'], 2147483647);

        add_action('wp_before_admin_bar_render', [$this, 'removeFromAdminBarBefore'], 99);
    }

    public function removeFromAdminBarBefore(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('WPML_ALS');
    }

    public function removeFromAdminBar($wp_admin_bar): void
    {
        if (super_admin()) {
            return;
        }

        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_node('site-name');
        $wp_admin_bar->remove_node('customize');

        /* plugins */
        $wp_admin_bar->remove_menu('wp-mail-smtp-menu');
        $wp_admin_bar->remove_menu('wpseo-menu');

        /* remove "Howdy" from admin bar */
        $my_account = $wp_admin_bar->get_node('my-account');
        $newtext    = str_replace('Howdy,', '', $my_account->title);
        $wp_admin_bar->add_node([
            'id'    => 'my-account',
            'title' => $newtext,
        ]);

        $wp_admin_bar->add_node([
            'id'    => 'cds-home',
            'title' => '<div class="ab-item"><span class="ab-icon"></span>'.__('Canadian Digital Service',
                    'cds-snc').'</div>',
            'href'  => "https://digital.canada.ca",
        ]);

        $wp_admin_bar->remove_menu('my-sites');
    }
}