<?php

declare(strict_types=1);

namespace CDS\Modules\Subscribe;

class Setup
{
    public function __construct()
    {
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

    public function confirmSubscription()
    {
        if (!isset($_POST['list_manager'])) {
            return json_encode(["error" => "missing data"]);
        }

        if (!wp_verify_nonce($_POST['list_manager'], 'list_manager_nonce_action')) {
            return json_encode(["error" => "failed to verify"]);
        }

        if (isset($_POST['list_manager'])) {
            // @todo send to list manager API
            return json_encode(["success" => "all good"]);
        }

    }
}
