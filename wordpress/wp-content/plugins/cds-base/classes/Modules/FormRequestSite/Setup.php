<?php

declare(strict_types=1);

namespace CDS\Modules\FormRequestSite;

use CDS\Modules\FormRequestSite\Block;
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

        // where is this?
        wp_localize_script("cds-subscribe-js", "CDS_VARS", array(
            "rest_url" => esc_url_raw(rest_url()),
            "rest_nonce" => wp_create_nonce("wp_rest"),
        ));
    }

    protected function sendEmail(string $email, string $message, string $contactType): array
    {
        try {
            $notifyMailer = new NotifyClient();
            $to = 'platform-mvp@cds-snc.ca';
            $notifyTemplateId = "125002c5-cf95-4eec-a6c8-f97eda56550a";
            $notifyMailer->sendMail($to, $notifyTemplateId, [
                'email' => $email,
                'contact-type' => $contactType,
                'message' => $message
            ]);

            return ["success" => __("Thanks for the message", "cds-snc")];
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return ["error" => $exception->getMessage()];
        }
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

        return [
            'error' =>  true,
            "error_message" => 'problem',
            'post' => $_POST
        ];

        if (
            !isset($_POST['site']) || $_POST['site'] === ''
        ) {
            $message = __(
                'Please complete the required field(s) to continue',
                'cds-snc',
            );

            return [
                'error' =>  true,
                "error_message" => $message,
                'post' => $_POST
            ];
        }

        return [
            'error' =>  true,
            "error_message" => "everything worked"
        ];
    }
}
