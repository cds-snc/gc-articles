<?php

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Roles
{
    public function __construct()
    {
        if (isset($_SERVER) && isset($_SERVER['SERVER_PORT'])) {
            $port = $_SERVER['SERVER_PORT'];

            if ($port == 8888 || $port == 8889) {
                // ensure the administrator role exists for wp-env
                // wp-env uses (Username: admin, Password: password).
                // https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/#quick-tldr-instructions
                $this->cleanupRoles('administrator');
                return;
            }
        }

        Utils::checkOptionCallback('cds_base_activated', '1.0.7', function () {
            if (is_blog_installed()) {
                remove_role('administrator');
                remove_role('editor');
                remove_role('author');
                remove_role('contributor');
                remove_role('subscriber');
                remove_role('gceditor');
                remove_role('gcadmin');
                // the ircc role should be removed in the next version update (leaving to cleanup current db)
                remove_role('ircc');
                $this->cleanupRoles('gceditor');
                $this->cleanupRoles('gcadmin');
            }
        });
    }

    protected function cleanupRoles($role)
    {
        $default_roles = [
            'administrator' => [
                'switch_themes' => 1,
                'edit_themes' => 1,
                'activate_plugins' => 1,
                'edit_plugins' => 1,
                'edit_users' => 1,
                'edit_files' => 1,
                'manage_options' => 1,
                'moderate_comments' => 1,
                'manage_categories' => 1,
                'manage_links' => 1,
                'upload_files' => 1,
                'import' => 1,
                'unfiltered_html' => 1,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'edit_pages' => 1,
                'read' => 1,
                'level_10' => 1,
                'level_9' => 1,
                'level_8' => 1,
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
                'delete_users' => 1,
                'create_users' => 1,
                'unfiltered_upload' => 1,
                'edit_dashboard' => 1,
                'update_plugins' => 1,
                'delete_plugins' => 1,
                'install_plugins' => 1,
                'update_themes' => 1,
                'install_themes' => 1,
                'update_core' => 1,
                'list_users' => 1,
                'remove_users' => 1,
                'add_users' => 1,
                'promote_users' => 1,
                'edit_theme_options' => 1,
                'delete_themes' => 1,
                'export' => 1,
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
            ],
            'gcadmin' => [
                'edit_users' => 1,
                'list_users' => 1,
                'delete_users' => 1,
                'create_users' => 1,
                'remove_users' => 1,
                'add_users' => 1,
                'promote_users' => 1,
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
            ],
            'display_name' => [
                'administrator' => 'Administrator',
                'editor' => 'Editor',
                'author' => 'Author',
                'contributor' => 'Contributor',
                'subscriber' => 'Subscriber',
                'gceditor' => 'GC Editor',
                'gcadmin' => 'GC Admin'
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
}
