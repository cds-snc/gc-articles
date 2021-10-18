<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class Users
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'tbs-sct.gc.ca'];

    public function __construct()
    {
        add_filter('wpmu_validate_user_signup', [$this, 'validateEmailDomain']);
    }

    public function validateEmailDomain($result)
    {
        $allowed_email_domains_HTML = "<ul><li>" . implode("</li><li>", self::ALLOWED_EMAIL_DOMAINS) . "</li></ul>";
        $message = __(
            "You can not use this email domain for registration. Permitted email addresses:",
            'cds-snc'
        ) . $allowed_email_domains_HTML;

        if (!$this->isAllowedDomain($result['user_email'])) {
            $result['errors']->add('user_email', $message);
        }

        return $result;
    }

    public function isAllowedDomain($user_email)
    {
        $allowed_email_domains = apply_filters(
            'cds_allowed_email_domains',
            self::ALLOWED_EMAIL_DOMAINS
        );

        if (
            isset($user_email)
            && strpos($user_email, '@') > 0 // "@" can't be first character
            && is_email($user_email)
        ) {
            list($username, $domain) = explode('@', trim($user_email));
            if (in_array($domain, $allowed_email_domains)) {
                return true;
            }
        }

        return false;
    }
}
