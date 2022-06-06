<?php

use GCLists\Api\SendMessage;
use function Pest\Faker\faker;

beforeAll(function () {
    WP_Mock::setUp();
});

afterAll(function () {
    WP_Mock::tearDown();
});

test('Send a message', function() {
    $response = array(
        'body' => '{"status": "OK"}',
        'response' => array(
            'code' => 200,
            'message' => 'OK',
        ),
        'cookies' => array(),
        'filename' => null,
    );

    $url = getenv('LIST_MANAGER_ENDPOINT') . '/send';
    $listId = faker()->uuid();
    $subject = 'This is my subject';
    $body = 'This is my body';

    $args = [
        'method' => 'POST',
        'headers' => [
            'Authorization' => getenv('DEFAULT_LIST_MANAGER_API_KEY'),
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode([
            'job_name' => "gc-lists",
            'list_id' => $listId,
            'personalisation' => json_encode([
                'subject' => $subject,
                'message' => $body,
            ]),
            'template_id' => get_option('NOTIFY_GENERIC_TEMPLATE_ID'),
            'template_type' => "email",
        ]),
    ];

    \WP_Mock::userFunction( 'wp_remote_request', [
        'times' => 1,
        'args' => [$url, $args],
        'return' => $response
    ]);

    $proxy_response = SendMessage::handle($listId, $subject, $body);
    
    $this->assertEquals( $proxy_response, $response );
})->group('send');
