<?php

use CDS\Wpml\Api\Endpoints;

beforeEach(function() {
	global $wp_rest_server;
	$this->server = $wp_rest_server = new \WP_REST_Server();
	$this->baseRoute = 'cds/wpml';

	do_action( 'rest_api_init' );
});

test('getInstance', function() {
	$messages = Endpoints::getInstance();
	$this->assertInstanceOf(Endpoints::class, $messages);
});

test('getAvailablePages English', function() {
	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');

	$this->factory()->post->create_many(5, [
		'post_type' => 'page'
	]);

	$request  = new WP_REST_Request( 'GET', '/cds/wpml/pages/en' );
	$response = $this->server->dispatch( $request );

	$body = $response->get_data();

	expect($body)
		->toBeArray()
		->toHaveCount(5)
		->each
		->toHaveKeys(['ID', 'post_title', 'post_type', 'language_code', 'translated_post_id', 'is_translated'])
		->toHaveKey('language_code', 'en');
})->group('wpml');

test('getAvailablePages French', function() {
	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('fr');

	$this->factory()->post->create_many(5, [
		'post_type' => 'page'
	]);

	$request  = new WP_REST_Request( 'GET', '/cds/wpml/pages/fr' );
	$response = $this->server->dispatch( $request );

	$body = $response->get_data();

	expect($body)
		->toBeArray()
		->toHaveCount(5)
		->each
		->toHaveKeys(['ID', 'post_title', 'post_type', 'language_code', 'translated_post_id', 'is_translated'])
		->toHaveKey('language_code', 'fr');
})->group('wpml');
