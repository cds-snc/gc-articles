<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

class Setup
{
    protected string $redirect = '';

    public function __construct()
    {
        /*
         * Note - if testing with WP ENV
         * https://wordpress.org/support/topic/wp-env-with-gutenber-doesnt-have-a-rest-api/
         */
        add_action('rest_api_init', function () {
            $subscribe = new Subscribe();

            register_rest_route('subscribe/v1', '/process/', [
                'methods' => 'POST',
                'callback' => [$subscribe, 'confirmSubscription'],
                'permission_callback' => function () {
                    return '';
                }
            ]);
        });

        new SubscriptionForm();
    }
}
