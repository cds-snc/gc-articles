<?php

use GCLists\Utils;

test('extractServiceIdFromApiKey', function() {
    $fakeApiKey = 'this-is-fake-a7902fc8-37f0-417c-54c8-9ab499ee54c8-8f43566b-e491-451h-99e7-4f604b5c8283';
	$serviceId = Utils::extractServiceIdFromApiKey($fakeApiKey);
	$this->assertEquals($serviceId, 'a7902fc8-37f0-417c-54c8-9ab499ee54c8');

	// the utility just returns a substring using offsets, may in future add more handling/validation around this
	$shortone = 'this-is-not-an-api-key';
	$serviceId = Utils::extractServiceIdFromApiKey($shortone);
	$this->assertEquals($serviceId, $shortone);
});

test('getServiceId', function() {
    mock('get_option')
        ->shouldReceive('NOTIFY_API_KEY')
        ->andReturn('this-is-fake-a7902fc8-37f0-417c-54c8-9ab499ee54c8-8f43566b-e491-451h-99e7-4f604b5c8283');

    $serviceId = Utils::getServiceId();
})->skip();

test('getServices', function() {
    // todo
})->skip();

test('getUserPermissions', function() {
    // todo
})->skip();
