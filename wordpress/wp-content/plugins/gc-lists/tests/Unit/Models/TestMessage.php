<?php

use GCLists\Database\Models\Message;
use GCLists\Exceptions\InvalidAttributeException;

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

test('Retrieve whereNotNull param', function() {
    $message_ids = $this->factory->message->create_many(5);

    // one column
    $messages = Message::whereNotNull('created_at');

    $this->assertEquals(5, count($messages));

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }

    // multiple columns
    $messages = Message::whereNotNull(['created_at', 'updated_at']);

    $this->assertEquals(5, count($messages));

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }
});

test('Retrieve whereNull param', function() {
    $message_ids = $this->factory->message->create_many(5);

    // one column
    $messages = Message::whereNull('original_message_id');

    $this->assertEquals(5, count($messages));

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }

    // multiple columns
    $messages = Message::whereNull(['original_message_id']);

    $this->assertEquals(5, count($messages));

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }
});

test('Create a model with invalid attribute', function() {
    $this->expectException(InvalidAttributeException::class);

    Message::create([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message',
        'notacolumn' => 'This column doesnt exist',
    ]);
});

test('Model instance doesnt save invalid attributes', function() {
    $message = Message::create([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message'
    ]);

    $message->name = 'New name of the message';
    $message->notacolumn = "Huzzah";
    $message->save();

    $this->assertTrue($message instanceof Message);
    $this->assertObjectNotHasAttribute('notacolumn', $message);
    $this->assertEquals('New name of the message', $message->name);
});

test('Retrieve versions of a Message', function() {
    $message_id = $this->factory->message->create();

    $this->factory->message->create_many(5, [
        'original_message_id' => $message_id
    ]);

    $message = Message::find($message_id);

    $this->assertEquals(5, count($message->versions()));
    $this->assertIsArray($message->versions());

    foreach($message->versions() as $version) {
        $this->assertTrue($version instanceof Message);
    }
})->group('test');
