<?php

use CDS\Modules\DBInsights\DBInsights;

beforeAll(function () {
    WP_Mock::setUp();
});

afterAll(function () {
    WP_Mock::tearDown();
});

test('DBInsights addActions', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $maintenance = new DBInsights();
    WP_Mock::expectActionAdded('rest_api_init', [$maintenance, 'registerRestRoutes']);
    $maintenance->addActions();
});

test('parses BlogId(s) from string', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $insights = new DBInsights();

    $result = $insights->parseBlogId("wp_2_some_table_name");
    expect($result)->toEqual(2);

    $result = $insights->parseBlogId("wp_some_table");
    expect($result)->toEqual(null);

    $result = $insights->parseBlogId("wp");
    expect($result)->toEqual(null);
});


test('cleans DB tables', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $mock = \Mockery::mock('CDS\Modules\DBInsights\DBInsights[getBlogIds,getTables]');

    $blogIds = ["3", "2", "1"];
    $mock->shouldReceive('getBlogIds')
        ->andReturn($blogIds);

    $tables = [
        "wp_1_table_1",
        "wp_1_table_2",
        "wp_2_table_1",
        "wp_4_table_1",
        "wp_4_table_2",
        "wp_5_table_1"
    ];

    $mock->shouldReceive('getTables')
        ->andReturn($tables);

    expect($mock->getTables())->toEqual($tables);

    // $expected = {"blogIds":["3","2","1"],"tables":["wp_4_table_1","wp_4_table_2","wp_5_table_1"]}'
    $result = $mock->cleanupDbTables();

    expect($result->blogIds)->toEqual($blogIds);
    expect($result->tables[0])->toEqual(["name" => "wp_4_table_1"]);
    expect($result->tables[1])->toEqual(["name" => "wp_4_table_2"]);
    expect($result->tables[2])->toEqual(["name" => "wp_5_table_1"]);
});




