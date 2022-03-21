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

        $required_keys = ['fullname', 'email', 'department', 'goal', 'message'];
        $empty_keys = [];

        foreach ($required_keys as $_key) {
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
        $message .= $department . "\n";

        if (isset($_POST['usage'])) {
            $message .=
                'What are you thinking about using GC Articles for?' . "\n";

            foreach ($_POST['usage'] as $item) {
                $message .= '- ' . sanitize_text_field($item) . "\n";
            }
        }

        if (isset($_POST['usage-optional']) && $_POST['usage-optional'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['usage-optional']) .
                "\n";
        }

        if (isset($_POST['target'])) {
            $message .=
                "\n\n" .
                'Who are the target audiences you’re thinking about?' .
                "\n";
            foreach ($_POST['target'] as $item) {
                $message .= '- ' . sanitize_text_field($item) . "\n";
            }
        }

        if (isset($_POST['target-optional']) && $_POST['target-optional'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['target-optional']) .
                "\n";
        }

        $message .= "\n\n" . 'Message:' . "\n";
        $message .= sanitize_text_field($_POST['message']);

        $messenger = new Messenger();
        $response = $messenger->createTicket($goal, $fullname, $email, $message);

        if (isset($_POST['cc']) && $_POST['cc'] !== "") {
            $messenger->sendMail($email, $message);
        }

        return $response;
    }
}
