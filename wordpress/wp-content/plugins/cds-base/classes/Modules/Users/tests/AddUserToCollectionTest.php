<?php

use CDS\Modules\Users\Users;
use CDS\Modules\Users\ValidationException;

// Global variables to store mocked function returns
$GLOBALS['wp_test_mocks'] = [];

// Mock WordPress functions
if (!function_exists('sanitize_email')) {
    function sanitize_email($email) {
        return $email;  // return_arg => 0 means return the first argument unchanged
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return $str;  // return_arg => 0 means return the first argument unchanged
    }
}

if (!function_exists('is_email')) {
    function is_email($email) {
        return $GLOBALS['wp_test_mocks']['is_email'] ?? true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Mock function - just store the filter registration
        return true;
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        // Mock function - just store the action registration
        return true;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;  // Return the text unchanged for testing
    }
}

beforeAll(function () {
    $GLOBALS['wp_test_mocks']['is_email'] = true;
});

afterAll(function () {
    $GLOBALS['wp_test_mocks'] = [];
});

test('too few args', function () {
    $users = new Users();
    $users->sanitizeValues();
})->throws(ValidationException::class);

test('empty array', function () {
    $users = new Users();
    $users->sanitizeValues([]);
})->throws(ValidationException::class);

test('empty email throws email error', function () {
    try {
        $users = new Users();
        $users->sanitizeValues(["role" => "gceditor", "confirmationType" => ""]);
    } catch (ValidationException $e) {
        $this->assertTrue(str_contains($e->getMessage(), "email"));
    }
});

test('bad domain for email throws', function () {
    try {
        $users = new Users();
        $users->sanitizeValues(["email" => "test@example.com", "confirmationType" => ""]);
    } catch (ValidationException $e) {
        $this->assertTrue(str_contains($e->getMessage(), "email"));
    }
});


test('empty role throws role error', function () {
    try {
        $users = new Users();
        $users->sanitizeValues(["email" => "admin@cds-snc.ca",  "role" => "" , "confirmationType" => ""]);
    } catch (ValidationException $e) {
        $this->assertTrue(str_contains($e->getMessage(), "role"));
    }
});

test('throws when an invalid role is passed', function () {
    global $wp_roles;
    $wp_roles = new \stdClass();
    $wp_roles->role_names = [];

    try {
        $users = new Users();
        $users->sanitizeValues(["email" => "test@cds-snc.ca",  "role" => "admin", "confirmationType" => ""]);
    } catch (ValidationException $e) {
        $this->assertTrue(str_contains($e->getMessage(), "role"));
    }
});

test('returns array with cleaned values', function () {
    global $wp_roles;
    $wp_roles = new \stdClass();
    $wp_roles->role_names = [];

    try {
        $users = new Users();
        $result = $users->sanitizeValues(["email" => "test@cds-snc.ca",  "role" => "administrator", "confirmationType" => ""]);
        $this->assertTrue($result["email"] === "test@cds-snc.ca");
        $this->assertTrue($result["role"] === "administrator");
    } catch (ValidationException $e) {
        print_r($e->getMessage());
        $this->assertTrue(false === true);
    }
});
