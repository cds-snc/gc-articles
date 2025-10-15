<?php

use CDS\Modules\Users\EmailDomains;

// Global variables to store mocked function returns
$GLOBALS['wp_test_mocks'] = [];

// Mock WordPress functions
if (!function_exists('is_email')) {
    function is_email($email) {
        return $GLOBALS['wp_test_mocks']['is_email'] ?? true;
    }
}

beforeAll(function () {
    $GLOBALS['wp_test_mocks']['is_email'] = true;
});

afterAll(function () {
    $GLOBALS['wp_test_mocks'] = [];
});

test('badEmails', function($email) {
    expect(EmailDomains::isValidDomain($email))->toEqual(false);
})->with([
    "bad domain" => 'justin.trudeau@gmail.com',
    "no TLD" => 'justin.trudeau@cds-snc',
    "no @" => 'justin.trudeaucds-snc.ca',
    "no username" => '@cds-snc.ca',
    "onmicrosoft without domain" => 'justin.trudeau@onmicrosoft.com',
    "any onmicrosoft" => 'justin.trudeau@something.onmicrosoft.com', // no randos
    'empty' => '',
    'leading similar domain cds-snc.ca' => 'justin.trudeau@badcds-snc.ca',
    'leading similar domain canada.ca' => 'justin.truedau@badcanada.ca',
    'trailing similar domain canada.ca' => 'justin.trudeau@canada.ca.something',
    'trailing similar domain cds-snc.ca' => 'justin.trudeau@cds-snc.ca.something',
    "only GC domain" => 'justin.trudeau@gc.ca', // pretty sure this is *not* valid
]);

test('Good emails', function($email) {
    expect(EmailDomains::isValidDomain($email))->toEqual(true);
})->with([
    "CDS domain" => 'justin.trudeau@cds-snc.ca',
    "TBS domain" => 'justin.trudeau@tbs-sct.gc.ca',
    "random GC domain" => 'justin.trudeau@my-very-cool.department.gc.ca',
    'Canada domain' => 'justin.trudeau@canada.ca',
    'Service Canada domain' => 'justin.trudeau@servicecanada.ca',
    'random Canada domain' => 'justin.trudeau@hockey.night.in.canada.ca',
    'pspc innovation domain' => 'justin.trudeau@pspcinnovation.onmicrosoft.com', // subdomain must be explicitly allowed
]);