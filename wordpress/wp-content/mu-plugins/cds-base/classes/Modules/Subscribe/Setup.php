<?php

declare(strict_types=1);

namespace CDS\Modules\Subscribe;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Setup
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'subscribe']);

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
    }

    public function subscribe()
    {
        wp_enqueue_script('cds-subscribe', plugin_dir_url(__FILE__) . '/src/handler.js', ['jquery'], "1.0.0", true);

        wp_localize_script("cds-subscribe", "CDS_VARS", array(
            "rest_url" => esc_url_raw(rest_url()),
            "rest_nonce" => wp_create_nonce("wp_rest"),
        ));
    }

    public function confirmSubscription()
    {
        if (!isset($_POST['list_manager'])) {
            return json_encode(["error" => "missing data"]);
        }

        if (!wp_verify_nonce($_POST['list_manager'], 'list_manager_nonce_action')) {
            return json_encode(["error" => __("failed to verify", "cds-snc")]);
        }

        if (!isset($_POST["email"]) || $_POST["email"] === "") {
            return json_encode(["error" => __("invalid email", "cds-snc")]);
        }

        $email = $_POST["email"];

        try {
            $client = new Client([
                'headers' => [
                    "Authorization" => getenv('LIST_MANAGER_API_KEY')
                ]
            ]);

            $endpoint = getenv('LIST_MANAGER_ENDPOINT');

            $response = $client->request('POST', $endpoint . '/subscription', [
                'json' => [
                    "email" => $email,
                    "list_id" => 'ce14a753-904e-450a-a70c-808d6d69e05c'
                ]
            ]);

            return json_encode(["success" => __("all good your on the list", "cds-snc")]);

        } catch (Exception $exception) {
            return json_encode(["error" => __("api call failed", "cds-snc")]);
        }


    }
}
