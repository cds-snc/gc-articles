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
    $field = Utils::radioField('myName', 'myId', 'myValue');
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($field)->toContain('<input type="radio" name="myName" id="myId" value="myId" class="gc-radio__input" required />');
    expect($field)->toContain('<label for="myId"');
    expect($field)->toContain('<span class="radio-label-text">myValue</span>');
});

test('asserts checkboxField returns a checkbox input with expected values', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue');
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($field)->toContain('<input type="checkbox" name="myName" id="myId" value="myId" class="gc-input-checkbox__input" />');
    expect($field)->toContain('<label for="myId"');
    expect($field)->toContain('<span class="checkbox-label-text">myValue</span>');
});

test('asserts checkboxField returns a "checked" checkbox input if "values" array contains value', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', ['myValue']);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('checked');
});

test('asserts checkboxField returns a not "checked" checkbox input if "values" array does not contain value', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', ['myOtherValue']);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->not->toContain('checked');
});

test('asserts checkboxField returns an "aria-controls" and "expanded" checkbox input', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', ['myValue'], 'aria-controls');
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('aria-controls="aria-controls" aria-expanded="1"');
});
