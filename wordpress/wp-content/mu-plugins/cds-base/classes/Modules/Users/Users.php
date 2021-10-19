<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class Users
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'tbs-sct.gc.ca'];

    public function __construct()
    {
        add_filter('wpmu_validate_user_signup', [$this, 'validateEmailDomain']);

        add_action('admin_enqueue_scripts', [$this, "replaceUserPage"]);
    }

    public function validateEmailDomain($result)
    {
        $allowed_email_domains_HTML = "<ul><li>" . implode("</li><li>", self::ALLOWED_EMAIL_DOMAINS) . "</li></ul>";

        $details = "<details><summary>" .
            __('Expand to see allowed domains.', 'cds-snc') .
            "</summary>" . $allowed_email_domains_HTML . "</details>";

        $message = __(
            "You can not use this email domain for registration.",
            'cds-snc'
        ) . $details;


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

    public function replaceUserPage(): void
    {
        $current_page = sprintf(basename($_SERVER['REQUEST_URI']));
        if ($current_page == "user-new.php") {
            $data = 'CDS.renderUserForm();';
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
        }
    }
}
