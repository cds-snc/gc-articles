<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use JetBrains\PhpStorm\ArrayShape;
use WP_REST_Response;
use CDS\Modules\Users\EmailDomains;
use CDS\Modules\Users\UserLockout;
use CDS\Modules\Users\UserSessions;
use CDS\Modules\Users\ValidationException;
use CDS\Modules\Notify\NotifyClient;

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

        add_action('pre_user_query', [$this, 'hideSuperAdminsFromUserList']);

        add_action('rest_api_init', [$this, 'registerEndpoints']);

        add_action('plugins_loaded', function () {
            new UserLockout();
        }, 12); // relies on "Disable User Login" plugin, which activates itself at priority 11

        UserSessions::getInstance();
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

        $administrator = __(
            "This role has complete control over the articles collection and can perform all other roles actions",
            "cds-snc"
        );
        $gceditor = __(
            "This role is allows the user to write and publish articles online to the collection.",
            "cds-snc"
        );

        $gcwriter = __(
            "This role is allows the user to write articles online to the collection.",
            "cds-snc"
        );
        $roleDescriptions = ["administrator" => $administrator, "gceditor" => $gceditor, "gcwriter" => $gcwriter];

        foreach ($wp_roles->role_names as $key => $value) {
            $desc = "";
            if ($roleDescriptions[$key]) {
                $desc = $roleDescriptions[$key];
            }
            array_push($role_names_arr, ['id' => $key, 'name' => $value, "description" => $desc]);
        }

        return new WP_REST_Response($role_names_arr);
    }

    #[ArrayShape(["email" => "mixed|string", "role" => "mixed|string" , "confirmationType" => "mixed|string"])]
    public function sanitizeValues($data = []): array|false
    {
        if (!is_array($data) && !is_object($data)) {
            throw new ValidationException([ $this->getEmailErrors(), $this->getRoleErrors() ]);
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

        $confirmationType = $data['confirmationType'] ?? "";

        if ($confirmationType !== "welcome") {
            $confirmationType = "default";
        }

        return ["email" => sanitize_email($email), "role" => sanitize_text_field($role), "confirmationType" => $confirmationType];
    }

    private function getEmailErrors($email)
    {
        $error = ["location" => "email", "message" => __("Email is required."), "value" => $email];

        if ($email === "") {
            return $error;
        }

        if (!EmailDomains::isValidDomain(sanitize_email($email))) {
            $error['message'] = __("You must enter a Government of Canada email to send an invitation.");
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

        if (!in_array(sanitize_text_field($role), [ "gcwriter", "gceditor", "administrator" ])) {
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

    // note this is only available to super admins
    // for use when setting up a new site / user
    public function sendWelcome($uId, $email, $userExists)
    {

        $password_message = "";

        if (!$userExists) {
            $userInfo = get_userdata($uId);
            $unique = get_password_reset_key($userInfo);

            $uniqueUrl = network_site_url(
                sprintf("wp-login.php?action=rp&key=%s&login=%s", $unique, rawurlencode($userInfo->user_login)),
                'login'
            );
        }

        if (!$userExists) {
            // we don't need to send this for existing users
            $password_message = $uniqueUrl;
        }

        try {
            $notifyClient = new NotifyClient();
            $notifyClient->sendMail(
                $email,
                "a11693cb-2b84-4920-9e66-e9eb649fc948",
                [
                    "password_message" => $password_message
                ],
            );
        } catch (\Alphagov\Notifications\Exception\NotifyException $e) {
            error_log("[Notify] " . $e->getMessage());
            // throw new error to be handled by add user
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    public function sendReset($uId, $email)
    {
        $userInfo = get_userdata($uId);
        $unique = get_password_reset_key($userInfo);
        $uniqueUrl = network_site_url(
            "wp-login.php?action=rp&key=$unique&login=" . rawurlencode($userInfo->user_login),
            'login'
        );

        // phpcs:disable
        $subject = __("Invitation to collaborate on GC Articles", "cds-snc");
        $message = __('Someone has invited this email to collaborate on a GC Articles collection site.', "cds-snc") . "\r\n\r\n";
        $message .= __('If this was a mistake, please ignore this email and the invitation will expire', "cds-snc") . "\r\n\r\n";
        $message .= __('To set your GC Articles account password, please visit the following address:', "cds-snc") . "\r\n\r\n";
        $message .= $uniqueUrl;
        // phpcs:enable

        wp_mail($email, $subject, $message);
    }

    public function sendAddToCollection($uId, $email)
    {
        $userInfo = get_userdata($uId);

        $blogName = get_bloginfo('name');
        $url = network_site_url('wp-login.php');

        // phpcs:disable
        $subject = __("Invitation to collaborate on GC Articles", "cds-snc"). " — ". $blogName;
        $message .= __('Someone has invited this email to collaborate on a GC Articles — ', "cds-snc").$blogName.".". "\r\n\r\n";
        $message .= __('To get started log in here: ', "cds-snc").$url;
        
        // phpcs:enable

        wp_mail($email, $subject, $message);
    }

    public function addUserToCollection($data): WP_REST_Response|false
    {
        try {
            $uId = false;
            $statusCode = 200;

            $list = list('email' => $email, 'role' => $role , 'confirmationType' => $confirmationType) = $this->sanitizeValues($data);

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

                if ($confirmationType === "welcome") {
                    $this->sendWelcome($uId, $email, false);
                } else {
                    $this->sendReset($uId, $email);
                }

                $statusCode = 201;
            }

            if (is_multisite()) {
                $this->addToBlog($uId, $role);

                if ($statusCode === 200) {
                    if ($confirmationType === "welcome") {
                        $this->sendWelcome($uId, $email, true);
                    } else {
                        // only send if we haven't created a new user
                        $this->sendAddToCollection($uId, $email);
                    }
                }
            }

            return new WP_REST_Response([
                [
                    "status" => $statusCode,
                    "message" => $email . __(" was invited to the Site."),
                    "uID" => $uId,
                    "email" => $email,
                    "confirmationType" => $confirmationType
                ]
            ]);
        } catch (ValidationException $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    "data" => $data,
                    "type" => gettype($data),
                    'errors' => $exception->decodeMessage(),
                    'uID' => $uId,
                    "confirmationType" => $confirmationType
                ]
            ]);
        } catch (\Exception $exception) {
            return new WP_REST_Response([
                [
                    "status" => 400,
                    'errors' => [ [ "message" => $exception->getMessage() ] ],
                    'uID' => $uId,
                    'email' => $email,
                    "confirmationType" => $confirmationType
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
            if (is_super_admin()) {
                $data = 'CDS.renderUserForm({isSuperAdmin: true});';
            } else {
                $data = 'CDS.renderUserForm({isSuperAdmin: false});';
            }
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
        }
    }

    public function hideSuperAdminsFromUserList($user_search)
    {
        if (! is_super_admin()) {
            $super_admins = get_super_admins(); // returns array of superadmin usernames, not IDs
            $usernames = implode("', '", array_map('esc_sql', $super_admins));

            global $wpdb;
            $user_search->query_where =
                str_replace(
                    'WHERE 1=1',
                    "WHERE 1=1 AND {$wpdb->users}.user_login NOT IN ('" . $usernames . "')",
                    $user_search->query_where
                );
        }
    }
}
