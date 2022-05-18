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

test('asserts textField returns a text input with expected values', function () {
    $field = Utils::textField('myId', 'Enter your name', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<label class="gc-label" for="myId" id="myId-label">Enter your name</label>');
    expect($field)->toContain('<input type="text" id="myId" name="myId" value="" required class="gc-input-text" />');
});

test('asserts textField returns a text input that is not required when id starts with "optional"', function () {
    $field = Utils::textField('optional-myID', 'Enter your middle name', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<label class="gc-label" for="optional-myID" id="optional-myID-label">Enter your middle name</label>');
    expect($field)->toContain('<input type="text" id="optional-myID" name="optional-myID" value="" class="gc-input-text" />');
});

test('asserts textField returns an email input when id is "email"', function () {
    $field = Utils::textField('email', 'Enter your email', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<label class="gc-label" for="email" id="email-label">Enter your email</label>');
    expect($field)->toContain('<input type="email" autocomplete="email" id="email" name="email" value="" required class="gc-input-text" />');
});

test('asserts textField returns a text input with a description', function () {
    $field = Utils::textField('myId', 'Enter your name', 'Your name is that thing people call you', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<div id="myId-desc" class="gc-description" data-testid="description">Your name is that thing people call you</div>');
});

test('asserts textField returns a text input with a filled-in value', function () {
    $field = Utils::textField('myId', 'Enter your name', value: 'Gino', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<input type="text" id="myId" name="myId" value="Gino" required class="gc-input-text" />');
});

test('asserts textField returns a text input with a placeholder', function () {
    $field = Utils::textField('myId', 'Enter your name', placeholder: 'Your name here', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<input type="text" id="myId" name="myId" value="" placeholder="Your name here" required class="gc-input-text" />');
});

test('asserts radioField returns a radio input with expected values', function () {
    $field = Utils::radioField('myName', 'myId', 'myValue', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($field)->toContain('<input type="radio" name="myName" id="myId" value="myId" class="gc-radio__input" required />');
    expect($field)->toContain('<label for="myId"');
    expect($field)->toContain('<span class="radio-label-text">myValue</span>');
});

test('asserts radioField returns a "checked" radio input if "id" array contains value', function () {
    $field = Utils::radioField('myName', 'myId', 'myValue', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($field)->toContain('<input type="radio" name="myName" id="myId" value="myId" class="gc-radio__input" required />');
    expect($field)->toContain('<label for="myId"');
    expect($field)->toContain('<span class="radio-label-text">myValue</span>');
});

test('asserts checkboxField returns a checkbox input with expected values', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    // note that "id" arg is used for value (because we don't want to be translated)
    expect($field)->toContain('<input type="checkbox" name="myName" id="myId" value="myId" class="gc-input-checkbox__input" />');
    expect($field)->toContain('<label for="myId"');
    expect($field)->toContain('<span class="checkbox-label-text">myValue</span>');
});

test('asserts checkboxField returns a "checked" checkbox input if "id" array contains value', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', vals: ['myId'], echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('checked');
});

test('asserts checkboxField returns a not "checked" checkbox input if "values" array does not contain value', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', vals: ['myOtherValue'], echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->not->toContain('checked');
});

test('asserts checkboxField returns an "aria-controls" and "expanded" checkbox input', function () {
    $field = Utils::checkboxField('myName', 'myId', 'myValue', vals: ['myId'], ariaControls: 'aria-controls', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('aria-controls="aria-controls" aria-expanded="1"');
});

test('asserts submitButton returns a button with the label we want', function () {
    $field = Utils::submitButton('Press the button', echo: false);
    $field = preg_replace('/\s+/', ' ', $field); // remove all whitespace

    expect($field)->toContain('<button class="gc-button" type="submit" id="submit">Press the button</button>');
});
