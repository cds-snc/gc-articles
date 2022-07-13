<?php

declare(strict_types=1);

namespace GCLists;

use WP_User;

class Permissions
{
    protected static $instance;

    public static function getInstance(): Permissions
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    /**
     * Setup default Role permisssions overriding what's in cds-base\classes\Modules\Cleanup\Roles.php
     * which should be refactored and parts of it removed.
     */
    public function cleanupCustomCapsForRoles()
    {
        $role = get_role('administrator');
        $role->add_cap('manage_notify', true);
        $role->add_cap('manage_list_manager', true);
        $role->add_cap('list_manager_bulk_send', true);
        $role->add_cap('list_manager_bulk_send_sms', false);

        $role = get_role('gceditor');
        $role->remove_cap('manage_notify');
        $role->remove_cap('manage_list_manager');
        $role->remove_cap('list_manager_bulk_send');
        $role->remove_cap('list_manager_bulk_send_sms');

        $role = get_role('gcwriter');
        $role->remove_cap('manage_notify');
        $role->remove_cap('manage_list_manager');
        $role->remove_cap('list_manager_bulk_send');
        $role->remove_cap('list_manager_bulk_send_sms');

        add_option('gc-lists_roles_cleanup', true);
    }

    public function addDefaultUserCapsForRole($user_id, $role, $old_roles)
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

    public function displayListManagerMeta($user)
    {
        if (!is_super_admin()) {
            return;
        }

        printf('<h3>%s</h3>', __('GC Lists', 'cds-snc'));
        print "<table class='form-table'>";

        /* MANAGE LISTS */
        print '<tr>';
        $manage_list_manager = user_can($user, "manage_list_manager");

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
        $list_manager_bulk_send = user_can($user, "list_manager_bulk_send");

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
        $list_manager_bulk_send_sms = user_can($user, "list_manager_bulk_send_sms");

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
