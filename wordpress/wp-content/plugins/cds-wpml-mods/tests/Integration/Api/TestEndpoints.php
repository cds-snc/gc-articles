<?php

use CDS\Wpml\Api\Endpoints;
use CDS\Wpml\Post;

beforeEach(function() {
	global $wp_rest_server;
	$this->server = $wp_rest_server = new \WP_REST_Server();
	$this->baseRoute = 'cds/wpml';

	do_action( 'rest_api_init' );
});

test('constructor', function() {
	$that = $this;
	$endpoints = new Endpoints();

	$assertPropertyClosure = function() use ($that) {
		expect($this->post)->toBeInstanceOf(Post::class);
		expect($this->namespace)->toBe("cds/wpml");
	};

	$doAssertPropertyClosure = $assertPropertyClosure->bindTo($endpoints, get_class($endpoints));

	$doAssertPropertyClosure();
});

test('getInstance', function() {
	$messages = Endpoints::getInstance();
	$this->assertInstanceOf(Endpoints::class, $messages);
});

test('getAvailablePages English', function() {
	global $sitepress;

	$user = wp_get_current_user();
	$user->add_cap("edit_posts");

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');
	$sitepress->shouldReceive("get_object_id")->andReturn(1);

	$this->factory()->post->create_many(5, [
		'post_type' => 'page'
	]);

	$request  = new WP_REST_Request('GET', '/cds/wpml/pages/en');
	$response = $this->server->dispatch($request);

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

	$user = wp_get_current_user();
	$user->add_cap("edit_posts");

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('fr');
	$sitepress->shouldReceive("get_object_id")->andReturn(1);

	$this->factory()->post->create_many(5, [
		'post_type' => 'page'
	]);

	$request  = new WP_REST_Request('GET', '/cds/wpml/pages/fr');
	$response = $this->server->dispatch($request);

	$body = $response->get_data();

	expect($body)
		->toBeArray()
		->toHaveCount(5)
		->each
		->toHaveKeys(['ID', 'post_title', 'post_type', 'language_code', 'translated_post_id', 'is_translated'])
		->toHaveKey('language_code', 'fr');
})->group('wpml');

test('saveTranslation validate translationId numeric', function() {
	$post_id = $this->factory()->post->create();
	$translation_id = $this->factory()->post->create();

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => 'dd'
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'Translation post ID must be numeric');
});

test('saveTranslation validate translationId invalid', function() {
	$post_id = $this->factory()->post->create();
	$translation_id = $this->factory()->post->create();

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => '0'
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'Translation post ID invalid');
});

test('saveTranslation validate translationId sameId', function() {
	$post_id = $this->factory()->post->create();
	$translation_id = $this->factory()->post->create();

	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('fr');


	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => $post_id
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'Translation post ID cannot be the same as original Post ID');
});

test('saveTranslation validate original does not exist', function() {
	$post_id = 444;
	$translationId = 445;

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => $translationId
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'The post you are looking for does not exist');
});

test('saveTranslation validate translationId does not exist', function() {
	$post_id = $this->factory()->post->create();
	$translationId = 445;

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => $translationId
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'The post you want to set as the translation does not exist');
});

test('saveTranslation validate different types', function() {
	$post_id = $this->factory()->post->create();
	$translationId = $this->factory()->post->create([
		'post_type' => 'page'
	]);

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => $translationId
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'Posts are not the same post_type');
});

test('saveTranslation validate same language', function() {
	$post_id = $this->factory()->post->create();
	$translationId = $this->factory()->post->create();

	$request  = new WP_REST_Request( 'POST', "/cds/wpml/posts/{$post_id}/translation" );
	$request->set_query_params([
		'translationId' => $translationId
	]);

	$response = $this->server->dispatch( $request );

	expect($response->get_status())->toBe(400);
	expect($response)
		->toBeInstanceOf('WP_REST_Response');

	expect($response->get_data())
		->toBeArray()
		->toHaveKey('code', 'rest_invalid_param');

	expect($response->get_data()['data']['params'])
		->toBeArray()
		->toHaveKey('translationId', 'Can’t assign a post’s translation to another post in the same language');
});
