<?php

use GCLists\Database\Models\Message;

beforeEach(function() {
    global $wpdb;
    $this->wpdb = $wpdb;
    $this->tableName = $wpdb->prefix . "messages";
});

test('Test create Message and assign properties', function() {
    $message = new Message();
    $message->name = "This is the message name";
    $message->subject = "This is the message subject";
    $message->body = "This is the message body";

    $this->assertIsObject($message);
    $this->assertEquals('This is the message name', $message->name);
});

test('Create Message by instantiation', function() {
    $message = new Message([
        'name' => 'This is the message name',
        'subject' => 'This is the message subject',
        'body' => 'This is the message body'
    ]);

    $this->assertIsObject($message);
    $this->assertEquals('This is the message name', $message->name);
});

test('Create Message and Save', function() {
    $message = new Message([
        'name' => 'This is the message name',
        'subject' => 'This is the message subject',
        'body' => 'This is the message body'
    ]);

    $message->save();

    $this->assertIsObject($message);
    $this->assertTrue($message->exists);
});

test('Create Message using static create method', function() {
    $message = Message::create([
        'name' => 'This is the message name',
        'subject' => 'This is the subject',
        'body' => 'This is the body'
    ]);

    $this->assertIsObject($message);
    $this->assertTrue($message->exists);

    // let's retrieve it from the db and check
    $message = Message::find($message->id);
    $this->assertEquals('This is the message name', $message->name);
    $this->assertEquals('This is the subject', $message->subject);
});

test('Update an existing Message', function() {
    // create a message
    $message = new Message([
        'name' => 'This is the message name',
        'subject' => 'This is the message subject',
        'body' => 'This is the message body'
    ]);
    $message->save();

    // change a few properties
    $message->name = 'This is a new message name';
    $message->subject = 'This is a new message subject';
    $message->save();

    // let's get it back from the db
    $updated = Message::find($message->id);
    $this->assertEquals('This is a new message name', $updated->name);
    $this->assertEquals('This is a new message subject', $updated->subject);
});

test('Find a model', function() {
    $message_id = $this->factory->message->create();

    $message = Message::find($message_id);

    $this->assertIsObject($message);
});

test('Delete a model', function() {
    $message_ids = $this->factory->message->create_many(5);

    $count = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
    $this->assertEquals(5, $count);

    $message = Message::find($message_ids[1]);
    $this->assertTrue($message instanceof Message);

    $message->delete();

    $count = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
    $this->assertEquals(4, $count);
});

test('Retrieve all models', function() {
    $this->factory->message->create_many(5);

    $messages = Message::all();

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }
});

test('Retrieve some models', function() {
    $message_ids = $this->factory->message->create_many(5);

    // should only return one model
    $messages = Message::whereEquals([
        'id' => $message_ids[0]
    ]);

    $this->assertEquals(1, count($messages));

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }
});
