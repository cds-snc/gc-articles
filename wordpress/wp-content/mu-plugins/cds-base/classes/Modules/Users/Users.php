<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use WP_REST_Response;

class Users
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'tbs-sct.gc.ca'];

    public function __construct()
    {
        add_filter('wpmu_validate_user_signup', [$this, 'validateEmailDomain']);

        add_action('admin_menu', [$this, 'addPageFindUsers']);
        add_action('admin_enqueue_scripts', [$this, 'replacePageFindUsers']);

        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints(): void
    {
        register_rest_route('users/v1', '/roles', [
            'methods' => 'GET',
            'callback' => [$this, 'getRoles'],
            'permission_callback' => function () {
                return true; //current_user_can('read');
            },
        ]);

        register_rest_route('users/v1', '/submit', [
            'methods' => 'POST',
            'callback' => [$this, 'processUserForm'],
            'permission_callback' => function () {
                return true; //current_user_can('read');
            },
        ]);
    }

    public function getRoles()
    {
        global $wp_roles;
        $role_names_arr = [];
        foreach ($wp_roles->role_names as $key => $value) {
            array_push($role_names_arr, ['id' => $key, 'name' => $value]);
        }

        return new WP_REST_Response($role_names_arr);
    }

    public function processUserForm($data)
    {
        try {
            $uId = false;
            $email = $data["email"];
            $role =  $data["role"];

            $uId = username_exists($email);

            // @todo if user already has site access return

            if (!$uId) {
                $result = wp_create_user($email, wp_generate_password(), $email);
                if (is_wp_error($result)) {
                    return new WP_REST_Response([
                        [
                            "status" => 400,
                            "location" => 'email',
                            'errors' => [$result->get_error_message()],
                            "message" => "failed to create user"
                        ]
                    ]);
                }

                $uId = $result;
            }

            // add to blog
            $blogId = get_current_blog_id();

            $result = add_user_to_blog($blogId,  $uId, $role);

            if (is_wp_error($result)) {

                return new WP_REST_Response([
                    [
                        "status" => 400,
                        "location" => 'role',
                        'errors' => [$result->get_error_message()],
                        "message" => "failed to add user to collection"
                    ]
                ]);
            }
        } catch (\Exception $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    "location" => '',
                    'errors' => [$exception->getMessage()],
                    "message" => "exception",
                    "uID" => $uId
                ]
            ]);
        }

        return new WP_REST_Response([
            ["status" => 200, "message" => "user created"]
        ]);


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
            2,
        );
    }

    public function newPageTemplate($page_title = 'Hello world!'): void
    {
        $enable_js_msg = __(
            'You must enable JavaScript to view this page.',
            'cds-snc',
        ); ?>
        <div class="wrap" id="react-wrap">
            <h1 class="wp-heading-inline">
                <?php echo esc_html($page_title); ?>
            </h1>
            <hr class="wp-header-end"/>
            <div id="react-body">
                <p><?php echo $enable_js_msg; ?></p>
            </div>
        </div>
        <?php
    }

    public function replacePageFindUsers(): void
    {
        $current_page = sprintf(basename($_SERVER['REQUEST_URI']));
        if (str_contains($current_page, 'page=users-find')) {
            $data = 'CDS.renderUserForm();';
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
        }
    }

    public function validateEmailDomain($result)
    {
        $allowed_email_domains_HTML =
            '<ul><li>' .
            implode('</li><li>', self::ALLOWED_EMAIL_DOMAINS) .
            '</li></ul>';

        $details =
            '<details><summary>' .
            __('Expand to see allowed domains.', 'cds-snc') .
            '</summary>' .
            $allowed_email_domains_HTML .
            '</details>';

        $message =
            __(
                'You can not use this email domain for registration.',
                'cds-snc',
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
            self::ALLOWED_EMAIL_DOMAINS,
        );

        if (
            isset($user_email) &&
            strpos($user_email, '@') > 0 && // "@" can't be first character
            is_email($user_email)
        ) {
            [$username, $domain] = explode('@', trim($user_email));
            if (in_array($domain, $allowed_email_domains)) {
                return true;
            }
        }

        return false;
    }
}
