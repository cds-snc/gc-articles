<?php

use CDS\Modules\Users\EmailDomains;

beforeAll(function () {
    WP_Mock::setUp();

    WP_Mock::userFunction('is_email', array(
        'return' => true,
    ));
});

afterAll(function () {
    WP_Mock::tearDown();
});

test('badEmails', function($email) {
    expect(EmailDomains::isValidDomain($email))->toEqual(false);
})->with([
    "bad domain" => 'justin.trudeau@gmail.com',
    "no TLD" => 'justin.trudeau@cds-snc',
    "no @" => 'justin.trudeaucds-snc.ca',
    "no username" => '@cds-snc.ca',
    "onmicrosoft without domain" => 'justin.trudeau@onmicrosoft.com',
    "any onmicrosoft" => 'justin.trudeau@something.onmicrosoft.com', // subdomain must be explicitly allowed
    'empty' => '',
    'similar domain cds-snc.ca' => 'justin.trudeau@badcds-snc.ca',
    'similar domain canada.ca' => 'justin.truedau@badcanada.ca'
]);

test('Good emails', function($email) {
    expect(EmailDomains::isValidDomain($email))->toEqual(true);
})->with([
    "CDS domain" => 'justin.trudeau@cds-snc.ca',
    "TBS domain" => 'justin.trudeau@tbs-sct.gc.ca',
    "random GC domain" => 'justin.trudeau@my-very-cool.department.gc.ca',
    "only GC domain" => 'justin.trudeau@gc.ca', // pretty sure this is *not* valid
    'Canada domain' => 'justin.trudeau@canada.ca',
    'Service Canada domain' => 'justin.trudeau@servicecanada.ca',
    'random Canada domain' => 'justin.trudeau@hockey.night.in.canada.ca',
    'pspc innovation domain' => 'justin.trudeau@pspcinnovation.onmicrosoft.com', // subdomain must be explicitly allowed
    'esdc ‘innovation’ domain' => 'justin.trudeau@esdc-innovation.onmicrosoft.com', // subdomain must be explicitly allowed
]);