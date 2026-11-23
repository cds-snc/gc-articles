<?php

use CDS\Modules\DBInsights\DBInsights;

// Global variables to store mocked action expectations
$GLOBALS['wp_test_action_expectations'] = [];
$GLOBALS['wp_test_rest_routes'] = [];
$GLOBALS['wp_test_is_super_admin'] = false;

// Mock WordPress function to register actions
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        $GLOBALS['wp_test_action_expectations'][] = [
            'hook' => $hook,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];
    }
}

if (!function_exists('register_rest_route')) {
    function register_rest_route($namespace, $route, $args) {
        $GLOBALS['wp_test_rest_routes'][$namespace . $route] = $args;
    }
}

if (!function_exists('is_super_admin')) {
    function is_super_admin() {
        return $GLOBALS['wp_test_is_super_admin'];
    }
}

afterAll(function () {
    // Reset action expectations
    $GLOBALS['wp_test_action_expectations'] = [];
    $GLOBALS['wp_test_rest_routes'] = [];
});

test('DBInsights addActions', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    // Reset action expectations
    $GLOBALS['wp_test_action_expectations'] = [];

    $maintenance = new DBInsights();
    $maintenance->addActions();

    // Verify that the expected action was added
    $found = false;
    foreach ($GLOBALS['wp_test_action_expectations'] as $action) {
        if ($action['hook'] === 'rest_api_init' && 
            $action['callback'][0] instanceof DBInsights && 
            $action['callback'][1] === 'registerRestRoutes') {
            $found = true;
            break;
        }
    }
    
    expect($found)->toBeTrue();
});

test('maintenance REST routes require super-admin privileges', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $GLOBALS['wp_test_rest_routes'] = [];
    $GLOBALS['wp_test_is_super_admin'] = false;

    $insights = new DBInsights();
    $insights->registerRestRoutes();

    foreach (['/deleted-site-tables', '/site-activity'] as $route) {
        $permissionCallback = $GLOBALS['wp_test_rest_routes']['maintenance/v1' . $route]['permission_callback'];
        expect($permissionCallback())->toBeFalse();
    }

    $GLOBALS['wp_test_is_super_admin'] = true;

    foreach (['/deleted-site-tables', '/site-activity'] as $route) {
        $permissionCallback = $GLOBALS['wp_test_rest_routes']['maintenance/v1' . $route]['permission_callback'];
        expect($permissionCallback())->toBeTrue();
    }
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




