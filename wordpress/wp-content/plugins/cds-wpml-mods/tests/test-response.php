<?php

use CDS\Wpml\Api\Endpoints;
use CDS\Wpml\Api\FormatResponse;

function getPost(array $args = []): \WP_Post {
    $defaultArgs = [
        'ID'            => 1,
        'post_title'    => 'Mock Post',
        'post_type'     => 'post'
    ];
    $args = array_merge($defaultArgs, $args);

    $mockPost = new \WP_Post((new \stdClass()));
    foreach ($args as $k => $v) {
        $mockPost->$k = $v;
    }

    return $mockPost;
}

function getPosts() : array
{
    //Create 4 mock posts with different titles
    $mockPosts = [];
    for ($i = 0; $i <= 3; $i++) {
        $id = $i + 1;
        $args = [
            'ID' => $id ,
            'post_title' => 'Mock Post ' . $id
        ];
        $mockPosts[$i] = getPost($args);
    }
    //Return a mock array of mock posts
    return $mockPosts;
}

test('assert filtering language keeps "en" posts', function () {
    $response = mock(FormatResponse::class)->makePartial();
    $response->shouldReceive("getLanguageCodeOfPostObject")->andReturn('en');

    $posts = getPosts();
    $posts = $response->filterPostsByLanguage($posts, 'en');
    expect(count($posts))->toBe(4);
});

test('assert filtering language removes "fr" posts', function () {
    $response = mock(FormatResponse::class)->makePartial();
    $response->shouldReceive("getLanguageCodeOfPostObject")->andReturn('en');

    $posts = getPosts();
    $posts = $response->filterPostsByLanguage($posts, 'fr');
    expect(count($posts))->toBe(0);
});

test('assert reponse object  language removes "fr" posts', function () {
    $response = mock(FormatResponse::class)->makePartial();
    $response->shouldReceive("getLanguageCodeOfPostObject")->andReturn('en');
    $response->shouldReceive("getTranslatedPostID")->andReturn(null);

    $post = getPost();
    $responseObj = $response->buildResponseObject($post, 'fr');

    expect($responseObj)->toMatchArray([
        'ID' => 1,
        'post_title' => 'Mock Post',
        'post_type' => 'post',
        'language_code' => 'en',
        'translated_post_id' => null,
        'is_translated' => false
    ]);
});
