<?php

use CDS\Modules\TrackLogins\TrackLogins;

// Global variables to store mocked function returns and action expectations
$GLOBALS['wp_test_mocks'] = [];
$GLOBALS['wp_test_action_expectations'] = [];

// Mock WordPress functions
if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return $GLOBALS['wp_test_mocks']['get_option'] ?? true;
    }
}

if (!function_exists('current_time')) {
    function current_time($type, $gmt = 0) {
        return $GLOBALS['wp_test_mocks']['current_time'] ?? date('Y-m-d H:i:s');
    }
}

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

beforeAll(function () {
    $GLOBALS['wp_test_mocks']['get_option'] = true;
});

afterAll(function () {
    $GLOBALS['wp_test_mocks'] = [];
    $GLOBALS['wp_test_action_expectations'] = [];
});

test('TrackLogins addActions', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    // Reset action expectations
    $GLOBALS['wp_test_action_expectations'] = [];

    $trackLogins = new TrackLogins();
    $trackLogins->addActions();

    // Verify that the expected actions were added
    $expectedActions = ['wp_login', 'rest_api_init', 'wp_dashboard_setup'];
    $foundActions = [];

    foreach ($GLOBALS['wp_test_action_expectations'] as $action) {
        if (in_array($action['hook'], $expectedActions) && 
            $action['callback'][0] instanceof TrackLogins) {
            $foundActions[] = $action['hook'];
        }
    }

    expect(count($foundActions))->toBe(3);
    expect($foundActions)->toContain('wp_login');
    expect($foundActions)->toContain('rest_api_init');
    expect($foundActions)->toContain('wp_dashboard_setup');
});

test('TrackLogins logUserLogins', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36';
    $user = (object)[
        'ID' => 1,
        'user_email' => 'admin@canada.ca',
    ];
    $current_time = '2021-09-16 20:46:06';
    $data = [
        'user_agent' => $user_agent,
        'time_login' => $current_time,
        'user_id'    => $user->ID
    ];

    $wpdb->shouldReceive('insert')->once()->with('wp_userlogins', $data)->andReturn(true);

    // Mock current_time function
    $GLOBALS['wp_test_mocks']['current_time'] = $current_time;

    $_SERVER['HTTP_USER_AGENT'] = $user_agent;

    $trackLogins = new TrackLogins();
    $trackLogins->logUserLogin('username', $user);
});
