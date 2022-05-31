<?php

declare(strict_types=1);

namespace GCLists\Api;

use GCLists\Database\Models\Message;
use WP_REST_Response;
use WP_REST_Request;

class Messages extends BaseEndpoint
{
    protected static $instance;

    public static function getInstance(): Messages
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function register()
    {
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
    }

    public function hasPermission(): bool
    {
        return true; // current_user_can('delete_posts');
    }

    public function registerRestRoutes()
    {
        register_rest_route($this->namespace, '/messages', [
            'methods'             => 'GET',
            'callback'            => [$this, 'all'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages/sent', [
            'methods'             => 'GET',
            'callback'            => [$this, 'sent'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)', [
            'methods'             => 'GET',
            'callback'            => [$this, 'get'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)/versions', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getVersions'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)/sent', [
            'methods'             => 'GET',
            'callback'            => [$this, 'getSentVersions'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages', [
            'methods'             => 'POST',
            'callback'            => [$this, 'create'],
            'permission_callback' => function () {
                return $this->hasPermission();
            },
            'args' => [
                'name' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Body of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_textarea_field($value);
                    }
                ],
                'message_type' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Type of message',
                    'validate_callback' => function ($value, $request, $param) {
                        return in_array($value, ['email', 'phone']);
                    }
                ]
            ]
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)', [
            'methods'             => 'PUT',
            'callback'            => [$this, 'update'],
            'permission_callback' => function () {
                return $this->hasPermission();
            },
            'args' => [
                'name' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Body of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_textarea_field($value);
                    }
                ]
            ]
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)', [
            'methods'             => 'DELETE',
            'callback'            => [$this, 'delete'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);
    }

    /**
     * Get all Message templates
     *
     * @param  WP_REST_Request  $request
     * @return WP_REST_Response
     */
    public function all(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $results = Message::templates($options);

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get sent Messages
     *
     * @param  WP_REST_Request  $request
     * @return WP_REST_Response
     */
    public function sent(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $results = Message::sentMessages($options);

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get a Message (defaults to latest add ?original to get original)
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function get(WP_REST_Request $request): WP_REST_Response
    {
        $params  = $request->get_params();

        if (isset($params['original'])) {
            $message = Message::find($request['id'])->original();

            $response = new WP_REST_Response($message);

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        // Whether we have a version or the original, return the latest version
        $message = Message::find($request['id'])->original()->latest();

        $response = new WP_REST_Response($message);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get versions of a Message template
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function getVersions(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $message = Message::find($request['id']);
        $versions = $message->versions($options);

        $response = new WP_REST_Response($versions);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get sent versions of a Message template
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function getSentVersions(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $message = Message::find($request['id']);
        $versions = $message->sent($options);

        $response = new WP_REST_Response($versions);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Create a Message template
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function create(WP_REST_Request $request): WP_REST_Response
    {
        $message = Message::create([
            'name' => $request['name'],
            'subject' => $request['subject'],
            'body' => $request['body'],
            'message_type' => $request['message_type']
        ])->fresh();

        // @TODO: Probably need to catch exceptions from Model::create()
        $response = new WP_REST_Response($message);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Update a Message
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function update(WP_REST_Request $request): WP_REST_Response
    {
        $message = Message::find($request['id']);

        $message->fill([
            'name' => $request['name'],
            'subject' => $request['subject'],
            'body' => $request['body']
        ]);

        $message->saveVersion();

        $response = new WP_REST_Response($message->latest());

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Delete a Message
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function delete(WP_REST_Request $request): WP_REST_Response
    {
        $message = Message::find($request['id']);
        $message->delete();

        $response = new WP_REST_Response([]);

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Build up an array of valid $options from request params
     *
     * @param  WP_REST_Request  $request
     * @return array
     */
    protected function getOptions(WP_REST_Request $request): array
    {
        $options = [];
        $params  = $request->get_params();

        if (isset($params['limit'])) {
            $options['limit'] = (int)$params['limit'] ?: 5;
        }

        return $options;
    }
}
