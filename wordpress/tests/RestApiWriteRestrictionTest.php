<?php

// ---------------------------------------------------------------------------
// Stub WordPress classes (only defined once per process)
// ---------------------------------------------------------------------------

if (!class_exists('WP_Error')) {
    class WP_Error
    {
        public string $code;
        public string $message;
        public mixed $data;

        public function __construct(string $code = '', string $message = '', mixed $data = '')
        {
            $this->code    = $code;
            $this->message = $message;
            $this->data    = $data;
        }

        public function get_error_code(): string
        {
            return $this->code;
        }

        public function get_error_data(): mixed
        {
            return $this->data;
        }
    }
}

if (!class_exists('WP_REST_Server')) {
    class WP_REST_Server {}
}

if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request
    {
        private string $method;
        private string $route;

        public function __construct(string $method = 'GET', string $route = '/')
        {
            $this->method = $method;
            $this->route  = $route;
        }

        public function get_method(): string
        {
            return $this->method;
        }

        public function get_route(): string
        {
            return $this->route;
        }
    }
}

// ---------------------------------------------------------------------------
// Stub WordPress global functions (only defined once per process)
// ---------------------------------------------------------------------------

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1): bool
    {
        return true;
    }
}

if (!function_exists('get_current_user_id')) {
    function get_current_user_id(): int
    {
        return $GLOBALS['wp_test_mocks']['get_current_user_id'] ?? 0;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = 'default'): string
    {
        return $text;
    }
}

// ---------------------------------------------------------------------------
// Load the mu-plugin under test
// ---------------------------------------------------------------------------

require_once __DIR__ . '/../wp-content/mu-plugins/rest-api-write-restriction.php';

// ---------------------------------------------------------------------------
// Test helpers
// ---------------------------------------------------------------------------

$GLOBALS['wp_test_mocks'] = [];

afterEach(function () {
    $GLOBALS['wp_test_mocks'] = [];
});

function makeRestRequest(string $method, string $route): WP_REST_Request
{
    return new WP_REST_Request($method, $route);
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

test('GET request from unauthenticated user passes through', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('GET', '/wp/v2/posts')
    );
    expect($result)->toBeNull();
});

test('HEAD request from unauthenticated user passes through', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('HEAD', '/wp/v2/posts')
    );
    expect($result)->toBeNull();
});

test('POST from authenticated user passes through', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 5;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/wp/v2/posts')
    );
    expect($result)->toBeNull();
});

test('PUT from authenticated user passes through', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 5;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('PUT', '/wp/v2/posts/1')
    );
    expect($result)->toBeNull();
});

test('POST from unauthenticated user to arbitrary route is blocked with 403', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/wp/v2/posts')
    );
    expect($result)->toBeInstanceOf(WP_Error::class);
    expect($result->get_error_code())->toBe('rest_write_forbidden');
    expect($result->get_error_data()['status'])->toBe(403);
});

test('PUT from unauthenticated user to arbitrary route is blocked with 403', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('PUT', '/wp/v2/posts/1')
    );
    expect($result)->toBeInstanceOf(WP_Error::class);
    expect($result->get_error_data()['status'])->toBe(403);
});

test('DELETE from unauthenticated user to arbitrary route is blocked with 403', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('DELETE', '/wp/v2/posts/1')
    );
    expect($result)->toBeInstanceOf(WP_Error::class);
    expect($result->get_error_data()['status'])->toBe(403);
});

test('PATCH from unauthenticated user to arbitrary route is blocked with 403', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('PATCH', '/wp/v2/posts/1')
    );
    expect($result)->toBeInstanceOf(WP_Error::class);
    expect($result->get_error_data()['status'])->toBe(403);
});

test('unauthenticated POST to JWT token endpoint is allowlisted', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/jwt-authentication-for-wp-rest-api/v1/token')
    );
    expect($result)->toBeNull();
});

test('unauthenticated POST to JWT token/validate endpoint is allowlisted', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/jwt-authentication-for-wp-rest-api/v1/token/validate')
    );
    expect($result)->toBeNull();
});

test('unauthenticated POST to contact form endpoint is allowlisted', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/contact/v1/process')
    );
    expect($result)->toBeNull();
});

test('unauthenticated POST to request form endpoint is allowlisted', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $result = cds_block_unauthenticated_rest_writes(
        null,
        new WP_REST_Server(),
        makeRestRequest('POST', '/request/v1/process')
    );
    expect($result)->toBeNull();
});

test('pre-dispatched non-null result is returned unchanged without checking auth', function () {
    $GLOBALS['wp_test_mocks']['get_current_user_id'] = 0;
    $existing = ['already' => 'dispatched'];
    $result = cds_block_unauthenticated_rest_writes(
        $existing,
        new WP_REST_Server(),
        makeRestRequest('POST', '/wp/v2/posts')
    );
    expect($result)->toBe($existing);
});
