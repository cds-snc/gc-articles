<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class EmailDomains
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'gc.ca', 'canada.ca'];


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
        add_filter('is_email', [$this, "filterDomain"], 10, 3 );
    }

    public static function isAllowedDomain($user_email): bool
    {
        if (
            isset($user_email) &&
            strpos($user_email, '@') > 0 && // "@" can't be first character
            is_email($user_email)
        ) {
            return EmailDomains::filterDomain($user_email);
        }

        return false;
    }

    public static function validateEmailDomain($result)
    {
        $message =
            __(
                'You can’t use this email domain for registration.',
                'cds-snc',
            );

        if (!self::isAllowedDomain($result['user_email'])) {
            $result['errors']->add('user_email', $message);
        }

        return $result;
    }

    // fixes https://github.com/cds-snc/gc-articles-issues/issues/208
    public static function filterDomain($email): bool
    {
        $allowed_email_domains = apply_filters(
            'cds_allowed_email_domains',
            self::ALLOWED_EMAIL_DOMAINS,
        );

        $isAllowedDomain = false;

        [, $domain] = explode('@', trim($email));

        foreach (self::ALLOWED_EMAIL_DOMAINS as $allowed_domain) {
            if (str_ends_with($domain, $allowed_domain)) {
                $isAllowedDomain = true;
            }
        }

        return $isAllowedDomain;
    }
}
