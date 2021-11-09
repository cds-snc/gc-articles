<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class UserSessions
{
    /**
     * Plugin singleton instance
     *
     * @var UserSessions
     */
    private static $instance;


    /**
     * Hide constructor for this singleton
     */
    private function __construct()
    {
    }

    /**
     * Singleton instance
     */
    public static function getInstance(): UserSessions
    {
        if (empty(self::$instance) && ! (self::$instance instanceof UserSessions)) {
            self::$instance = new UserSessions();
            self::$instance->addHooks();
        }

        return self::$instance;
    }


    private function addHooks(): void
    {
        // ~Heavily~ inspired by the "Remember Me Not" plugin: https://wordpress.org/plugins/remembermenot/

        // Remove the "Remember me" option from the login form
        add_action('login_form', [$this, 'removeRememberMe'], 99);
        // Reset any attempt to set the "Remember me" option
        add_action('login_head', [$this, 'resetRememberMeOption'], 99);
    }

    public function resetRememberMeOption(): void
    {
        // Remove the rememberme post value
        if (isset($_POST['rememberme'])) {
            unset($_POST['rememberme']);
        }
    }

    public function removeRememberMe(): void
    {
        ob_start([$this, 'processLoginForm']);
    }

    public function processLoginForm($content): string
    {   
        $content = preg_replace(
            '/<p class="forgetmenot">(.*)<\/p>/', // Remove the "remember me" checkbox
            '<style>#wp-submit {float: left;}</style>', // add CSS rules to left-align the button
            $content
        );

        return $content;
    }
}
