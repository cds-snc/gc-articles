<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use CDS\Modules\TrackLogins\TrackLogins;

class UserLockout
{
    public const USER_LOCKOUT_TIME = (60 * 60 * 24) * 90; // 90 days

    public $loginPlugin;
    public $trackLogins;

    public function __construct()
    {
        // Setup method for "Disable User Login" plugin
        $disable_user_login_plugin_active = function_exists('SSDUL');

        if ($disable_user_login_plugin_active) {
            // returns an instance of "SS_Disable_User_Login_Plugin"
            $this->loginPlugin = SSDUL();
            $this->trackLogins = new TrackLogins();

            // add a login row whenever someone is enabled
            add_action('disable_user_login.user_enabled', [$this,'insertLoginForEnabledUser']);

            add_action('lockout_cron', [$this, 'setLoginLock']);

            register_deactivation_hook(__FILE__, [$this, 'deactivateCron']);

            if (! wp_next_scheduled('lockout_cron')) {
                wp_schedule_event(time(), 'daily', 'lockout_cron');
            }
        } else {
            // remove cron event if disable login plugin is not active
            $this->deactivateCron();
        }
    }

    public function deactivateCron(): void
    {
        $timestamp = wp_next_scheduled('lockout_cron');
        wp_unschedule_event($timestamp, 'lockout_cron');
        wp_clear_scheduled_hook('lockout_cron');
    }

    public function lockUser(string|int $user_id): void
    {
        $originally_disabled = get_user_meta($user_id, $this->loginPlugin->user_meta_key(), true);

        // Update the user's disabled status
        update_user_meta($user_id, $this->loginPlugin->user_meta_key(), true);

        // Trigger an action when a user's account is enabled
        if (!$originally_disabled) {
            do_action('disable_user_login.user_disabled', $user_id);
        }
    }

    public function setLoginLock(): void
    {
        $users = get_users(array( 'fields' => array( 'id' ) ));
        $user_ids = array_map(function ($users) {
            return $users->id;
        }, $users);

        // remove superadmins
        $user_ids = array_filter(
            $user_ids,
            function ($user_id) {
                return ! is_super_admin($user_id);
            }
        );

        foreach ($user_ids as $user_id) {
            $logins = $this->trackLogins->getUserLogins($user_id, $limit = 1);

            // you can't be locked out before you log in
            $login_time = empty($logins[0]) ?
                time() : // set to current time if they have never logged in
                strtotime($logins[0]->time_login);

            $seconds_since_last_login = time() - $login_time;

            if ($seconds_since_last_login > self::USER_LOCKOUT_TIME) {
                $this->lockUser($user_id);
            }
        }
    }

    public function insertLoginForEnabledUser(int|string $user_id): void
    {
        $user = get_user_by('id', $user_id);

        $this->trackLogins->insertUserLogin(
            $user,
            $user_agent = "disable_user_login.user_enabled"
        );
    }
}
