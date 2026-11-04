<?php

test('retired public form classes cannot be autoloaded', function (string $class) {
    expect(class_exists($class))->toBeFalse();
})->with([
    'CDS\\Modules\\Forms\\Setup',
    'CDS\\Modules\\Forms\\Contact\\Setup',
    'CDS\\Modules\\Forms\\Contact\\ContactForm',
    'CDS\\Modules\\Forms\\RequestSite\\Setup',
    'CDS\\Modules\\Forms\\RequestSite\\RequestSiteForm',
    'CDS\\Modules\\Forms\\Messenger',
    'CDS\\Modules\\Forms\\Utils',
]);
