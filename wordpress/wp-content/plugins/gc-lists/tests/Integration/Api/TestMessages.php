<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

use Carbon\Carbon;
use GCLists\Api\Messages;
use GCLists\Database\Models\Message;
use function Pest\Faker\faker;

beforeEach(function() {
	global $wp_rest_server;
	$this->server = $wp_rest_server = new \WP_REST_Server();
	$this->baseRoute = 'gc-lists/messages';

	global $wpdb;
	$this->tableName = $wpdb->prefix . "messages";

	do_action( 'rest_api_init' );

    $this->messageAttributes = [
        'id',
        'name',
        'subject',
        'body',
        'message_type',
        'sent_at',
        'sent_to_list_name',
        'sent_by_email',
        'original_message_id',
        'version_id',
        'created_at',
        'updated_at'
    ];
});

test('getInstance', function() {
    $messages = Messages::getInstance();
    $this->assertInstanceOf(Messages::class, $messages);
});

test('Get all Message templates', function() {
	$this->factory->message->create_many(5);

	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
	$this->assertCount(5, $response->get_data());

    $body = json_encode($response->get_data());

    expect($body)
        ->toBeJson()
        ->json()
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys($this->messageAttributes);
})->group('test');

test('Get all templates with limit', function() {
    $this->factory->message->create_many(20);

    $request  = new WP_REST_Request( 'GET', '/gc-lists/messages' );
    $request->set_query_params([
        'limit' => 5,
    ]);
    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toHaveCount(5)
        ->each
        ->toBeArray()
        ->toHaveKeys($this->messageAttributes);
})->group('test');

test('Get all templates with invalid limit returns default 5', function() {
    $this->factory->message->create_many(20);

    $request  = new WP_REST_Request( 'GET', '/gc-lists/messages' );
    $request->set_query_params([
        'limit' => 'xxx',
    ]);
    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toHaveCount(5);
})->group('test');

test('Get all sent messages', function() {
    // Create some templates and versions
	$template = $this->factory->message->create_and_get();

	$this->factory->message->create_many(5, [
		'original_message_id' => $template->id,
        'sent_at' => Carbon::now()->timestamp
	]);

    $template2 = $this->factory->message->create_and_get();

    $this->factory->message->create_many(8, [
        'original_message_id' => $template2->id,
        'sent_at' => Carbon::now()->timestamp
    ]);

	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages/sent' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );

	$body = json_encode($response->get_data());

	expect($body)
        ->json()
        ->toHaveCount(13)
        ->each
        ->toBeArray()
        ->toHaveKeys($this->messageAttributes);
});

test('Get a message', function() {
	$message = $this->factory->message->create_and_get([
		'name' => 'This is the message name'
	]);

	$request  = new WP_REST_Request( 'GET', "/gc-lists/messages/{$message->id}" );
	$response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = $response->get_data()->toJson();

    expect($body)
        ->json()
        ->toHaveKeys($this->messageAttributes);

    $this->assertEquals('This is the message name', $message->name);
});

test('Get a message returns the latest version if there are versions', function() {
    $message = $this->factory->message->create_and_get([
        'name' => 'This is the message name'
    ]);

    // Create some versions
    $versions = $this->factory->message->create_many(5, [
        'original_message_id' => $message->id
    ]);

    $version_id = collect($versions)->random();

    $request  = new WP_REST_Request( 'GET', "/gc-lists/messages/{$version_id}" );
    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = $response->get_data()->toJson();

    expect($body)
        ->json()
        ->toHaveKeys($this->messageAttributes)
        ->toHaveKey('original_message_id', $message->id);
});

test('Get a message adding `original` query param returns the original version', function() {
    $message_id = $this->factory->message->create();

    // Create some versions
    $this->factory->message->create_many(5, [
        'original_message_id' => $message_id
    ]);

    $request  = new WP_REST_Request( 'GET', "/gc-lists/messages/{$message_id}" );
    $request->set_query_params([
        'original' => true,
    ]);
    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = $response->get_data()->toJson();

    expect($body)
        ->json()
        ->toHaveKeys($this->messageAttributes)
        ->toHaveKey('id', $message_id);
});

test('Get versions of a message', function() {
    $message_id = $this->factory->message->create([
        'name' => 'This is the message name'
    ]);

    // Generate 5 versions, odd = sent (5)
    for($version_id = 1; $version_id <= 10; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? $timestamp : NULL)
        ]);
    }

    $request = new WP_REST_Request('GET', "/gc-lists/messages/{$message_id}/versions");
    $request->set_query_params([
        'limit' => 5,
    ]);
    $response = $this->server->dispatch($request);

    $this->assertEquals(200, $response->get_status());

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toBeArray()
        ->toHaveCount(5)
        ->each
        ->toHaveKeys($this->messageAttributes);
})->group('test');

test('No versions available', function() {
    $message_id = $this->factory->message->create([
        'name' => 'This is the message name'
    ]);

    $request = new WP_REST_Request('GET', "/gc-lists/messages/{$message_id}/versions");
    $response = $this->server->dispatch($request);

    $this->assertEquals(200, $response->get_status());

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toBeArray()
        ->toBeEmpty();
});

