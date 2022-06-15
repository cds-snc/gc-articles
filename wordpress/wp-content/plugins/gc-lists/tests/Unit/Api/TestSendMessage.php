<?php

use GCLists\Api\SendMessage;
use function Pest\Faker\faker;

test('Send a message', function() {
    // intercept/mock the HTTP proxy request
    add_filter( 'pre_http_request', function() {
        return [
            'headers'     => [],
            'cookies'     => [],
            'filename'    => null,
            'response'    => 200,
            'status_code' => 200,
            'success'     => 1,
            'body'        => '{"status": "OK"}',
        ];
    }, 10, 3 );

    $listId = faker()->uuid();
    $subject = 'This is my subject';
    $body = 'This is my body';
    $proxy_response = SendMessage::handle($listId, $subject, $body);

    $expected = (object) [
        'status' => 'OK'
    ];

    $this->assertInstanceOf('WP_REST_Response', $proxy_response);
    $this->assertEquals( $proxy_response->get_data(), $expected );
});
