<?php

use GCLists\ListManager\Proxy;

beforeAll(function() {
    define('LIST_MANAGER_ENDPOINT', 'https://list-manager.cdssandbox.xyz');
    define('DEFAULT_LIST_MANAGER_API_KEY', 'abcxyz');
});

beforeEach(function() {
    global $wp_rest_server;
    $this->server = $wp_rest_server = new \WP_REST_Server();
    $this->baseRoute = 'gc-lists/messages';

    global $wpdb;
    $this->tableName = $wpdb->prefix . "messages";

    do_action( 'rest_api_init' );
});

test('getInstance', function() {
    $messages = Proxy::getInstance();
    $this->assertInstanceOf(Proxy::class, $messages);
})->group('proxy');

test('ListManager Proxy API endpoint is registered', function () {
    $routes = $this->server->get_routes();
    expect($routes)
        ->toBeArray()
        ->toHaveKey('/list-manager');
})->group('proxy');

test('ListManager proxy applies Authorization header and proxies request', function() {
    // Prepare a user to make the request
    $user_id = $this->factory->user->create();
    $user = wp_set_current_user( $user_id );

    // Make sure the user has permissions
    $user->add_cap('list_manager_bulk_send');

    // intercept/mock the HTTP proxy request
    add_filter( 'pre_http_request', function($preempt, $parsed_args, $url) {

        // Proxy applies Authorization headers to outgoing request
        expect($parsed_args['headers'])
            ->toBeArray()
            ->toHaveKey('Authorization', 'abcxyz');

        // Proxy to list-manager url
        expect($url)->toContain('https://list-manager.cdssandbox.xyz/lists');

        return [
            'headers'     => [],
            'cookies'     => [],
            'filename'    => null,
            'response'    => [
                "code" => 200
            ],
            'status_code' => 200,
            'success'     => 1,
            'body'        => '{"status": "OK"}',
        ];
    }, 10, 3 );

    $request = new WP_REST_Request( 'GET', '/list-manager/lists' );

    $this->server->dispatch( $request );
})->group('proxy');

test('ListManager proxy authenticates user permission (failure)', function() {
    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );

    $request  = new WP_REST_Request( 'GET', '/list-manager/lists' );
    $response = $this->server->dispatch( $request );

    expect($response->get_status())->toBe(403);
})->group('proxy');

test('ListManager proxy authenticates user permission (pass)', function() {
    // intercept the outgoing request
    add_filter( 'pre_http_request', function($preempt, $parsed_args, $url) {
        if(str_contains($url, 'https://list-manager.cdssandbox.xyz')) {
            return [
                'headers'     => [],
                'cookies'     => [],
                'filename'    => null,
                'response'    => [
                    "code" => 200
                ],
                'status_code' => 200,
                'success'     => 1,
                'body'        => '{"status": "OK"}',
            ];
        }
    }, 10, 3 );

    $user_id = $this->factory->user->create();
    $user = wp_set_current_user( $user_id );

    $user->add_cap('list_manager_bulk_send');

    $request  = new WP_REST_Request( 'GET', '/list-manager/lists' );
    $response = $this->server->dispatch( $request );

    expect($response->get_status())->toBe(403);
})->group('proxy');
