<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use CDS\Modules\TrackLogins\TrackLogins;

class UserLockout
{
    public const USER_LOCKOUT_STRING = '_is_disabled'; // same key as "Disable User Login" plugin
    public const USER_LOCKOUT_TIME = 60; // 60 seconds

    public $loginPlugin;
    public $trackLogins;

    public function __construct()
    {
        /* @TODO: on "enabled", update login time */

        // Setup method for "Disable User Login" plugin
        $disable_user_login_plugin_active = function_exists('SSDUL');
        // var_dump($disable_user_login_plugin_active);

        if($disable_user_login_plugin_active) {

            // returns an instance of "SS_Disable_User_Login_Plugin"
            $this->loginPlugin = SSDUL();
            $this->trackLogins = new TrackLogins();

            // the original width of 80px messed up our table
            add_action('admin_footer-users.php', [$this, 'manage_users_css'], 11);
            // add a login whenever someone is enabled
            add_action('disable_user_login.user_enabled', [$this,'insertLoginForEnabledUser']);

            add_filter('cron_schedules', [$this, 'customCronSchedule']);
            add_action('lockout_cron', [$this, 'setLoginLock']);

            register_deactivation_hook(__FILE__, [$this, 'deactivateCron']);

            if (! wp_next_scheduled('lockout_cron')) {
                wp_schedule_event(time(), '45-seconds', 'lockout_cron');
            }
        } else {
            // remove cron event if disable login plugin is not active
            $this->deactivateCron();
        }
    }

    /**
	 * Specify the width of our custom column
     */
	public function manage_users_css()
    {
		echo '<style type="text/css">.column-disable_user_login { width: 85px; }</style>';
	}

    public function deactivateCron()
    {
        $timestamp = wp_next_scheduled( 'lockout_cron' );
        wp_unschedule_event( $timestamp, 'lockout_cron' );
        wp_clear_scheduled_hook('lockout_cron');
    }

    /**
     * Adds a custom cron schedule for every minute
     *
     * @param array $schedules An array of non-default cron schedules.
     * @return array Filtered array of non-default cron schedules.
     */
    public function customCronSchedule(array $schedules): array
    {
        $schedules[ '45-seconds' ] = array( 'interval' => 45, 'display' => __('Every 45 seconds', 'cds-snc') );
        return $schedules;
    }

    public function lockUser(string|int $user_id)
    {
        $originally_disabled = get_user_meta( $user_id, self::USER_LOCKOUT_STRING, true );

		// Update the user's disabled status
		update_user_meta( $user_id, self::USER_LOCKOUT_STRING, true );

        /**
		 * Trigger an action when a user's account is enabled
		 */
		if ( ! $originally_disabled ) {
			do_action( 'disable_user_login.user_disabled', $user_id );
		}
    }

    public function setLoginLock()
    {
        $users = get_users(array( 'fields' => array( 'id' ) ));
        $user_ids = array_map(function ($users) {
            return $users->id;
        }, $users);

        // remove superadmins
        $user_ids = array_filter(
            $user_ids,
            function ($user_id) { return ! is_super_admin( $user_id ); },
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

    public function insertLoginForEnabledUser($user_id) {
        $user = get_user_by('id', $user_id);

        $this->trackLogins->insertUserLogin(
            $user,
            $user_agent = "disable_user_login.user_enabled"
        );
    }
}
