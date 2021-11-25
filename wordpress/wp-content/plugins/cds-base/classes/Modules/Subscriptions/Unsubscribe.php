<?php

declare(strict_types=1);

namespace CDS\Modules\Subscriptions;

class Unsubscribe
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            /*
             * GET /lists/unsubscribe/{subscription_id}
             */
            register_rest_route('lists', '/unsubscribe/(?P<subscription_id>[^/]+)', [
                'methods'  => 'GET',
                'callback' => [$this, 'unsubscribe'],
            ]);

            /*
             * POST /lists/unsubscribe
             * Params: email, form_id
             */
            register_rest_route('lists', '/unsubscribe', [
                'methods'  => 'POST',
                'callback' => [$this, 'unsubscribeByEmail'],
            ]);
        });
    }

    public function unsubscribe($data): WP_REST_Response
    {
        $subscription_id = $data['subscription_id'];

        if ($this->doUnsubscribe($subscription_id)) {
            $response = new WP_REST_Response([
                'status' => 'Success',
            ]);

            $response->set_status(200);

            return $response;
        }

        $response = new WP_REST_Response([
            'status' => 'Not found'
        ]);

        $response->set_status(404);

        return $response;
    }

    public function doUnsubscribe($subscription_id): bool
    {
        global $wpdb;

        $count = $wpdb->query(
            $wpdb->prepare(
                "
                DELETE from {$wpdb->prefix}wpforms_entries
                WHERE subscription_id = %s
            ",
                $subscription_id,
            ),
        );

        return $count > 0;
    }

    public function unsubscribeByEmail($data): WP_REST_Response
    {
        // Validate the request contains email and form_id
        if ($errors = $this->validateUnsubscribeRequest($data)) {
            $response = new WP_REST_Response([
                'errors' => $errors
            ]);

            $response->set_status(400);

            return $response;
        }

        $email   = $data['email'];
        $form_id = $data['form_id'];

        global $wpdb;

        $count = $wpdb->query(
            $wpdb->prepare(
                "
                DELETE from {$wpdb->prefix}wpforms_entries
                WHERE JSON_SEARCH(fields, 'one', %s)
                AND form_id = %s
            ",
                $email,
                $form_id
            ),
        );

        if ($count) {
            return new WP_REST_Response([
                'status'  => 'Success',
                'message' => 'You have been unsubscribed'
            ]);
        }

        $response = new WP_REST_Response([
            'status' => 'Not found'
        ]);

        $response->set_status(404);

        return $response;
    }

    public function validateUnsubscribeRequest($data): array
    {
        $errors = [];

        if (! isset($data['email'])) {
            array_push($errors, 'Email required');
        }

        if (! isset($data['form_id'])) {
            array_push($errors, 'Form ID required');
        }

        return $errors;
    }
}
