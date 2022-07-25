<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\RequestSite;

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
            register_rest_route('request/v1', '/process', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSend'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);

        new RequestSiteForm();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-url-typer-js', plugin_dir_url(__FILE__) . './src/url-typer.js', ['jquery'], "1.0.0", true);
    }

    protected function removeslashes($str)
    {
        $str = implode("", explode("\\", $str));
        return stripslashes(trim($str));
    }

    public function confirmSend(): array
    {
        $nonceErrorMessage = Utils::isNonceErrorMessage($_POST);
        if ($nonceErrorMessage) {
            return ['error' => true, "error_message" => $nonceErrorMessage];
        }

        $keys_page_1 = ['site', 'usage', 'optional-usage-value', 'target', 'optional-target-value', 'timeline'];
        $keys_page_2 = ['fullname', 'email', 'role', 'department'];
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

        $all_keys = array_merge($keys_page_1, $keys_page_2);
        $message = '';

        foreach ($all_keys as $_key) {
            $value = $_POST[$_key] ?? '';
            if ($value) {
                $value = is_array($value) ? str_replace(".", "", implode(", ", $value)) : $value;
                $message .= sanitize_text_field(ucfirst($_key)) . ': ' . $this->removeslashes(sanitize_text_field($value)) . "\n\n";
            }
        }

        // Add the submitted URL to the message text
        if (isset($_POST['url']) && $_POST['url'] !== '') {
            $message .=
            "\n\n" .
            'URL: ' .
            sanitize_text_field($_POST['url']);
        }

        $site = $_POST['site'] ?? __('No name specified', 'cds-snc');
        $goal = __('Request a site:', 'cds-snc') . ' ' . $this->removeslashes($site);
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];

        $messenger = new Messenger();
        $response = $messenger->createTicket($goal, $fullname, $email, $message);

        $messenger->sendMail('platform-mvp@cds-snc.ca', $message);

        $cc = $_POST['cc'] ?? '';
        if ($cc) {
            $messenger->sendMail($email, $message);
        }

        return $response;
    }
}
