<?php

beforeEach(function() {
	global $wp_rest_server;
	$this->server = $wp_rest_server = new \WP_REST_Server();
	$this->baseRoute = 'gc-lists/messages';

	do_action( 'rest_api_init' );
});

test('Get all messages', function() {
	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});

test('Get sent messages', function() {
	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages/sent' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});

test('Get one messages', function() {
	$request  = new WP_REST_Request( 'GET', '/gc-lists/messages/1' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});

test('Create a message', function() {
	$request  = new WP_REST_Request( 'POST', '/gc-lists/messages' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});

test('Update a message', function() {
	$request  = new WP_REST_Request( 'PUT', '/gc-lists/messages/1' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});

test('Delete a message', function() {
	$request  = new WP_REST_Request( 'DELETE', '/gc-lists/messages/1' );
	$response = $this->server->dispatch( $request );

	$this->assertEquals( 200, $response->get_status() );
});
