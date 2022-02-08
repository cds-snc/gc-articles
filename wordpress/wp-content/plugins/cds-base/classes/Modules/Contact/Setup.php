<?php

declare(strict_types=1);

namespace CDS\Modules\Contact;

use CDS\Modules\Contact\Block;
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
            register_rest_route('contact/v1', '/process/', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSend'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        new ContactForm();
        new Block();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-contact-js', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);

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

    public function confirmSend(): string
    {
        if (!isset($_POST['contact'])) {
            return json_encode(["error" => __("400 Bad Request", "cds-snc")]);
        }

        if (!wp_verify_nonce($_POST['contact'], 'contact_form_nonce_action')) {
            return json_encode(["error" => __("401 Unauthorized", "cds-snc")]);
        }

        if (isset($_POST['contact-type']) && $_POST['contact-type'] === "request-site") {
            $errors = false;
            if (!isset($_POST['purpose']) || $_POST['purpose'] === "") {
                $errors = true;
            }

            if (!isset($_POST['heard-about-from']) || $_POST['heard-about-from'] === "") {
                $errors = true;
            }


            if (!isset($_POST['gc-collection-name']) || $_POST['gc-collection-name'] === "") {
                $errors = true;
            }

            if (!isset($_POST['sending-integration']) || $_POST['sending-integration'] === "") {
                $errors = true;
            }

            if ($errors) {
                return json_encode(["error" => __("Please complete all required fields to continue", "cds-snc")]);
            }

            $message = "\n\n";
            $message .= "• Request Collection Name: " . sanitize_text_field($_POST['gc-collection-name']) . "\n\n";
            $message .= "• Purpose: " . sanitize_text_field($_POST['purpose']) . "\n\n";
            $message .= "• Heard about from: " . sanitize_text_field($_POST['heard-about-from']) . "\n\n";
            $message .= "• Sending integration: " . sanitize_text_field($_POST['sending-integration']) . "\n\n";
            $email = sanitize_email($_POST["email"]);
            $contactType = sanitize_text_field($_POST["contact-type"]);

            return json_encode($this->sendEmail($email, $message, $contactType));
        } else {
            if (
                (!isset($_POST["message"]) || $_POST["message"] === "")
                || (!isset($_POST['contact-type']) || $_POST['contact-type'] === "")
                || (!isset($_POST['email']) || $_POST['email'] === "")
            ) {
                return json_encode(["error" => __("Please complete the required field to continue", "cds-snc")]);
            }

            $message = sanitize_text_field($_POST['message']);
            $email = sanitize_email($_POST["email"]);
            $contactType = sanitize_text_field($_POST["contact-type"]);

            return json_encode($this->sendEmail($email, $message, $contactType));
        }
    }
}
