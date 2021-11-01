<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use JetBrains\PhpStorm\ArrayShape;
use WP_REST_Response;
use CDS\Modules\Users\EmailDomains;
use CDS\Modules\Users\Usernames;
use CDS\Modules\Users\ValidationException;

class Users
{
    public function __construct()
    {
        add_filter('sanitize_user', ['CDS\Modules\Users\Usernames', 'sanitizeUsernameAsEmail'], 10, 3);
        add_filter('manage_users_columns', ['CDS\Modules\Users\Usernames', 'removeEmailColumn']);

        add_filter('wpmu_validate_user_signup', ['CDS\Modules\Users\EmailDomains', 'validateEmailDomain']);

        add_action('admin_menu', [$this, 'addPageAddUsers']);
        add_action('admin_menu', [$this, 'removePageUsersAddNew']);
        add_action('admin_enqueue_scripts', [$this, 'replacePageAddUsers']);

        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    public function registerEndpoints(): void
    {
        register_rest_route('users/v1', '/roles', [
            'methods' => 'GET',
            'callback' => [$this, 'getRoles'],
            'permission_callback' => function () {
                return current_user_can('create_users');
            },
        ]);

        register_rest_route('users/v1', '/submit', [
            'methods' => 'POST',
            'callback' => [$this, 'addUserToCollection'],
            'permission_callback' => function () {
                return current_user_can('create_users');
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
    public function sanitizeEmailAndRole($data = []): array|false
    {
        if (!is_array($data) && !is_object($data)) {
            throw new ValidationException([ this->getEmailErrors(), $this->getRoleErrors() ]);
            return false;
        }

        $errors = array();

        /* validate email */
        $email = $data['email'] ?? "";
        $email_errors = $this->getEmailErrors($email);
        if ($email_errors) {
            array_push($errors, $email_errors);
        }

        /* validate role */
        $role = $data['role']['value'] ?? $data['role'] ?? "";
        $role_errors = $this->getRoleErrors($role);
        if ($role_errors) {
            array_push($errors, $role_errors);
        }

        if (count($errors)) {
            throw new ValidationException($errors);
        }

        return ["email" => sanitize_email($email), "role" => sanitize_text_field($role)];
    }

    private function getEmailErrors($email)
    {
        $error = ["location" => "email", "message" => __("Email is required."), "value" => $email];

        if ($email === "") {
            return $error;
        }

        if (!EmailDomains::isAllowedDomain(sanitize_email($email))) {
            $error['message'] = __("You can’t use this email domain for registration.");
            return $error;
        }

        return false;
    }

    private function getRoleErrors($role)
    {
        $error = ["location" => "role", "message" => __("Role is required."), "value" => $role];

        if ($role === "") {
            return $error;
        }

        if (!in_array(sanitize_text_field($role), [ "gceditor", "administrator" ])) {
            $error['message'] = __("You entered an invalid role.");
            return $error;
        }

        return false;
    }

    public function createUser($email): int
    {
        // @TODO: username should be the same as email
        $result = wp_create_user($email, wp_generate_password(), $email);
        if (is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }

        return intval($result);
    }

    public function addToBlog($uId, $role)
    {
        $result = add_user_to_blog(get_current_blog_id(), $uId, $role);

        if (is_wp_error($result)) {
            throw new \Exception($result->get_error_message());
        }
    }

    public function sendReset($uId, $email)
    {
        $userInfo = get_userdata($uId);
        $unique = get_password_reset_key($userInfo);
        $uniqueUrl = network_site_url(
            "wp-login.php?action=rp&key=$unique&login=" . rawurlencode($userInfo->user_login),
            'login'
        );

        $subject  = "Set Password";
        $message  = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
        $message .=  $uniqueUrl;

        wp_mail($email, $subject, $message);
    }

    public function addUserToCollection($data): WP_REST_Response|false
    {
        try {
            $uId = false;
            $statusCode = 200;

            $list = list('email' => $email, 'role' => $role) = $this->sanitizeEmailAndRole($data);

            // if we just use a logical OR, we get $uId === true rather than the integer
            $uId = email_exists($email) ? email_exists($email) : username_exists($email);

            // if we have a user AND they are a member of the blog, end early
            if ($uId && is_user_member_of_blog($uId, get_current_blog_id())) {
                throw new \Exception($email . __(" is already a member of this Collection"));
                return false;
            }

            // create the user if not exists
            if (!$uId) {
                $uId = $this->createUser($email);
                $this->sendReset($uId, $email);
                $statusCode = 201;
            }

            if (is_multisite()) {
                $this->addToBlog($uId, $role);
            }

            return new WP_REST_Response([
                [
                    "status" => $statusCode,
                    "message" => $email . __(" was added to the Collection."),
                    "uID" => $uId,
                    "email" => $email
                ]
            ]);
        } catch (ValidationException $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    "data" => $data,
                    "type" => gettype($data),
                    'errors' => $exception->decodeMessage(),
                    'uID' => $uId
                ]
            ]);
        } catch (\Exception $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    'errors' => [ [ "message" => $exception->getMessage() ] ],
                    'uID' => $uId,
                    'email' => $email
                ]
            ]);
        }
    }

    public function addPageAddUsers(): void
    {
        $page_title = __('Add user', 'cds-snc');

        // https://developer.wordpress.org/reference/functions/add_users_page/
        add_users_page(
            $page_title,
            $page_title,
            'create_users',
            'users-add',
            fn() => $this->newPageTemplate($page_title),
            2,
        );
    }


    public function removePageUsersAddNew(): void
    {
        $page = remove_submenu_page('users.php', 'user-new.php');

        global $pagenow;

        // Redirect from the default "Add New" users page to our new page
        // @TODO: GC Admins only see a "add existing" button / screen
        if ($pagenow === 'user-new.php') {
            wp_redirect(admin_url('/users.php?page=users-add'));
            exit;
        }
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

    public function replacePageAddUsers(): void
    {
        $current_page = basename($_SERVER['REQUEST_URI']);
        if (str_contains($current_page, 'page=users-add')) {
            $data = 'CDS.renderUserForm();';
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
        }
    }
}
