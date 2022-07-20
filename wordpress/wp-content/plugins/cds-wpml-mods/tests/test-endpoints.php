<?php

use CDS\Wpml\Api\Endpoints;

function getPosts() : array
{
    //Create 4 mock posts with different titles
    $mockPosts = [];
    for ($i = 0; $i <= 3; $i++) {
        $postID = $i + 1;
        $mockPosts[$i] = (new \WP_Post((new \stdClass())));
        $mockPosts[$i]->ID = $postID;
        $mockPosts[$i]->post_title = "Mock Post $postID";
    }
    //Return a mock array of mock posts
    return $mockPosts;
}

test('asserts true is true', function () {
    $endpoints = new Endpoints();
 
    expect(true)->toBeTrue();
    expect($endpoints->returnTwo())->toBe(2);
});

test('asserts post title', function () {
    $endpoints = new Endpoints();

    $posts = getPosts();

    expect($endpoints->getPostTitle($posts[0]))->toEqual('Mock Post 1');
});

test('asserts post language', function () {
    $endpoints = mock(Endpoints::class)->makePartial();
    $endpoints->shouldReceive("getLanguageCodeOfPostObject")->andReturn('en');

    $posts = getPosts();

    expect($endpoints->getPostLanguage($posts[0]))->toEqual('en');
});