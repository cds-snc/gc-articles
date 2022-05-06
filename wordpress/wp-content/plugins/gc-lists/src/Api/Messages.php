<?php

declare(strict_types=1);

namespace GCLists\Api;

use WP_REST_Response;

class Messages
{
    protected $wpdb;
    protected string $namespace;
    protected string $tableName;
    protected static $instance;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $wpdb->prefix . "messages";

        $this->namespace = "gc-lists";
    }

    public static function getInstance(): Messages
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function hasPermission()
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

    public function all()
    {
        $results = $this->wpdb->get_results(
            "SELECT * FROM {$this->tableName} WHERE original_message_id IS NULL"
        );

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    public function sent()
    {
        $results = $this->wpdb->get_results(
            "SELECT * FROM {$this->tableName} WHERE original_message_id IS NOT NULL"
        );

        $response = new WP_REST_Response($results);

        $response->set_status(200);

        return $response;
    }

    public function get($id)
    {
        $response = new WP_REST_Response([]);

        $response->set_status(200);

        return $response;
    }

    public function create()
    {
        $response = new WP_REST_Response([]);

        $response->set_status(200);

        return $response;
    }

    public function update($id)
    {
        $response = new WP_REST_Response([]);

        $response->set_status(200);

        return $response;
    }

    public function delete($id)
    {
        $response = new WP_REST_Response([]);

        $response->set_status(200);

        return $response;
    }
}
