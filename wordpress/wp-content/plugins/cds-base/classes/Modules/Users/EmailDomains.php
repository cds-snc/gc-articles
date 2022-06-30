<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use WP_Error;

class EmailDomains
{
    /**
     * Regex matching rules for valid email domains
     *
     * When specifying domains/subdomains, make sure you're not opening this up to unintended inclusions
     * of domains not controlled by GoC. For example, if you leave off the leading ^ anchor character
     * on domains you could unintentionally match an invalid domain, ie:
     *
     * cds-snc.ca - could match bad.actor@badcds-snc.ca - use ^cds-snc.ca
     *
     * For subdomains, ensure you escape the leading . with a backslash \ character otherwise you could
     * unintentionally match an invalid domain as . is a wildcard that will match any character, ie:
     *
     * .canada.ca - could match bad.actor@badcanada.ca - use \.canada.ca
     *
     * Finally, with adhoc domains that innovation teams sometimes use, they should be explicitly
     * added when they come up, as there is a lot of potential for risky matching, ie:
     *
     * \.onmicrosoft.com - anyone in the world could register an email address that matches this.
     *
     * NOTE: the regex filter automatically adds the end of string $ anchor character to these
     * rules when applied, as there should never be a trailing wildcard.
     */
    public const ALLOWED_EMAIL_DOMAINS = [
        '^cds-snc.ca',
        '\.gc.ca',
        '^canada.ca',
        '\.canada.ca',
        '^pspcinnovation.onmicrosoft.com',
        '^servicecanada.ca'
    ];

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
        if (!strpos($email, '@') > 0) {
            return false;
        }

        $allowed_email_domains = self::ALLOWED_EMAIL_DOMAINS;

        [, $domain] = explode('@', trim($email));

        foreach ($allowed_email_domains as $allowed_domain) {
            if (preg_match("/$allowed_domain$/", $domain)) {
                return true;
            }
        }

        return  false;
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
