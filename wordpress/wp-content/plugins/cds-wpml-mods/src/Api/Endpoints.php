<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

use WP_REST_Request;

class Endpoints extends BaseEndpoint
{
    protected static $instance;

    public static function getInstance(): Endpoints
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
        // Get available pages by language
        register_rest_route($this->namespace, '/pages/' . '(?P<language>en|fr)', [
            'methods'             => 'GET',
            'callback'            => [$this, 'all'],
            'permission_callback' => function () {
                return $this->hasPermission();
            }
        ]);
    }

    public function all(WP_REST_Request $request)
    {
        return $request['language'];
    }
}
