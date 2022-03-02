<?php

declare(strict_types=1);

namespace CDS\Modules\ListManager;

use WP_REST_Request;
use WP_REST_Response;

class ListManager
{
    protected string $listManagerAdminScreenName = 'bulk-send_page_cds_list_manager_app';

    public function __construct()
    {
        //
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

    public function enqueue($hook_suffix)
    {
        if ($hook_suffix == $this->listManagerAdminScreenName) {
            try {
                $path  = plugin_dir_path(__FILE__) . 'app/build/asset-manifest.json';
                $json  = file_get_contents($path);
                $data  = json_decode($json, true);
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