test('Get sent versions of a message', function() {
    $message_id = $this->factory->message->create([
        'name' => 'This is the message name'
    ]);

    // Generate 5 versions, odd = sent (5)
    for($version_id = 1; $version_id <= 10; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? $timestamp : NULL)
        ]);
    }

    $request = new WP_REST_Request('GET', "/gc-lists/messages/{$message_id}/sent");
    $request->set_query_params([
        'limit' => 4,
    ]);
    $response = $this->server->dispatch($request);

    $this->assertEquals(200, $response->get_status());

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toBeArray()
        ->toHaveCount(4)
        ->each
        ->toHaveKeys($this->messageAttributes)
        ->toHaveKey('original_message_id', $message_id)
        ->toHaveKey('sent_at', $timestamp);
});

test('No sent versions available', function() {
    $message_id = $this->factory->message->create([
        'name' => 'This is the message name'
    ]);

    $this->factory->message->create_many(5, [
        'original_message_id' => $message_id
    ]);

    $request = new WP_REST_Request('GET', "/gc-lists/messages/{$message_id}/sent");
    $response = $this->server->dispatch($request);

    $this->assertEquals(200, $response->get_status());

    $body = json_encode($response->get_data());

    expect($body)
        ->json()
        ->toBeArray()
        ->toBeEmpty();
});

test('Create a message', function() {
	$request  = new WP_REST_Request( 'POST', '/gc-lists/messages' );
	$request->set_query_params([
		'name' => 'Name of the message',
		'subject' => 'Subject of the message',
		'body' => 'Body of the message',
		'message_type' => 'email'
	]);

	$response = $this->server->dispatch( $request );

	$this->assertEquals(200, $response->get_status());

	$body = $response->get_data()->toJson();

	expect($body)
        ->json()
        ->toHaveKey('name', 'Name of the message');
});

test('Create a message with Validation errors', function() {
    $request  = new WP_REST_Request( 'POST', '/gc-lists/messages' );
    $request->set_query_params([
        'subject' => 'Subject of the message',
        'message_type' => 'email'
    ]);

    $response = $this->server->dispatch( $request );

    $body = $response->get_data();

    $this->assertEquals(400, $response->get_status());

    // missing name, body
    expect($body)
        ->toBeArray()
        ->toHaveKey('code', 'rest_missing_callback_param')
        ->toHaveKey('data.status', 400)
        ->toHaveKey('data.params', ['name', 'body']);


    // Again with no missing params but invalid message_type
    $request->set_query_params([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message',
        'message_type' => 'xx'
    ]);

    $response = $this->server->dispatch( $request );

    $body = $response->get_data();

    $this->assertEquals(400, $response->get_status());

    // missing name, body
    expect($body)
        ->toBeArray()
        ->toHaveKey('code', 'rest_invalid_param')
        ->toHaveKey('data.status', 400)
        ->toHaveKey('data.params.message_type');
});

test('Update a message creates a new version', function() {
	$message = $this->factory->message->create_and_get([
		'name' => 'This is the original message name'
	]);

	$request  = new WP_REST_Request( 'PUT', "/gc-lists/messages/{$message->id}" );
	$request->set_query_params([
		'id' => $message->id,
		'name' => 'Name of the new version of the message',
		'subject' => 'Subject of the new message',
		'body' => 'Body of the new message',
	]);

	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );

	$body = $response->get_data()->toJson();

	expect($body)
        ->json()
        ->toHaveKey('name', 'Name of the new version of the message')
        ->toHaveKey('subject', 'Subject of the new message')
        ->toHaveKey('body', 'Body of the new message')
        ->toHaveKey('original_message_id', $message->id);
});

test('Delete a message', function() {
	$message_ids = $this->factory->message->create_many(5);

	global $wpdb;
	$count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
	$this->assertEquals(5, $count);

	$request  = new WP_REST_Request( 'DELETE', "/gc-lists/messages/{$message_ids[2]}" );
	$response = $this->server->dispatch( $request );

	// After deleting one, there should be four left
	$this->assertEquals( 200, $response->get_status() );
	$count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
	$this->assertEquals(4, $count);
});

test('Send an existing message', function() {
    $message_id = $this->factory->message->create([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz'
    ]);
    $list_id = faker()->uuid();

    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );

    $request  = new WP_REST_Request( 'POST', "/gc-lists/messages/{$message_id}/send" );
    $request->set_query_params([
        'name' => 'Foo sent',
        'subject' => 'Bar sent',
        'body' => 'Baz sent',
        'sent_to_list_id' => $list_id,
        'sent_to_list_name' => 'The list',
    ]);

    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = $response->get_data()->toJson();

    expect($body)
        ->json()
        ->toHaveKey('name', 'Foo sent')
        ->toHaveKey('subject', 'Bar sent')
        ->toHaveKey('body', 'Baz sent')
        ->toHaveKey('original_message_id', $message_id);
});

test('Send a message directly from input', function() {
    $list_id = faker()->uuid();
    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );

    $request  = new WP_REST_Request( 'POST', "/gc-lists/messages/send" );
    $request->set_query_params([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz',
        'message_type' => 'email',
        'sent_to_list_id' => $list_id,
        'sent_to_list_name' => 'The list',
    ]);

    $response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );

    $body = $response->get_data()->toJson();

    expect($body)
        ->json()
        ->toHaveKey('name', 'Foo')
        ->toHaveKey('subject', 'Bar')
        ->toHaveKey('body', 'Baz')
        ->toHaveKey('original_message_id', NULL);
});
