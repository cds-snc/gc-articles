<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class AdminBar
{
    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'removeFromAdminBar'], 2147483647);
        add_action('wp_before_admin_bar_render', [$this, 'removeFromAdminBarBefore'], 99);

        add_action( 'admin_bar_menu', [$this, 'addCollections'], 21 );
    }

    public function addCollections($wp_admin_bar): void
    {
        // if less than 2 collections or a superadmin, skip this
        if (count($wp_admin_bar->user->blogs) < 2 || is_super_admin()) {
            return;
        }

        $root_id = 'my-collections';
        $wp_admin_bar->add_node([
            'id'    =>  $root_id,
            'title' => '<div class="ab-item"><span class="ab-icon"></span>' . __(
                'My Collections',
                'cds-snc'
            ) . '</div>'
        ]);

        $current_site_id = get_current_blog_id();

        /* Inspiration for this loop comes from 'wp_admin_bar_my_sites_menu' function in core WP */
        foreach ((array) $wp_admin_bar->user->blogs as $blog) {
            switch_to_blog($blog->userblog_id);

            if (has_site_icon()) {
                $blavatar = sprintf(
                    '<img class="blavatar" src="%s" srcset="%s 2x" alt="" width="16" height="16" />',
                    esc_url(get_site_icon_url(16)),
                    esc_url(get_site_icon_url(32))
                );
            } else {
                $blavatar = '<div class="blavatar"></div>';
            }

            $blogname = $blog->blogname;

            if (!$blogname) {
                $blogname = preg_replace('#^(https?://)?(www.)?#', '', get_home_url());
            }

            $menu_id = 'site-' . $blog->userblog_id;
            $is_current = $blog->userblog_id === $current_site_id ? __(' (current)', 'cds-snc') : '';

            $wp_admin_bar->add_node(
                array(
                    'parent' => $root_id,
                    'id'     => $menu_id,
                    'title'  => $blavatar . $blogname . $is_current,
                    'href'   => admin_url(),
                )
            );

            $wp_admin_bar->add_node(
                array(
                    'parent' => $menu_id,
                    'id'     => $menu_id . '-d',
                    'title'  => __('Dashboard', 'cds-snc'),
                    'href'   => admin_url(),
                )
            );

            $wp_admin_bar->add_node(
                array(
                    'parent' => $menu_id,
                    'id'     => $menu_id . '-v',
                    'title'  => __('Visit', 'cds-snc'),
                    'href'   => home_url('/'),
                )
            );
        }
        restore_current_blog();
    }

    public function removeFromAdminBarBefore(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('WPML_ALS');
    }

    public function removeFromAdminBar($wp_admin_bar): void
    {
        $wp_admin_bar->remove_node('new-content');

        if (is_super_admin()) {
            return;
        }

        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
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
            'title' => '<div class="ab-item"><span class="ab-icon"></span>' . __(
                'GC Articles',
                'cds-snc'
            ) . '</div>',
            'href'  => admin_url(),
        ]);

        $wp_admin_bar->remove_menu('my-sites');
    }
}
