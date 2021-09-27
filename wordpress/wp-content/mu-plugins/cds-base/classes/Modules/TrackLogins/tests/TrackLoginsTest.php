<?php

use CDS\Modules\TrackLogins\TrackLogins;

beforeAll(function () {
    WP_Mock::setUp();
});

afterAll(function () {
    WP_Mock::tearDown();
});

test('TrackLogins addActions', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $trackLogins = new TrackLogins();

    WP_Mock::expectActionAdded('wp_login', [$trackLogins,'logUserLogin'], 10, 2);
    WP_Mock::expectActionAdded('rest_api_init', [$trackLogins, 'registerRestRoutes']);
    WP_Mock::expectActionAdded('wp_dashboard_setup', [$trackLogins, 'dashboardWidget']);

    $trackLogins->addActions();
});

test('TrackLogins logUserLogins', function () {
    global $wpdb;

    $wpdb = mock('\WPDB');
    $wpdb->prefix = 'wp_';

    $user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.63 Safari/537.36';
    $this->user = (object)[
        'ID' => 1,
    ];
    $current_time = '2021-09-16 20:46:06';
    $data = [
        'user_agent' => $user_agent,
        'time_login' => $current_time,
        'user_id'    => $this->user->ID
    ];

    $wpdb->shouldReceive('insert')->once()->with('wp_userlogins', $data)->andReturn(true);

    WP_Mock::userFunction('current_time', [
        'times' => 1,
        'return' => $current_time
    ]);

    $_SERVER['HTTP_USER_AGENT'] = $user_agent;

    $trackLogins = new TrackLogins();
    $trackLogins->logUserLogin('username', $this->user);
});
