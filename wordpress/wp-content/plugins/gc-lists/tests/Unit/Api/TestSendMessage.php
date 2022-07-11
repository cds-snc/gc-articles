<?php

use GCLists\Api\SendMessage;
use function Pest\Faker\faker;

beforeAll(function () {
    add_option('NOTIFY_API_KEY', 'its-a-key-mario');
    add_option('NOTIFY_GENERIC_TEMPLATE_ID', 'world-1-1');

    putenv('DEFAULT_NOTIFY_API_KEY=the-luigi-key');

    if (!defined('DEFAULT_NOTIFY_PHONE_TEMPLATE')) {
        define('DEFAULT_NOTIFY_PHONE_TEMPLATE', 'world-2-2');
    }
});

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
    $message_status = 'email';

    $proxy_response = SendMessage::handle($listId, $subject, $body, $message_status);

    $expected = (object) [
        'status' => 'OK'
    ];

    $this->assertInstanceOf('WP_REST_Response', $proxy_response);
    $this->assertEquals( $proxy_response->get_data(), $expected );
});

# Note, options/env vars are being set at the top of the file
test('getNotifyIdsFromType for an email type', function() {
    [$notify_api_key, $notify_template_id] = SendMessage::getNotifyIdsFromType('email');
    $this->assertEquals($notify_api_key, 'its-a-key-mario');
    $this->assertEquals($notify_template_id, 'world-1-1');
});

test('getNotifyIdsFromType for an phone type', function() {
    [$notify_api_key, $notify_template_id] = SendMessage::getNotifyIdsFromType('phone');
    $this->assertEquals($notify_api_key, 'the-luigi-key');
    $this->assertEquals($notify_template_id, 'world-2-2');
});

test('getNotifyIdsFromType for a random string', function() {
    [$notify_api_key, $notify_template_id] = SendMessage::getNotifyIdsFromType('fax machine');
    $this->assertEquals($notify_api_key, 'its-a-key-mario');
    $this->assertEquals($notify_template_id, 'world-1-1');
});
