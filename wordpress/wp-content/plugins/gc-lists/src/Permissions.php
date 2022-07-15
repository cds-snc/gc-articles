<?php

declare(strict_types=1);

namespace GCLists;

use WP_User;

use function get_sites;

class Permissions
{
    protected static $instance;

    public static function getInstance(): Permissions
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    /**
     * Setup default Role capabilities
     *
     * Note: this overrides what's in cds-base\classes\Modules\Cleanup\Roles.php
     * which should be refactored and parts of it removed.
     */
    public function cleanupCustomCapsForRoles()
    {
        if ($role = get_role('administrator')) {
            $role->add_cap('manage_notify', false);
            $role->add_cap('manage_list_manager', false);
            $role->add_cap('list_manager_bulk_send', false);
            $role->add_cap('list_manager_bulk_send_sms', false);
        }

        if ($role = get_role('gceditor')) {
            $role->add_cap('manage_notify', false);
            $role->add_cap('manage_list_manager', false);
            $role->add_cap('list_manager_bulk_send', false);
            $role->add_cap('list_manager_bulk_send_sms', false);
        }

        if ($role = get_role('gcwriter')) {
            $role->add_cap('manage_notify', false);
            $role->add_cap('manage_list_manager', false);
            $role->add_cap('list_manager_bulk_send', false);
            $role->add_cap('list_manager_bulk_send_sms', false);
        }

        add_option('gc-lists_roles_cleanup', true);
    }

    /**
     * Setup default capabilities for users (migrate existing)
     *
     * This should just run once to set existing users default capabilities since we've removed
     * the defaults from our custom Roles to allow for more flexibility.
     *
     * @TODO: This code can be removed after it has executed
     *
     * @codeCoverageIgnore
     */
    public function cleanupCustomCapsForUsers()
    {
        if (is_multisite()) {
            try {
                $sites = get_sites();

                foreach ($sites as $site) {
                    switch_to_blog($site->blog_id);
                    $users = get_users();

                    foreach ($users as $user) {
                        if (! is_super_admin($user->id)) {
                            if (in_array('administrator', $user->roles)) {
                                $user->add_cap('manage_notify', true);
                                $user->add_cap('manage_list_manager', true);
                                $user->add_cap('list_manager_bulk_send', true);
                            }

                            if (in_array('gceditor', $user->roles)) {
                                $user->add_cap('manage_list_manager', true);
                                $user->add_cap('list_manager_bulk_send', true);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                error_log("[GC-LISTS] There was a problem migrating caps for User " .
                          ($user ? $user->id : '?') . " in Site " .
                          ($site ? $site->blog_id : '?') . " : " . $e->getMessage());
            }
        }
    }

    /**
     * Add default capabilities to a User according to their Role.
     *
     * When assigning a Role to a user, grant relevant capabilities to the User based on their assigned Role.
     * Doing it this way vs having these caps on the Roles themselves, gives us more flexibility when
     * fine-tuning these caps per user.
     *
     * @param $user_id
     * @param $role
     */
    public function addDefaultUserCapsForRole($user_id, $role)
    {
        $user = get_user_by('ID', $user_id);

        $user->remove_cap('manage_notify');
        $user->remove_cap('manage_list_manager');
        $user->remove_cap('list_manager_bulk_send');
        $user->remove_cap('list_manager_bulk_send_sms');

        if ($role === 'administrator') {
            $user->add_cap('manage_notify', true);
            $user->add_cap('manage_list_manager', true);
            $user->add_cap('list_manager_bulk_send', true);
        }

        if ($role === 'gceditor') {
            $user->add_cap('manage_list_manager', true);
            $user->add_cap('list_manager_bulk_send', true);
        }

        if ($role === 'gcwriter') {
            //
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function displayListManagerMeta($user)
    {
        if (!is_super_admin()) {
            return;
        }

        printf('<h3>%s</h3>', __('GC Lists', 'cds-snc'));
        print "<table class='form-table'>";

        /* MANAGE LISTS */
        print '<tr>';
        $manage_list_manager = $user->has_cap('manage_list_manager');

        if ($manage_list_manager) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        printf(
            "<th><label for='manage_list_manager'>%s</label></th>",
            __('Manage Lists', 'cds-snc'),
        );
        printf(
            "<td><input value='true' type='checkbox' name='manage_list_manager' id='manage_list_manager' %s /> %s</td>",
            $checked,
            __('Can manage lists', 'cds-snc'),
        );

        print '</tr>';

        /* BULK EMAIL SEND */
        print '<tr>';
        $list_manager_bulk_send = $user->has_cap('list_manager_bulk_send');

        if ($list_manager_bulk_send) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        printf(
            "<th><label for='list_manager_bulk_send'>%s</label></th>",
            __('Bulk Send', 'cds-snc'),
        );
        printf(
            "<td><input value='true' type='checkbox' name='list_manager_bulk_send' id='list_manager_bulk_send' %s /> %s</td>",
            $checked,
            __('Allow bulk send', 'cds-snc'),
        );
        print '</tr>';

        /* BULK SMS SEND */
        $list_manager_bulk_send_sms = $user->has_cap('list_manager_bulk_send_sms');

        if ($list_manager_bulk_send_sms) {
            $sms_checked = 'checked';
        } else {
            $sms_checked = '';
        }

        print '<tr>';
        printf(
            "<th><label for='list_manager_bulk_send_sms'>%s</label></th>",
            __('Bulk Send SMS', 'cds-snc'),
        );
        printf(
            "<td><input value='true' type='checkbox' name='list_manager_bulk_send_sms' id='list_manager_bulk_send_sms' %s /> %s</td>",
            $sms_checked,
            __('Allow bulk send SMS', 'cds-snc'),
        );

        print '</tr>';
        print '</table>';
    }

    /**
     * @codeCoverageIgnore
     */
    public function updateListManagerMeta($userId)
    {
        if (!is_super_admin()) {
            return;
        }

        $user = new WP_User($userId);
        $user->remove_cap('manage_list_manager');
        $user->remove_cap('list_manager_bulk_send');
        $user->remove_cap('list_manager_bulk_send_sms');

        if (isset($_POST['manage_list_manager']) && $_POST['manage_list_manager'] === "true") {
            $user->add_cap('manage_list_manager');
        }

        if (isset($_POST['list_manager_bulk_send']) && $_POST['list_manager_bulk_send'] === "true") {
            $user->add_cap('list_manager_bulk_send');
        }

        if (isset($_POST['list_manager_bulk_send_sms']) && $_POST['list_manager_bulk_send_sms'] === "true") {
            $user->add_cap('list_manager_bulk_send_sms');
        }
    }
}
