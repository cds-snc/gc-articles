<?php

declare(strict_types=1);

namespace CDS\Modules\FormRequestSite;

use CDS\Modules\Forms\Messenger;

class Setup
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);

        /*
         * Note - if testing with WP ENV
         * https://wordpress.org/support/topic/wp-env-with-gutenber-doesnt-have-a-rest-api/
         */
        add_action('rest_api_init', function () {
            register_rest_route('request/v1', '/process', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSend'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        new RequestSite();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-request-js', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);
    }

    public function isUnsetOrEmpty(string $needle, array $haystack): bool
    {
        return !isset($haystack[$needle]) || $haystack[$needle] === '';
    }

    protected function removeslashes($str)
    {
        $str = implode("", explode("\\", $str));
        return stripslashes(trim($str));
    }

    public function confirmSend(): array
    {
        if (!isset($_POST['cds-form-nonce'])) {
            $message = __('400 Bad Request', 'cds-snc');
            return ['error' => true, "error_message" => $message];
        }

        if (!wp_verify_nonce($_POST['cds-form-nonce'], 'cds_form_nonce_action')) {
            $message = __('400 Bad Request', 'cds-snc');
            return ['error' => true , "error_message" => $message];
        }

        $keys_page_1 = ['site', 'usage', 'usage-other', 'target', 'target-other', 'timeline'];
        $keys_page_2 = ['fullname', 'email', 'role', 'department'];
        $empty_keys = [];

        foreach ($keys_page_2 as $_key) {
            if ($this->isUnsetOrEmpty($_key, $_POST)) {
                array_push($empty_keys, $_key);
            }
        }

        if (
            !empty($empty_keys) // if this is NOT empty, then we are missing a key
        ) {
            $message = __(
                'Please complete the required field(s) to continue',
                'cds-snc',
            );

            return [
                'error' =>  true,
                'error_message' => $message,
                'keys' => $empty_keys
            ];
        }

        $all_keys = array_merge($keys_page_1, $keys_page_2);
        $message = '';

        foreach ($all_keys as $_key) {
            $value = $_POST[$_key] ?? '';
            if ($value) {
                $value = is_array($value) ? str_replace(".", "", implode(", ", $value)) : $value;
                $message .= sanitize_text_field(ucfirst($_key)) . ': ' . $this->removeslashes(sanitize_text_field($value)) . "\n\n";
            }
        }

        $site = $_POST['site'] ?? __('No name specified', 'cds-snc');
        $goal = __('Request a site:', 'cds-snc') . ' ' . $this->removeslashes($site);
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];

        $messenger = new Messenger();
        $response = $messenger->createTicket($goal, $fullname, $email, $message);

        $cc = $_POST['cc'] ?? '';
        if ($cc) {
            $messenger->sendMail($email, $message);
        }

        return $response;
    }
}
