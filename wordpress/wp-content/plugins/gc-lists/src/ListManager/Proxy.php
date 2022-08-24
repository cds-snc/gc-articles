<?php

declare(strict_types=1);

namespace GCLists\ListManager;

use WP_REST_Request;
use WP_REST_Response;

class Proxy
{
    protected static $instance;

    public static function getInstance(): Proxy
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

    /**
     * Route to proxy requests to list-manager
     */
    public function registerRestRoutes()
    {
        register_rest_route('list-manager', '/(?P<endpoint>[a-z0-9\-_/]*)', [
            'methods'             => \WP_REST_Server::ALLMETHODS,
            'callback'            => [$this, 'proxyRequest'],
            'permission_callback' => function () {
                return current_user_can('list_manager_bulk_send');
            }
        ]);
    }

    /**
     * Local WP Rest proxy for list-manager requests.
     * Adds authorization header before forwarding the request.
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function proxyRequest(WP_REST_Request $request): WP_REST_Response
    {
        $endpoint = $request['endpoint'];
        $body = $request->get_body();
        $url = LIST_MANAGER_ENDPOINT . '/' . $endpoint;

        $args = [
            'method' => $request->get_method(),
            'headers' => [
                'Authorization' => DEFAULT_LIST_MANAGER_API_KEY,
                'Content-Type' => 'application/json'
            ],
            'body' => $body,
        ];

        // Proxy request to list-manager
        $proxy_response = wp_remote_request($url, $args);
        // Retrieve information
        $response_code = $proxy_response["response"]["code"];
        $response_body = $proxy_response["body"];

        // Return response and code
        $response = new WP_REST_Response(json_decode($response_body));
        $response->set_status($response_code);

        return $response;
    }
}
