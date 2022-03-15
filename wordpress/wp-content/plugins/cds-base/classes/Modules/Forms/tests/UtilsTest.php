<?php 

use CDS\Modules\Forms\Utils;

beforeAll(function () {
    \WP_Mock::setUp();

    \WP_Mock::userFunction('sanitize_title', array(
        'return_arg' => 'myId'
    ));
});

afterAll(function () {
    \WP_Mock::tearDown();
});

test('asserts radioField returns a radio input with expected values', function () {
    $radioField = Utils::radioField('myName', 'myId', 'myValue');
    // remove all whitespace
    $radioField = preg_replace('/\s+/', ' ', $radioField);

    // str_contains(string $haystack, string $needle): bool

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($radioField)->toContain('<input type="radio" name="myName" id="myId" value="myId" class="gc-radio__input" required />');
    expect($radioField)->toContain('<label for="myId"');
    expect($radioField)->toContain('<span class="radio-label-text">myValue</span>');
});