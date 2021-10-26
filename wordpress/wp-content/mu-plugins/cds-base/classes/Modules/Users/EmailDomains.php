<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class EmailDomains
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'gc.ca', 'canada.ca'];

    public static function isAllowedDomain($user_email): bool
    {

        $allowed_email_domains = apply_filters(
            'cds_allowed_email_domains',
            self::ALLOWED_EMAIL_DOMAINS,
        );

        if (
            isset($user_email) &&
            strpos($user_email, '@') > 0 && // "@" can't be first character
            is_email($user_email)
        ) {
            [$username, $domain] = explode('@', trim($user_email));

            foreach (self::ALLOWED_EMAIL_DOMAINS as $allowed_domain) {
                if (str_ends_with($domain, $allowed_domain)) {
                    return true;
                }
            }
        }

        return false;
    }
}
