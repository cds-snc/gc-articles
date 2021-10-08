<?php

declare(strict_types=1);

namespace CDS\Modules\Contact;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

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
            register_rest_route('contact/v1', '/process/', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSend'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        new ContactForm();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-contact-js', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);

        wp_localize_script("cds-subscribe-js", "CDS_VARS", array(
            "rest_url" => esc_url_raw(rest_url()),
            "rest_nonce" => wp_create_nonce("wp_rest"),
        ));
    }

    protected function sendEmail(string $email): array
    {
        try {
            return ["success" => __("Thanks for the message", "cds-snc")];
        } catch (Exception $exception) {
           error_log($exception->getMessage());
            return ["error" => $exception->getMessage()];
        }
    }

    public function confirmSend(): string
    {
        if (!isset($_POST['contact_form'])) {
            return json_encode(["error" => __("400 Bad Request", "cds-snc")]);
        }

        if (!wp_verify_nonce($_POST['contact_form'], 'contact_form_nonce_action')) {
            return json_encode(["error" => __("401 Unauthorized", "cds-snc")]);
        }

        if (!isset($_POST["message"]) || $_POST["message"] === "") {
            return json_encode(["error" => __("Please complete the required field to continue", "cds-snc")]);
        }

        return json_encode($this->sendEmail($_POST["message"]));
    }
}
