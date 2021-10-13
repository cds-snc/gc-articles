<?php

declare(strict_types=1);

namespace CDS\Modules\Subscribe;

use CDS\Modules\Subscribe\Block;
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
            register_rest_route('subscribe/v1', '/process/', [
                'methods' => 'POST',
                'callback' => [$this, 'confirmSubscription'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        new SubscriptionForm();
        new Block();
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-subscribe-js', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);

        wp_localize_script("cds-subscribe-js", "CDS_VARS", array(
            "rest_url" => esc_url_raw(rest_url()),
            "rest_nonce" => wp_create_nonce("wp_rest"),
        ));
    }

    protected function isJson($string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function handleException($e): string
    {
        $exception = (string)$e->getResponse()->getBody();

        error_log($exception);

        if ($this->isJson($exception)) {
            $exceptions = json_decode($exception);

            $errors = "";

            foreach ($exceptions->detail as $error) {
                $errors = $errors . $error->loc[1] . ': ' . $error->msg . '<br>';
            }

            return $errors;
        }

        return __("Internal server error", "cds-snc");
    }

    protected function subscribe(string $email): array
    {
        try {
            $client = new Client([
                'headers' => [
                    "Authorization" => getenv('LIST_MANAGER_API_KEY')
                ]
            ]);

            $endpoint = getenv('LIST_MANAGER_ENDPOINT');

            $client->request('POST', $endpoint . '/subscription', [
                'json' => [
                    "email" => $email,
                    "list_id" => '0c188973-efab-4e8e-8b09-b165e98c66cf',
                    "service_api_key" => getenv('NOTIFY_API_KEY')
                ]
            ]);

            return ["success" => __("Thanks for subscribing", "cds-snc")];
        } catch (ClientException $exception) {
            $error = $this->handleException($exception);
            return ["error" => $error];
        } catch (RequestException  $exception) {
            return ["error" => __("Internal server error", "cds-snc")];
        }
    }

    public function confirmSubscription(): string
    {
        if (!isset($_POST['list_manager'])) {
            return json_encode(["error" => __("400 Bad Request", "cds-snc")]);
        }

        if (!wp_verify_nonce($_POST['list_manager'], 'list_manager_nonce_action')) {
            return json_encode(["error" => __("401 Unauthorized", "cds-snc")]);
        }

        if (!isset($_POST["email"]) || $_POST["email"] === "") {
            return json_encode(["error" => __("Please complete the required field to continue", "cds-snc")]);
        }

        return json_encode($this->subscribe($_POST["email"]));
    }
}
