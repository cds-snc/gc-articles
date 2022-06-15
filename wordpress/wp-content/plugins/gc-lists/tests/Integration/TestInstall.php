<?php

use GCLists\Install;

test('construct', function() {
    $that = $this;
    $installer = Install::getInstance();

    $assertPropertyClosure = function() use ($that){
        global $wpdb;
        $that->assertSame($wpdb, $this->wpdb);
        $that->assertEquals('wptests_', $this->wpdb->prefix);
        $that->assertEquals('wptests_messages', $this->tableName);
    };

    $doAssertPropertyClosure = $assertPropertyClosure->bindTo($installer, get_class($installer));

    $doAssertPropertyClosure();
});

test('install', function() {
    global $wpdb;

    $installer = Install::getInstance();
    $installer->install();

    $columns = $wpdb->get_col("DESC {$installer->getTableName()}", 0);

    $this->assertEquals($columns, [
        'id',
        'name',
        'subject',
        'body',
        'message_type',
        'sent_at',
        'sent_to_list_id',
        'sent_to_list_name',
        'sent_by_id',
        'sent_by_email',
        'original_message_id',
        'version_id',
        'created_at',
        'updated_at'
    ]);
});

test('uninstall', function() {
    global $wpdb;

    $installer = Install::getInstance();

    $installer->install();

    $installer->uninstall();

    $result = $wpdb->get_var("SHOW TABLES LIKE '{$installer->getTableName()}'");

    $this->assertEmpty($result);
})->skip('Plugin table does not uninstall in test environment');
