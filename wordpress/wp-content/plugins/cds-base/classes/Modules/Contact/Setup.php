<?php

declare(strict_types=1);

namespace CDS\Modules\Contact;

use CDS\Modules\Contact\Block;
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
            register_rest_route('contact/v1', '/process/', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSend'],
                'permission_callback' => function () {
                    return '';
                },
            ]);
        });

        new ContactForm();
        new Block();
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

    protected function isJson($string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    protected function handleException($e)
    {
        $exception = (string) $e->getResponse()->getBody();

        error_log("ZENDESK - ClientException" . $exception);

        if ($this->isJson($exception)) {
            try {
                return json_decode($exception);
            } catch (\Exception $e) {
                return __('ZenDesk client error', 'cds-snc');
            }
        }
    }

    protected function createTicket(
        string $goal,
        string $fullname,
        string $email,
        string $message,
    ): array {

        try {
            $client = new Client([]);

            $response = $client->request('POST', getenv('ZENDESK_API_URL') . '/api/v2/requests', [
                'json' =>  ["request" => [
                    'subject' => $goal,
                    'description' => '',
                    'email' => $email,
                    'comment' => ['body' => $message],
                    'requester' => ['name' => $fullname, 'email' => $email],
                    'tags' => ['articles_api']
                ]
                ],
            ]);

            return ['success' => __('Success', 'cds-snc')];
        } catch (ClientException $exception) {
            return ['error' => ["exceptions" => $this->handleException($exception)], "error_message" => __('Internal server error', 'cds-snc')];
        } catch (Exception $e) {
            error_log("ZENDESK - Exception" . $exception->getMessage());
            return ['error' => true, "error_message" => __('ZenDesk server error', 'cds-snc')];
        }
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

        # on hold
        # $response = $this->createTicket($goal, $fullname, $email, $message);
        $messenger = new Messenger();
        $response = $messenger->sendMail("platform-mvp@cds-snc.ca", $message);

        if (isset($_POST['cc']) && $_POST['cc'] !== "") {
            $messenger->sendMail($email, $message);
        }

        return $response;
    }
}
