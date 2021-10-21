<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use JetBrains\PhpStorm\ArrayShape;
use InvalidArgumentException;
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
            'callback' => [$this, 'addUserToCollection'],
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

    #[ArrayShape(["email" => "mixed|string", "role" => "mixed|string"])]
    public function santatizeEmailAndRole($data = []): array|false
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException("email and role is required");
            return false;
        }

        // check email
        $email = $data["email"] ?? '';

        if ($email === "") {
            throw new InvalidArgumentException("email is required");
            return false;
        }

        if (!$this->isAllowedDomain($email)) {
            throw new InvalidArgumentException("you cannot use this email domain for registration");
            return false;
        }

        // check role
        $role =  $data["role"] ?? '';

        if (!$role === "") {
            throw new InvalidArgumentException("role is required");
            return false;
        }

        if (!in_array($role, ["gcadmin", "gceditor"])) {
            throw new InvalidArgumentException("role is not allowed");
            return false;
        }

        return ["email" => $email, "role" => $role];
    }

    protected function containsDomain ($email): bool
    {
        $allowed_email_domains = Users::ALLOWED_EMAIL_DOMAINS;

        [$username, $domain] = explode('@', trim($email));
        if (in_array($domain, $allowed_email_domains)) {
            return true;
        }

        return false;
    }

    protected function createUser($email): int
    {
        $result = wp_create_user($email, wp_generate_password(), $email);
        if (is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }

        return intval($result);
    }

    protected function addToBlog($uId, $role)
    {
        $result = add_user_to_blog(get_current_blog_id(), $uId, $role);

        if (is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }
    }

    public function addUserToCollection($data): array
    {
        try {
            $uId = false;
            [$email, $role] = $this->santatizeEmailAndRole($data);
            /*
            $uId = username_exists($email);

            if (is_user_member_of_blog($uId, get_current_blog_id())) {
                throw new Error("user is already a member for this collection");
            }

            if (!$uId) {
                $uId = $this->createUser($email);
            }

            $this->addToBlog($uId, $role);
            */
        } catch (\InvalidArgumentException $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    "location" => 'detect this',
                    'errors' => [$exception->getMessage()],
                    "uID" => $uId
                ]
            ]);
        } catch (\Exception $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    "location" => '',
                    'errors' => [$exception->getMessage()],
                    "uID" => $uId
                ]
            ]);
        }

        return new WP_REST_Response([
            ["status" => 200, "message" => "success"]
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

    public function isAllowedDomain($user_email): bool
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
