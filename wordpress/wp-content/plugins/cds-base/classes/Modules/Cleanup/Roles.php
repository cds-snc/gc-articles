<?php

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Roles
{
    public function __construct()
    {
        Utils::checkOptionCallback('cds_base_activated', '1.1.8', function () {
            if (is_blog_installed()) {
                $wp_roles = wp_roles();
                $allRoles = array_keys($wp_roles->roles); // array_keys returns only the slug

                // remove ALL roles, not just ones we know about
                foreach ($allRoles as $role) {
                    remove_role($role);
                }

                $this->cleanupRoles('gcwriter');
                $this->cleanupRoles('gceditor');
                $this->cleanupRoles('administrator');

                // Allow Collection administrators to add new users to their Collection via the "Users → Add New" page
                update_network_option(null, 'add_new_users', 1);
            }
        });

        add_action('wpseo_activate', [$this, 'removeSEORoles'], 99);

        // Add "unfiltered_html" role to GC Admins, GC Editors, GC Writers
        add_filter('map_meta_cap', [$this, 'addUnfilteredHTMLRole'], 1, 3);
        add_filter('map_meta_cap', [$this, 'forceExcludeCaps'], 2, 3);
        add_filter( 'rest_endpoints', [$this, 'disable_user_rest_endpoints'], 1 );
    }

    public function forceExcludeCaps($caps, $cap, $user_id)
    {
        if (!is_admin()) {
            return $caps;
        }

        if (is_multisite() && is_super_admin()) {
            return $caps;
        }

        global $pagenow;
        if ($pagenow === 'edit-comments.php' && $cap === 'edit_posts') {
            $caps = ['do_not_allow'];
        }

        if ($pagenow === 'tools.php') {
            $caps = ['do_not_allow'];
        }

        if (
            in_array($cap, [
                'delete_site',
                'import',
                'view_site_health_checks',
                'export_others_personal_data',
                'export',
                'erase_others_personal_data',
                'edit_themes',
                'install_themes',
                'update_core',
                'update_themes',
                'update_plugins',
            ])
        ) {
            $caps = ['do_not_allow'];
        }

        return $caps;
    }

    public function addUnfilteredHTMLRole($caps, $cap, $user_id)
    {
        if ('unfiltered_html' === $cap) {
            // Checking for "unfiltered_html" here causes an infinite loop
            if (is_super_admin() || user_can($user_id, "allow_unfiltered_html")) {
                $caps = array('unfiltered_html');
            }
        }
        return $caps;
    }

    public function removeSEORoles()
    {
        /* Installed by Yoast when plugin is activated */
        remove_role('wpseo_manager');
        remove_role('wpseo_editor');
    }

    protected function cleanupRoles($role)
    {
        $default_roles = [
            'administrator' => [
                'edit_users' => 1,
                'list_users' => 1,
                'delete_users' => 1,
                'create_users' => 1,
                'remove_users' => 1,
                'add_users' => 1,
                'promote_users' => 1,
                'manage_network_users' => 1, // enables "edit_users" for GC Admins'
                'manage_options' => 1,
                'manage_categories' => 1,
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'moderate_comments' => 0,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'edit_pages' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'upload_files' => 1,
                'edit_theme_options' => 1, // allows editing the "menu" options
                'unfiltered_html' => 0,
                'allow_unfiltered_html' => 0,
                'manage_notify' => 1,
                'manage_list_manager' => 0,
                'list_manager_bulk_send' => 0,
                'list_manager_bulk_send_sms' => 0,
            ],
            'editor' => [
                'moderate_comments' => 1,
                'manage_categories' => 1,
                'manage_links' => 1,
                'upload_files' => 1,
                'unfiltered_html' => 1,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'edit_pages' => 1,
                'read' => 1,
                'level_7' => 1,
                'level_6' => 1,
                'level_5' => 1,
                'level_4' => 1,
                'level_3' => 1,
                'level_2' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
            ],
            'author' => [
                'upload_files' => 1,
                'edit_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'read' => 1,
                'level_2' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'delete_posts' => 1,
                'delete_published_posts' => 1,
            ],
            'contributor' => [
                'edit_posts' => 1,
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'delete_posts' => 1,
            ],
            'subscriber' => [
                'read' => 1,
                'level_0' => 1,
            ],
            'gceditor' => [
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'moderate_comments' => 0,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'edit_pages' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'upload_files' => 1,
                'edit_theme_options' => 1, // allows editing the "menu" options
                'unfiltered_html' => 0,
                'allow_unfiltered_html' => 0,
                'manage_notify' => 0,
                'manage_list_manager' => 0,
                'list_manager_bulk_send' => 0,
                'list_manager_bulk_send_sms' => 0,
            ],
            'gcwriter' => [
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'moderate_comments' => 0,
                'edit_posts' => 1,
                'edit_others_posts' => 0,
                'edit_published_posts' => 0,
                'publish_posts' => 0,
                'delete_posts' => 0,
                'delete_others_posts' => 0,
                'delete_published_posts' => 0,
                'delete_private_posts' => 0,
                'edit_private_posts' => 0,
                'read_private_posts' => 0,
                'edit_pages' => 1,
                'delete_private_pages' => 0,
                'edit_private_pages' => 0,
                'read_private_pages' => 0,
                'edit_others_pages' => 1,
                'edit_published_pages' => 0,
                'publish_pages' => 0,
                'delete_pages' => 0,
                'delete_others_pages' => 0,
                'delete_published_pages' => 0,
                'upload_files' => 0,
                'edit_theme_options' => 0, // allows editing the "menu" options
                'unfiltered_html' => 0,
                'allow_unfiltered_html' => 0,
                'manage_notify' => 0,
                'manage_list_manager' => 0,
                'list_manager_bulk_send' => 0,
                'list_manager_bulk_send_sms' => 0,
            ],
            'display_name' => [
                'administrator' => 'GC Admin',
                'editor' => 'Editor',
                'author' => 'Author',
                'contributor' => 'Contributor',
                'subscriber' => 'Subscriber',
                'gceditor' => 'GC Editor',
                'gcwriter' => 'GC Writer',
            ],
        ];

        $role = strtolower($role);
        remove_role($role);

        return add_role(
            $role,
            $default_roles['display_name'][$role],
            $default_roles[$role],
        );
    }

    public function disable_user_rest_endpoints( $endpoints ) {
        $routes = array( '/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)' );
    
        foreach ( $routes as $route ) {
            if ( empty( $endpoints[ $route ] ) ) {
                continue;
            }
            
            // note this disables GET routes only
            foreach ( $endpoints[ $route ] as $i => $handlers ) {
                if ( is_array( $handlers ) && isset( $handlers['methods'] ) &&
                    'GET' === $handlers['methods'] ) {
                    unset( $endpoints[ $route ][ $i ] );
                }
            }
        }
    
        return $endpoints;
    }
}
