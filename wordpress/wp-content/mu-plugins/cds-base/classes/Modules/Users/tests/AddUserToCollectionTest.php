<?php
use CDS\Modules\Users\Users;

beforeAll(function () {
    WP_Mock::setUp();
    WP_Mock::userFunction('is_email', array(
        'return' => true,
    ));
});

afterAll(function () {
    WP_Mock::tearDown();
});

test('too few args', function () {
    $users = new Users();
    $users->santatizeEmailAndRole();
})->throws(InvalidArgumentException::class);

test('empty array', function () {
    $users = new Users();
    $users->santatizeEmailAndRole([]);
})->throws(InvalidArgumentException::class);

test('empty email throws email error', function () {
    try {
        $users = new Users();
        $users->santatizeEmailAndRole(["role" => "gceditor"]);
    }catch(InvalidArgumentException $e){
        $this->assertTrue(str_contains($e->getMessage(), "email"));
    }
});

test('bad domain for email throws', function () {
    try {
        $users = new Users();
        $users->santatizeEmailAndRole(["email" => "test@example.com"]);
    }catch(InvalidArgumentException $e){
        $this->assertTrue(str_contains($e->getMessage(), "email"));
    }
});


test('empty role throws role error', function () {
    try {
        $users = new Users();
        $users->santatizeEmailAndRole(["email" => "admin@cds-snc.ca",  "role" => ""]);
    }catch(InvalidArgumentException $e){
        $this->assertTrue(str_contains($e->getMessage(), "role"));
    }
});

test('throws when an invalid role is passed', function () {
    global $wp_roles;
    $wp_roles = new \stdClass;
    $wp_roles->role_names = [];

    try {
        $users = new Users();
        $users->santatizeEmailAndRole(["email" => "test@cds-snc.ca",  "role" => "admin"]);
    }catch(InvalidArgumentException $e){
        $this->assertTrue(str_contains($e->getMessage(), "role"));
    }
});

test('returns array with cleaned values', function () {
    global $wp_roles;
    $wp_roles = new \stdClass;
    $wp_roles->role_names = [];

    try {
        $users = new Users();
        $result = $users->santatizeEmailAndRole(["email" => "test@cds-snc.ca",  "role" => "gcadmin"]);
        $this->assertTrue($result["email"] === "test@cds-snc.ca");
        $this->assertTrue($result["role"] === "gcadmin");
    }catch(InvalidArgumentException $e){
        print_r($e->getMessage());
    }
});
