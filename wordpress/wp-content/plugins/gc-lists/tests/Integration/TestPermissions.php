<?php

use GCLists\Permissions;

test('getInstance', function() {
    $messages = Permissions::getInstance();
    $this->assertInstanceOf(Permissions::class, $messages);
})->group('permissions');

beforeAll(function() {
    /**
     * When running the integration test suite, only the current plugin is loaded, so we need to setup these
     * Roles that are provided by cds-base. Would be better to figure out how to bootstrap both plugins.
     */
    add_role('gceditor', 'GC Editor');
    add_role('gcwriter', 'GC Writer');
});

test('CleanupCustomCapsForRoles', function() {
    $permissions = Permissions::getInstance();
    $permissions->cleanupCustomCapsForRoles();

    $administrator = get_role('administrator');
    $this->assertFalse($administrator->has_cap('manage_notify'));
    $this->assertFalse($administrator->has_cap('manage_list_manager'));
    $this->assertFalse($administrator->has_cap('list_manager_bulk_send'));
    $this->assertFalse($administrator->has_cap('list_manager_bulk_send_sms'));

    $gceditor = get_role('gceditor');
    $this->assertFalse($gceditor->has_cap('manage_notify'));
    $this->assertFalse($gceditor->has_cap('manage_list_manager'));
    $this->assertFalse($gceditor->has_cap('list_manager_bulk_send'));
    $this->assertFalse($gceditor->has_cap('list_manager_bulk_send_sms'));

    $gcwriter = get_role('gcwriter');
    $this->assertFalse($gcwriter->has_cap('manage_notify'));
    $this->assertFalse($gcwriter->has_cap('manage_list_manager'));
    $this->assertFalse($gcwriter->has_cap('list_manager_bulk_send'));
    $this->assertFalse($gcwriter->has_cap('list_manager_bulk_send_sms'));
})->group('permissions');

test('addDefaultUserCapsForRole sets up administrator defaults', function() {
    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );

    $user = wp_get_current_user();
    $user->add_role('administrator');

    $this->assertTrue($user->has_cap('manage_notify'));
    $this->assertTrue($user->has_cap('manage_list_manager'));
    $this->assertTrue($user->has_cap('list_manager_bulk_send'));
    $this->assertFalse($user->has_cap('list_manager_bulk_send_sms'));
})->group('permissions');

test('addDefaultUserCapsForRole sets up gceditor defaults', function() {
    $user_id = $this->factory->user->create();
    wp_set_current_user( $user_id );

    $user = wp_get_current_user();
    $user->add_role('gceditor');

    $this->assertFalse($user->has_cap('manage_notify'));
    $this->assertTrue($user->has_cap('manage_list_manager'));
    $this->assertTrue($user->has_cap('list_manager_bulk_send'));
    $this->assertFalse($user->has_cap('list_manager_bulk_send_sms'));
})->group('permissions');
