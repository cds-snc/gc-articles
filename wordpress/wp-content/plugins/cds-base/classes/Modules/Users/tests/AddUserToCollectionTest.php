<?php

use CDS\Modules\Users\Users;
use CDS\Modules\Users\ValidationException;

beforeAll(function () {
    WP_Mock::setUp();

    \WP_Mock::userFunction('sanitize_email', array(
        'return_arg' => 0
    ));

    \WP_Mock::userFunction('sanitize_text_field', array(
        'return_arg' => 0
    ));

    WP_Mock::userFunction('is_email', array(
        'return' => true,
    ));
});

afterAll(function () {
    WP_Mock::tearDown();
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
