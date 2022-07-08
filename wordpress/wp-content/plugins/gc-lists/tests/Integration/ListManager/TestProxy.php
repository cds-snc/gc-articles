<?php

use GCLists\ListManager\Proxy;

test('ListManager Proxy request', function() {
    define('LIST_MANAGER_ENDPOINT', 'https://list-manager.cdssandbox.xyz');
    define('DEFAULT_LIST_MANAGER_API_KEY', 'abcxyz');

    // intercept/mock the HTTP proxy request
    add_filter( 'pre_http_request', function() {
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

    $proxy = Proxy::getInstance();

    $request = new WP_REST_Request( 'GET', '/list-manager/lists' );

    $response = $proxy->proxyRequest($request);

    $expected = (object) [
        'status' => 'OK'
    ];

    $this->assertEquals($response->get_data(), $expected);
});
