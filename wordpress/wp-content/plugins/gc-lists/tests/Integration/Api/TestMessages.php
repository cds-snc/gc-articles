<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;

beforeEach(function() {
	global $wp_rest_server;
	$this->server = $wp_rest_server = new \WP_REST_Server();
	$this->baseRoute = 'gc-lists/messages';

	global $wpdb;
	$this->tableName = $wpdb->prefix . "messages";

	do_action( 'rest_api_init' );
});

test('Get all messages', function() {
	$this->factory->message->create_many(5);

	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
	$this->assertJson($response->get_data());
	$this->assertCount(5, json_decode($response->get_data()));

});

test('Get sent messages', function() {
	$template = $this->factory->message->create_and_get();

	$this->factory->message->create_many(5, [
		'original_message_id' => $template->id,
        'sent_at' => Carbon::now()->timestamp
	]);

	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages/sent' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
	$this->assertJson($response->get_data());
	$this->assertCount(5, json_decode($response->get_data()));
});

test('Get one message', function() {
	$message = $this->factory->message->create_and_get([
		'name' => 'This is the message name'
	]);

	$request  = new WP_REST_Request( 'GET', "/gc-lists/messages/{$message->id}" );
	$response = $this->server->dispatch( $request );

    $this->assertEquals( 200, $response->get_status() );
	$this->assertJson($response->get_data());

    $message = json_decode($response->get_data());
	$this->assertEquals('This is the message name', $message->name);
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
	$this->assertJson($response->get_data());

	$message = json_decode($response->get_data());
	$this->assertEquals('Name of the message', $message->name);
});

test('Update a message', function() {
	$message = $this->factory->message->create_and_get([
		'name' => 'This is the message name'
	]);

	$this->assertEquals('This is the message name', $message->name);

	$request  = new WP_REST_Request( 'PUT', "/gc-lists/messages/{$message->id}" );
	$request->set_query_params([
		'id' => $message->id,
		'name' => 'Name of the message',
		'subject' => 'Subject of the message',
		'body' => 'Body of the message',
	]);

	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
	$this->assertJson($response->get_data());

	$message = json_decode($response->get_data());
	$this->assertEquals('Name of the message', $message->name);
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
