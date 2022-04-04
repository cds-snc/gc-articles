<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use WP_Error;

class EmailDomains
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'gc.ca', 'canada.ca', '.onmicrosoft.com'];

    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();

        $instance->addFilters();
    }

    public function addFilters()
    {

        $pageName = basename($_SERVER['PHP_SELF']);

        if ('profile.php' === $pageName || 'user-edit.php' === $pageName) {
            add_filter('is_email', [$this, "isEmailFilter"], 10, 3);
        }
    }

    public static function isValidEmail($user_email): bool
    {
        if (
            isset($user_email) &&
            strpos($user_email, '@') > 0 && // "@" can't be first character
            is_email($user_email)
        ) {
            return true;
        }

        return false;
    }

    public static function isValidDomain($email): bool
    {
        try {
            if (!strpos($email, '@') > 0) {
                return false;
            }


            $allowed_email_domains = apply_filters(
                'cds_allowed_email_domains',
                self::ALLOWED_EMAIL_DOMAINS,
            );


            $isAllowedDomain  = false;

            [, $domain] = explode('@', trim($email));

            foreach ($allowed_email_domains as $allowed_domain) {
                if (str_ends_with($domain, $allowed_domain)) {
                    $isAllowedDomain = true;
                }
            }

            return  $isAllowedDomain;
        } catch (\Exception $e) {
            // no-op
            return false;
        }
    }

    public static function validateEmailDomain($result)
    {

        $message =
            __(
                'You can’t use this email domain for registration.',
                'cds-snc',
            );

        if (!self::isValidEmail($result['user_email']) || !self::isValidDomain($result['user_email'])) {
            $result['errors']->add('user_email', $message);
        }

        return $result;
    }

    public static function isEmailFilter($is_email = false, $email = ""): bool
    {

        if (!$is_email) {
            return false;
        }


        $WP_Error = new WP_Error();
        $WP_Error->add('invalid_email', __('Bad domain'));
        do_action('wp_error_added', "invalid_email", "bad", null, $WP_Error);

        return  self::isValidDomain($email);
    }
}
