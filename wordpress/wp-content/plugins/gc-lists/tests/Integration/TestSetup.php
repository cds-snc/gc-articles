<?php

test('Plugin is installed', function() {
	$this->assertTrue(defined('GC_LISTS_PLUGIN_FILE_PATH'));
});

test('Database table is installed', function() {
	global $wpdb;
	$table_name = $wpdb->prefix.'messages';
	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

	$this->assertEquals($wpdb->get_var( $query ), $table_name);
});

/**
 * This is just an example of how to use a factory. The factory is provided by WP_UnitTestCase.
 */
test('Use a factory to create a User', function() {
	$user_id = $this->factory->user->create();
	$this->assertEquals($user_id, 2);
});
