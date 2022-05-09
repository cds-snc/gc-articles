<?php

declare(strict_types=1);

namespace GCLists\Api;

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

        register_rest_route($this->namespace, '/messages', [
            'methods'             => 'POST',
            'callback'            => [$this, 'create'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);

        register_rest_route($this->namespace, '/messages/(?P<id>[\d]+)', [
            'methods'             => 'PUT',
            'callback'            => [$this, 'update'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
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
     * @return WP_REST_Response
     */
    public function all(): WP_REST_Response
    {
        // @TODO: revisit the SELECT * on all these queries
        $results = $this->wpdb->get_results(
            "SELECT * FROM {$this->tableName} WHERE original_message_id IS NULL"
        );

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    /**
     * Get sent Messages
     *
     * @return WP_REST_Response
     */
    public function sent(): WP_REST_Response
    {
        $results = $this->wpdb->get_results(
            "SELECT * FROM {$this->tableName} WHERE original_message_id IS NOT NULL"
        );

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    /**
     * Get a Message
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function get(WP_REST_Request $request): WP_REST_Response
    {
        $results = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id = %d", $request["id"])
        );

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    /**
     * Create a Message
     *
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function create(WP_REST_Request $request): WP_REST_Response
    {
        // @TODO: data validation and sanitation
        $result = $this->wpdb->insert(
            $this->tableName,
            [
                'name' => $request['name'],
                'subject' => $request['subject'],
                'body' => $request['body'],
                'message_type' => $request['message_type']
            ]
        );

        if ($result) {
            $id = $this->wpdb->insert_id;
            $message = $this->wpdb->get_row(
                $this->wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id = %d", $id)
            );

            $response = new WP_REST_Response($message);

            $response->set_status(200);

            return $response;
        }

        $response = new WP_REST_Response([
            'error' => 'There was an unspecified error'
        ]);

        $response->set_status(500);

        return $response;
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
        $result = $this->wpdb->update(
            $this->tableName,
            [
                'name' => $request['name'],
                'subject' => $request['subject'],
                'body' => $request['body']
            ],
            [
                'id' => $request['id']
            ]
        );

        $message = $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id = %d", $request['id'])
        );

        $response = new WP_REST_Response($message);

        $response->set_status(200);

        return $response;
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
        $result = $this->wpdb->delete($this->tableName, ['id' => $request['id']]);

        if ($result) {
            $response = new WP_REST_Response([]);

            $response->set_status(200);

            return $response;
        }

        $response = new WP_REST_Response([
            'error' => 'There has been an unspecified error'
        ]);

        $response->set_status(500);

        return $response;
    }
}
