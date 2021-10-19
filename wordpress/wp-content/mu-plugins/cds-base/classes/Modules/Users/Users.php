<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class Users
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'tbs-sct.gc.ca'];

    public function __construct()
    {
        add_filter('wpmu_validate_user_signup', [$this, 'validateEmailDomain']);

        add_action('admin_menu', [$this, 'addPageFindUsers']);
        add_action('admin_enqueue_scripts', [$this, 'replacePageFindUsers']);
    }

    public function addPageFindUsers(): void
    {
        $page_title = __('Find Users', 'cds-snc');

        // https://developer.wordpress.org/reference/functions/add_users_page/
        add_users_page(
            $page_title,
            $page_title,
            'manage_options', // permissions needed to see the menu option
            'users-find',
            fn() => $this->newPageTemplate($page_title),
            2
        );
    }

    public function newPageTemplate($page_title = 'Hello world!'): void
    {
        ?>
        <div class="wrap" id="react-wrap">
            <h1 class="wp-heading-inline">
                <?php echo esc_html($page_title) ?>
            </h1>
            <hr class="wp-header-end" />
            <div id="react-body">
                <p>You must enable JavaScript to view this page.</p>
            </div>
        </div>
        <?php
    }

    public function replacePageFindUsers(): void
    {
        $current_page = sprintf(basename($_SERVER['REQUEST_URI']));
        if (str_contains($current_page, "page=users-find")) {
            $data = 'CDS.renderUserForm();';
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
        }
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

    public function isAllowedDomain($user_email): boolean
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
