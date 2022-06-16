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
            'args'                => [
                'name'         => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject'      => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body'         => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Body of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_textarea_field($value);
                    }
                ],
                'message_type' => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Type of message',
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
            'args'                => [
                'name'    => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject' => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body'    => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Body of the Message',
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

        // create and send new message
        register_rest_route($this->namespace, '/messages/send', [
            'methods'             => 'POST',
            'callback'            => [$this, 'createAndSend'],
            'permission_callback' => function () {
                return $this->hasPermission();
            },
            'args'                => [
                'name'              => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject'           => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body'              => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Body of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_textarea_field($value);
                    }
                ],
                'message_type'      => [
                    'required'          => true,
                    'type'              => 'string',
                    'description'       => 'Type of message',
                    'validate_callback' => function ($value, $request, $param) {
                        return in_array($value, ['email', 'phone']);
                    }
                ],
                'sent_to_list_id'   => [
                    'required'    => true,
                    'type'        => 'string',
                    'description' => 'ID of the list'
                ],
                'sent_to_list_name' => [
                    'required'    => true,
                    'type'        => 'string',
                    'description' => 'Name of the list'
                ],
            ]
        ]);

        // send existing message
        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)/send', [
            'methods'             => 'POST',
            'callback'            => [$this, 'send'],
            'permission_callback' => function () {
                return $this->hasPermission();
            },
            'args'                => [
                'name'              => [
                    'required'          => false,
                    'type'              => 'string',
                    'description'       => 'Name of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'subject'           => [
                    'required'          => false,
                    'type'              => 'string',
                    'description'       => 'Subject of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_text_field($value);
                    }
                ],
                'body'              => [
                    'required'          => false,
                    'type'              => 'string',
                    'description'       => 'Body of the Message',
                    'sanitize_callback' => function ($value, $request, $param) {
                        return sanitize_textarea_field($value);
                    }
                ],
                'sent_to_list_id'   => [
                    'required'    => true,
                    'type'        => 'string',
                    'description' => 'ID of the list'
                ],
                'sent_to_list_name' => [
                    'required'    => true,
                    'type'        => 'string',
                    'description' => 'Name of the list'
                ],
            ]
        ]);
    }

    /**
     * Get all Message templates
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function all(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $results = Message::templates($options);

        $response = new WP_REST_Response($results->toArray());

        $response->set_status(200);

        return rest_ensure_response($response);
    }

    /**
     * Get sent Messages
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function sent(WP_REST_Request $request): WP_REST_Response
    {
        $options = $this->getOptions($request);

        $results = Message::sentMessages($options);

        $response = new WP_REST_Response($results->toArray());

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
        $params = $request->get_params();

        if (isset($params['original'])) {
            $message = Message::find($request['id'])->original();

            $response = new WP_REST_Response($message);

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        if (isset($params['latest'])) {
            $message = Message::find($request['id'])->original()->latest();

            $response = new WP_REST_Response($message);

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        $message = Message::find($request['id']);

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

        $message  = Message::find($request['id']);
        $versions = $message->versions($options);

        $response = new WP_REST_Response($versions->toArray());

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

        $message  = Message::find($request['id']);
        $versions = $message->sent($options);

        $response = new WP_REST_Response($versions->toArray());

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
            'name'         => $request['name'],
            'subject'      => $request['subject'],
            'body'         => $request['body'],
            'message_type' => $request['message_type']
        ]);

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

        // If this was a sent message, create a new draft
        if ($message->sent_at) {
            $draft = Message::create([
                'name'         => $request['name'],
                'subject'      => $request['subject'],
                'body'         => $request['body'],
                'message_type' => $message->message_type
            ]);

            $response = new WP_REST_Response($draft->fresh());

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        // If it's a draft, just save over the original
        $message->fill([
            'name'    => $request['name'],
            'subject' => $request['subject'],
            'body'    => $request['body']
        ]);

        $message->save();

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
     * Create and send a message immediately
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function createAndSend(WP_REST_Request $request): WP_REST_Response
    {
        $response = SendMessage::handle($request['sent_to_list_id'], $request['subject'], $request['body'])->data;

        if (isset($response->status) && $response->status === 'OK') {
            $current_user = wp_get_current_user();

            $message = new Message([
                'name'         => $request['name'],
                'subject'      => $request['subject'],
                'body'         => $request['body'],
                'message_type' => $request['message_type']
            ]);

            $message = $message->send(
                $request['sent_to_list_id'],
                $request['sent_to_list_name'],
                $current_user->ID,
                $current_user->user_email
            );

            $response = new WP_REST_Response($message);

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        // @TODO: should better handle errors coming back from list-manager api
        $response = new WP_REST_Response([
            "error" => "There was an error sending the message"
        ]);

        $response->set_status(500);

        return rest_ensure_response($response);
    }

    /**
     * Send an existing message
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function send(WP_REST_Request $request): WP_REST_Response
    {
        $response = SendMessage::handle($request['sent_to_list_id'], $request['subject'], $request['body'])->data;

        if (isset($response->status) && $response->status === 'OK') {
            $current_user = wp_get_current_user();
            $message      = Message::find($request['id']);

            $message->fill([
                'name'    => $request['name'] ?: $message->name,
                'subject' => $request['subject'] ?: $message->subject,
                'body'    => $request['body'] ?: $message->body,
            ]);

            $message = $message->send(
                $request['sent_to_list_id'],
                $request['sent_to_list_name'],
                $current_user->ID,
                $current_user->user_email
            );

            // Return the sent version
            $response = new WP_REST_Response($message->sent()->last());

            $response->set_status(200);

            return rest_ensure_response($response);
        }

        // @TODO: should better handle errors coming back from list-manager api
        $response = new WP_REST_Response([
            "error"   => "There was an error sending the message",
            "details" => $response
        ]);

        $response->set_status(500);

        return rest_ensure_response($response);
    }

    /**
     * Build up an array of valid $options from request params
     *
     * @param  WP_REST_Request  $request
     *
     * @return array
     */
    public function getOptions(WP_REST_Request $request): array
    {
        $options = [];
        $params  = $request->get_params();

        if (isset($params['limit'])) {
            $options['limit'] = (int)$params['limit'] ?: 5;
        }

        // asc or desc will sortBy created_at
        if (isset($params['sort']) && in_array($params['sort'], ['asc', 'desc'])) {
            $options['sort'] = $params['sort'];
        }

        return $options;
    }
}
