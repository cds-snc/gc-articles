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
    add_option('NOTIFY_API_KEY', 'this-is-fake-a7902fc8-37f0-417c-54c8-9ab499ee54c8-8f43566b-e491-451h-99e7-4f604b5c8283');
    $serviceId = Utils::getServiceId();
    $this->assertEquals($serviceId, 'a7902fc8-37f0-417c-54c8-9ab499ee54c8');
});

test('getServices', function() {
    add_option('NOTIFY_API_KEY', 'this-is-fake-a7902fc8-37f0-417c-54c8-9ab499ee54c8-8f43566b-e491-451h-99e7-4f604b5c8283');
    $services = Utils::getServices();
    $this->assertEquals($services, [
        'name' => 'Your Lists',
        'service_id' => 'a7902fc8-37f0-417c-54c8-9ab499ee54c8'
    ]);
});

test('getUserPermissions', function() {
    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );
    $current_user = wp_get_current_user();

    $permissions = Utils::getUserPermissions();

    $none = new stdClass();
    $none->hasEmail = false;
    $none->hasPhone = false;
    $this->assertEquals($none, $permissions);

    $current_user->add_cap('list_manager_bulk_send', true);
    $current_user->add_cap('list_manager_bulk_send_sms', true);
    $permissions = Utils::getUserPermissions();

    $hasBoth = new stdClass();
    $hasBoth->hasEmail = true;
    $hasBoth->hasPhone = true;
    $this->assertEquals($hasBoth, $permissions);
});
