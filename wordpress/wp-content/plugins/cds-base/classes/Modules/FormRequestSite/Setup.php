<?php

declare(strict_types=1);

namespace CDS\Modules\FormRequestSite;

use CDS\Modules\FormRequestSite\Block;
use CDS\Modules\Forms\Messenger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use CDS\Modules\Notify\NotifyClient;

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
        new Block();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-request-js', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);
    }

    public function isUnsetOrEmpty(string $needle, array $haystack): bool
    {
        return !isset($haystack[$needle]) || $haystack[$needle] === '';
    }

    /* TODO */
    public function confirmSend(): array
    {
        if (!isset($_POST['request'])) {
            $message = __('400 Bad Request', 'cds-snc');
            return ['error' => true, "error_message" => $message];
        }

        if (!wp_verify_nonce($_POST['request'], 'request_form_nonce_action')) {
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
                $message .= sanitize_text_field(ucfirst($_key)) . ': ' . sanitize_text_field($value) . "\n\n";
            }
        }

        $messenger = new Messenger();
        $response = $messenger->sendMail("platform-mvp@cds-snc.ca", $message);

        // # @TODO add a "CC" to the requet form
        // if (isset($_POST['cc']) && $_POST['cc'] !== "") {
        //     $messenger->sendMail($email, $message);
        // }

        return $response;
    }
}
