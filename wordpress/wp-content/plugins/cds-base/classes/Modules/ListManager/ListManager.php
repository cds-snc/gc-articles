<?php

declare(strict_types=1);

namespace CDS\Modules\ListManager;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class ListManager
{
    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
    }

    public function enqueue()
    {
        try {
            $path = plugin_dir_path(__FILE__) . 'app/build/asset-manifest.json';
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            $files = $data['files'];

            wp_enqueue_style('list-manager', $files['main.css'], null, '1.0.0');

            wp_enqueue_script(
                'list-manager',
                $files['main.js'],
                null,
                '1.0.0',
                true,
            );

            wp_localize_script('list-manager', 'CDS_LIST_MANAGER', [
                'endpoint' => esc_url_raw(getenv('LIST_MANAGER_ENDPOINT')),
            ]);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * Route to proxy requests to list-manager
     */
    public function registerRestRoutes()
    {
        register_rest_route('list-manager', '/(?P<path>[a-z0-9\-_/]*)', [
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
     * @return WP_Error|WP_REST_Response
     */
    public function proxyRequest(WP_REST_Request $request): WP_Error|WP_REST_Response
    {
        $token = getenv_docker('DEFAULT_LIST_MANAGER_API_KEY', '');
        $base_url = getenv_docker('LIST_MANAGER_ENDPOINT', '');
        $path = $request['path'];
        $body = $request->get_body();
        $url = $base_url . '/' . $path;

        $args = [
            'method' => $request->get_method(),
            'headers' => [
                'Authorization' => "$token",
                'Content-Type' => 'application/json'
            ],
            'body' => $body,
        ];

        // Proxy request to list-manager
        $proxy_response = wp_remote_request($url, $args);

        // Retrieve information
        $response_code = $proxy_response["response"]["code"];
        $response_message = $proxy_response["response"]["message"];
        $response_body = $proxy_response["body"];

        if ($response_code < 400) {
            return new WP_REST_Response(json_decode($response_body));
        } else {
            return new WP_Error($response_code, $response_message, $response_body);
        }
    }
}
