<?php

use CDS\Wpml\Post;

test('getInstance', function() {
	$messages = Post::getInstance();
	$this->assertInstanceOf(Post::class, $messages);
});

test('buildResponseObject assert response object with no translation', function () {
	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');
	$sitepress->shouldReceive("get_object_id")->andReturn(null);

	$postClass = new Post();

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$responseObj = $postClass->buildResponseObject($post, withTranslations: true);

	expect($responseObj)->toMatchArray([
		'ID' => $post_id,
		'post_title' => $post->post_title,
		'post_type' => 'post',
		'language_code' => 'en',
		'translated_post_id' => null,
		'is_translated' => false
	]);
});


test('buildResponseObject assert response object with translation', function () {
	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');
	$sitepress->shouldReceive("get_object_id")->andReturn(2);

	$postClass = new Post();

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$responseObj = $postClass->buildResponseObject($post, withTranslations: true);

	expect($responseObj)->toMatchArray([
		'ID' => $post->ID,
		'post_title' => $post->post_title,
		'post_type' => 'post',
		'language_code' => 'en',
		'translated_post_id' => 2,
		'is_translated' => true
	]);
});

test('buildResponseObject assert response object with no language provided', function () {
	global $sitepress;

	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');

	$postClass = new Post();

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$responseObj = $postClass->buildResponseObject($post, withTranslations: false);

	expect($responseObj)->toMatchArray([
		'ID' => $post->ID,
		'post_title' => $post->post_title,
		'post_type' => 'post',
		'language_code' => 'en'
	]);
});

test('getLanguageCodeOfPostObject', function() {
	global $sitepress;
	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$postClass = new Post();

	$language = $postClass->getLanguageCodeOfPostObject($post);

	expect($language)->toEqual('en');
});

test('getTRID', function() {
	global $sitepress;
	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_element_trid")->andReturn('100');

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$postClass = new Post();

	$trid = $postClass->getTRID($post->ID, 'post_post');

	expect($trid)->toEqual('100');
});

test('getTranslatedPostID', function() {
	global $sitepress;
	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_language_for_element")->andReturn('en');
	$sitepress->shouldReceive("get_object_id")->andReturn(2);

	$post_id = $this->factory()->post->create();
	$post = get_post($post_id);

	$postClass = new Post();

	$id = $postClass->getTranslatedPostID($post);

	expect($id)->toEqual(2);

	$id = $postClass->getTranslatedPostID($post, 'en');
	expect($id)->toEqual(2);
});

test('setTranslationForPost calls set_element_language_details_action', function() {
	global $sitepress;
	$sitepress = mock('\SitePress');
	$sitepress->shouldReceive("get_element_trid")->andReturn('100');
	$sitepress->shouldReceive("set_element_language_details_action");

	$postClass = new Post();
	$postClass->setTranslationForPost(1, 'post', 'en');
});


