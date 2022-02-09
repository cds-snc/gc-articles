<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class AdminBar
{
    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'removeFromAdminBar'], 2147483647);
        add_action('wp_before_admin_bar_render', [$this, 'removeFromAdminBarBefore'], 99);

        add_action('admin_bar_menu', [$this, 'addCollections'], 21);
        add_action('admin_bar_menu', [$this, 'addAdminToggle'], 21);

        add_action('admin_bar_menu', [$this, 'addLanguageSwitcher'], 21);
        add_action('admin_post_cds_change_lang', [$this, 'handleLanguageSwitcherResponse']);
    }

    public function addAdminToggle($wp_admin_bar): void
    {
        $menu_id = 'cds-home';
        // "is_admin" checks if viewing an admin page, it's not a role check
        $home_url = is_admin() ? home_url('/') : admin_url();

        $wp_admin_bar->add_node([
            'id'    => $menu_id,
            'title' => '<div class="ab-item"><span class="ab-icon"></span>' . __(
                'GC Articles',
                'cds-snc'
            ) . ' › ' . get_bloginfo('name') . '</div>',
            'href'  => $home_url,
        ]);

        // Add an option to visit the site.
        $wp_admin_bar->add_node(
            array(
                'parent' => $menu_id,
                'id'     => $menu_id . '-view-site',
                'title'  => __('Visit Site'),
                'href'   => home_url('/'),
            )
        );

        // Add an option to visit the site's "settings" page for superadmins
        if (is_multisite() && current_user_can('manage_sites')) {
            $wp_admin_bar->add_node(
                array(
                    'parent' => $menu_id,
                    'id'     => $menu_id . '-edit-site',
                    'title'  => __('Edit Site'),
                    'href'   => network_admin_url('site-info.php?id=' . get_current_blog_id()),
                )
            );
        }

        // Add an option to visit the dashboard.
        $wp_admin_bar->add_node(
            array(
                'parent' => $menu_id,
                'id'     => $menu_id . '-admin-dashboard',
                'title'  => __('Admin dashboard'),
                'href'   => admin_url(),
            )
        );
    }

    public function addLanguageSwitcher($wp_admin_bar): void
    {
        if (!is_admin()) {
            return;
        }

        $user_locale = get_user_locale();
        $translation_locale = str_contains($user_locale, "en") ? "fr_CA" : "en_CA";
        // Not using i18n function for these because we don't want to translate them
        $translation_locale_name = str_contains($user_locale, "en") ? "Français" : "English";

        ob_start();
        ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="cds_change_lang_form" >
            <input type="hidden" name="action" value="cds_change_lang">
            <?php wp_nonce_field('change_lang', "cds_change_lang_nonce"); ?>
            <input name="locale" type="hidden" value="<?php echo $translation_locale; ?>" /> 
            <input
                type="submit"
                name="submit"
                id="submit-change-lang"
                value="<?php echo $translation_locale_name; ?>"
                lang="<?php echo $translation_locale; ?>"
            />
        </form>
        <?php

        $cds_change_lang_form = ob_get_contents();
        ob_end_clean();

        $wp_admin_bar->add_node([
            'id'    => 'change-lang',
            'parent' => 'top-secondary',
            'title' => $cds_change_lang_form,
            'href'  => '',
        ]);
    }

    public function handleLanguageSwitcherResponse(): void
    {
        if (
            isset($_POST['cds_change_lang_nonce']) &&
            check_admin_referer('change_lang', 'cds_change_lang_nonce')
        ) {
            // sanitize the input
            $lang = sanitize_text_field($_POST['locale']);
            $user_id = get_current_user_id();
            wp_update_user(['ID' => $user_id, 'locale' => $lang]);
            wp_redirect(esc_url_raw($_POST['_wp_http_referer']));
            die();
        } else {
            wp_die(
                __('Invalid nonce', 'cds-snc'),
                __('Error', 'cds-snc'),
                ['response' => 403, 'back_link' => admin_url()]
            );
        }
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

        $user_blogs = get_blogs_of_user(get_current_user_id());

        /* Inspiration for this loop comes from 'wp_admin_bar_my_sites_menu' function in core WP */
        foreach ((array) $user_blogs as $blog) {
            if (has_site_icon()) {
                $blavatar = sprintf(
                    '<img class="blavatar" src="%s" srcset="%s 2x" alt="" width="16" height="16" />',
                    esc_url(get_site_icon_url(size: 16, blog_id: $blog->userblog_id)),
                    esc_url(get_site_icon_url(size: 32, blog_id: $blog->userblog_id))
                );
            } else {
                $blavatar = '<div class="blavatar"></div>';
            }

            $blogname = $blog->blogname;

            if (!$blogname) {
                $blogname = preg_replace('#^(https?://)?(www.)?#', '', get_home_url($blog->userblog_id));
            }

            $menu_id = 'site-' . $blog->userblog_id;
            $is_current = $blog->userblog_id === $current_site_id ? __(' (current)', 'cds-snc') : '';

            $wp_admin_bar->add_node(
                array(
                    'parent' => $root_id,
                    'id'     => $menu_id,
                    'title'  => $blavatar . $blogname . $is_current,
                    'href'   => get_admin_url(blog_id: $blog->userblog_id),
                )
            );

            $wp_admin_bar->add_node(
                array(
                    'parent' => $menu_id,
                    'id'     => $menu_id . '-d',
                    'title'  => __('Dashboard', 'cds-snc'),
                    'href'   => get_admin_url(blog_id: $blog->userblog_id),
                )
            );

            $wp_admin_bar->add_node(
                array(
                    'parent' => $menu_id,
                    'id'     => $menu_id . '-v',
                    'title'  => __('Visit', 'cds-snc'),
                    'href'   => get_home_url(blog_id: $blog->userblog_id, path: '/'),
                )
            );
        }
    }

    public function removeFromAdminBarBefore(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('WPML_ALS');
    }

    public function removeFromAdminBar($wp_admin_bar): void
    {
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('site-name');

        if (is_super_admin()) {
            return;
        }

        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('wp-logo');
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

        $wp_admin_bar->remove_menu('my-sites');
    }
}
