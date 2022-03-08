<?php

declare(strict_types=1);

namespace CDS\Modules\Contact;

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

    public function enqueue()
    {
        wp_enqueue_script(
            'cds-contact-js',
            plugin_dir_url(__FILE__) . '/src/handler.js',
            ['jquery'],
            '1.0.0',
            true,
        );

        wp_localize_script('cds-subscribe-js', 'CDS_VARS', [
            'rest_url' => esc_url_raw(rest_url()),
            'rest_nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    public function confirmSend(): array
    {
        if (!isset($_POST['contact'])) {
            $message = __('400 Bad Request', 'cds-snc');
            return ['error' => true, "error_message" => $message];
        }

        if (!wp_verify_nonce($_POST['contact'], 'contact_form_nonce_action')) {
            $message = __('400 Bad Request', 'cds-snc');
            return ['error' => true , "error_message" => $message];
        }

        if (
            !isset($_POST['message']) || $_POST['message'] === '' ||
            (!isset($_POST['fullname']) || $_POST['fullname'] === '') ||
            (!isset($_POST['email']) || $_POST['email'] === '') ||
            (!isset($_POST['goal']) || $_POST['goal'] === '')
        ) {
            $message = __(
                'Please complete the required field(s) to continue',
                'cds-snc',
            );

            return [
                'error' =>  true,
                "error_message" => $message
            ];
        }

        $fullname = sanitize_text_field($_POST['fullname']);
        $email = sanitize_email($_POST['email']);
        $goal = sanitize_text_field($_POST['goal']);

        $message = 'Goal of your message:' . "\n";
        $message .= $goal . "\n\n";

        if (isset($_POST['usage'])) {
            $message .=
                'What are you thinking about using GC Articles for?' . "\n";

            foreach ($_POST['usage'] as $item) {
                $message .= '- ' . sanitize_text_field($item) . "\n";
            }
        }

        if (isset($_POST['usage-other']) && $_POST['usage-other'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['usage-other']) .
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

        if (isset($_POST['target-other']) && $_POST['target-other'] !== '') {
            $message .=
                "\n" .
                '(Other) ' .
                sanitize_text_field($_POST['target-other']) .
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
