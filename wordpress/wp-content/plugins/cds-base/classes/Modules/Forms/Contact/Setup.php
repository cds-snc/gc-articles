<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Contact;

use CDS\Modules\Forms\Messenger;
use CDS\Modules\Forms\Utils;

class Setup
{
    public function __construct()
    {
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
                },
            ]);
        });

        new ContactForm();
    }

    public function confirmSend(): array
    {
        $nonceErrorMessage = Utils::isNonceErrorMessage($_POST);
        if ($nonceErrorMessage) {
            return ['error' => true, "error_message" => $nonceErrorMessage];
        }

        $keys_page_1 = ['goal', 'usage', 'optional-usage-value', 'target', 'optional-target-value', 'message'];
        $keys_page_2 = ['fullname', 'email', 'department'];
        $empty_keys = [];

        foreach ($keys_page_2 as $_key) {
            if (!isset($_POST[$_key]) || $_POST[$_key] === '') {
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

        $fullname = sanitize_text_field($_POST['fullname']);
        $email = sanitize_email($_POST['email']);
        $department  = sanitize_text_field($_POST['department']);

        $goal = sanitize_text_field($_POST['goal']);

        $message = 'Goal of your message:' . "\n";
        $message .= $goal . "\n\n";

        $message .= 'Department:' . "\n";
        $message .= $department . "\n\n";

        if (isset($_POST['usage']) && is_array($_POST['usage'])) {
            $message .=
                'What are you thinking about using GC Articles for?' . "\n";

            foreach ($_POST['usage'] as $item) {
                $message .= '- ' . sanitize_text_field($item) . "\n";
            }
        }

        if (isset($_POST['optional-usage-value']) && $_POST['optional-usage-value'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['optional-usage-value']) .
                "\n";
        }

        if (isset($_POST['target']) && is_array($_POST['target'])) {
            $message .=
                "\n\n" .
                'Who are the target audiences you’re thinking about?' .
                "\n";
            foreach ($_POST['target'] as $item) {
                $message .= '- ' . sanitize_text_field($item) . "\n";
            }
        }

        if (isset($_POST['optional-target-value']) && $_POST['optional-target-value'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['optional-target-value']) .
                "\n";
        }

        $message .= "\n\n" . 'Message:' . "\n";
        $message .= sanitize_text_field($_POST['message']);

        // Add the submitted URL to the message text
        if (isset($_POST['url']) && $_POST['url'] !== '') {
            $message .=
            "\n\n" .
            'URL: ' .
            sanitize_text_field($_POST['url']);
        }

        $messenger = new Messenger();

        $platform_message = __('Requester:', 'cds-snc') . " " . $email . "\n\n" . $message;
        $response = $messenger->sendMail('platform-mvp@cds-snc.ca', $platform_message);

        if (isset($_POST['cc']) && $_POST['cc'] !== "") {
            $messenger->sendMail($email, $message);
        }

        return $response;
    }
}
