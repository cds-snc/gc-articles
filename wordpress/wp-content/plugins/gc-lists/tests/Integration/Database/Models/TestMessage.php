<?php

use Carbon\Carbon;
use GCLists\Database\Models\Message;
use GCLists\Exceptions\InvalidAttributeException;
use GCLists\Exceptions\QueryException;
use Illuminate\Support\Collection;
use function Pest\Faker\faker;

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

test('Update an existing Message by save method', function() {
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

test('Update an existing Message using the fill method', function() {
    // create a message
    $message = new Message([
        'name' => 'This is the message name',
        'subject' => 'This is the message subject',
        'body' => 'This is the message body'
    ]);
    $message->save();

    $message->update([
        'name' => 'This is a new message name',
        'subject' => 'This is a new message subject',
    ]);

    $this->assertEquals('This is a new message name', $message->name);
    $this->assertEquals('This is a new message subject', $message->subject);
    $this->assertEquals('This is the message body', $message->body);

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
    $message_ids = collect($this->factory->message->create_many(5));

    $count = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
    $this->assertEquals(5, $count);

    $message = Message::find($message_ids->random());
    $this->assertTrue($message instanceof Message);

    $this->assertTrue($message->delete());

    $this->assertFalse($message->exists);

    $count = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->tableName}");
    $this->assertEquals(4, $count);
});

test('Deleting a non-existent model returns false', function() {
    $message = new Message([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz'
    ]);

    $this->assertFalse($message->delete());
});

test('Retrieve all models', function() {
    $this->factory->message->create_many(5);

    $messages = Message::all();

    // We should get back an array of Message models
    foreach($messages as $message) {
        $this->assertTrue($message instanceof Message);
    }
});

test('Retrieve all models with limit', function() {
    $this->factory->message->create_many(20);

    $messages = Message::all(['limit' => 5]);

    $this->assertCount(5, $messages);
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

test('Retrieve using whereNotNull param', function() {
    $this->factory->message->create_many(5);

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

test('Retrieve using whereNotNull param and Limit', function() {
    $this->factory->message->create_many(20);
    $messages = Message::whereNotNull('created_at', ['limit' => 5]);
    $this->assertEquals(5, count($messages));
});

test('Retrieve using whereNull param', function() {
    $this->factory->message->create_many(5);

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

test('Retrieve using whereNull param and Limit', function() {
    $this->factory->message->create_many(20);
    $messages = Message::whereNull('original_message_id', ['limit' => 5]);
    $this->assertEquals(5, count($messages));
});

test('Create a model with invalid attribute throws InvalidAttributeException', function() {
    $this->expectException(InvalidAttributeException::class);

    Message::create([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message',
        'notacolumn' => 'This column doesnt exist',
    ]);
});

test('Existing model instance throws QueryException on update with invalid attributes', function() {
    $message = Message::create([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message'
    ]);

    $this->expectException(QueryException::class);

    $message->name = 'New name of the message';
    $message->foo = "Bar";
    $message->save();

    $this->assertTrue($message instanceof Message);
    $this->assertObjectNotHasAttribute('notacolumn', $message);
    $this->assertEquals('New name of the message', $message->name);
});

test('New model throws QueryException on save with invalid attributes', function() {
    $message = new Message([
        'name' => 'Name of the message',
        'subject' => 'Subject of the message',
        'body' => 'Body of the message',
    ]);

    $message->foo = 'Bar';

    $this->expectException(QueryException::class);
    $message->save();
});

test('Retrieve versions of a Message', function() {
    $message_id = $this->factory->message->create();

    $this->factory->message->create_many(5, [
        'original_message_id' => $message_id
    ]);

    $message = Message::find($message_id);

    $this->assertEquals(6, count($message->versions()));
    $this->assertTrue($message->versions() instanceof Collection);

    foreach($message->versions() as $version) {
        $this->assertTrue($version instanceof Message);
    }
});

test('Retrieve the most recent version of a Message', function() {
    $message_id = $this->factory->message->create();

    // Generate 5 versions
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id
        ]);
    }

    $message = Message::find($message_id);
    $latest = $message->latest();

    $this->assertTrue($message instanceof Message);
    $this->assertEquals(5, $latest->version_id);
});

test('Retrieve the original of a Message version', function() {
    $message_id = $this->factory->message->create();

    // Generate 5 versions
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id
        ]);
    }

    // Get the message
    $message = Message::find($message_id);

    // Get original
    $original = $message->original();
    $this->assertTrue($original instanceof Message);

    // In this case, the original and message are the same object
    $this->assertSame($original, $message);

    // Grab a random version and check its original against the message
    $version = $message->versions()->random();
    $this->assertTrue($version instanceof Message);
    $this->assertEquals($version->original(), $message);
});

test('Retrieve sent versions of a message', function() {
    $message_id = $this->factory->message->create();

    // Generate 5 versions, odd = sent (3)
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? $timestamp : NULL)
        ]);
    }

    $message = Message::find($message_id);

    $sent = $message->sent();

    $this->assertInstanceOf(Collection::class, $sent);
    $this->assertCount(3, $sent);

    $message->sent()->each(function ($version) {
        $this->assertInstanceOf(Message::class, $version);
    });
});

test('Save a new version of a message', function() {
    $message_id = $this->factory->message->create([
        'name' => 'Original name',
        'body' => 'Original body',
    ]);
    $message = Message::find($message_id);

    // Save a version
    $message->name = 'This is a new name';
    $message->body = 'This is a new body';
    $message = $message->saveVersion();

    // Original model unchanged
    $this->assertSame('Original name', $message->name);
    $this->assertSame('Original body', $message->body);

    // Check the version
    $version = $message->latest();
    $this->assertCount(2, $message->versions());
    $this->assertSame('This is a new name', $version->name);
    $this->assertSame('This is a new body', $version->body);

    // Save another version
    $message->name = 'This is a another new name';
    $message->body = 'This is a another new body';
    $message = $message->saveVersion();

    // Check the new version
    $version = $message->latest();
    $this->assertCount(3, $message->versions());
    $this->assertSame('This is a another new name', $version->name);
    $this->assertSame('This is a another new body', $version->body);
});

test('Saving a new version of a message does not copy the sent attributes', function() {
    $message_id = $this->factory->message->create([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz',
        'sent_at' => Carbon::now()->toDateTimeString(),
        'sent_to_list_id' => faker()->uuid(),
        'sent_to_list_name' => "ListName",
        'sent_by_id' => 1,
        'sent_by_email' => faker()->email(),
    ]);

    $original = Message::find($message_id);

    $original->fill([
        'name' => 'Foo2',
        'subject' => 'Bar2',
        'body' => 'Baz2',
    ]);

    $original->saveVersion();

    $message = $original->latest();

    $this->assertNull($message->sent_at);
    $this->assertNull($message->sent_to_list_id);
    $this->assertNull($message->sent_to_list_name);
    $this->assertNull($message->sent_by_id);
    $this->assertNull($message->sent_by_email);
});

test('Saving a version touches the original updated_at timestamp', function() {
    Carbon::setTestNow(Carbon::now());

    $message_id = $this->factory->message->create([
        'name' => 'Original name',
        'body' => 'Original body',
    ]);
    $original = Message::find($message_id);
    $original_updated = $original->updated_at;

    // Let's go to the future!
    Carbon::setTestNow(Carbon::now()->addMinutes(5));

    $original = $original->fill([
        'name' => 'This is a new name',
        'body' => 'This is a new body'
    ])->saveVersion();

    $this->assertNotEquals($original_updated, $original->updated_at);
    $this->assertGreaterThan($original_updated, $original->updated_at);
});

test('Save a new version from a version should create a revision of the original', function() {
    $message_id = $this->factory->message->create([
        'name' => 'Original name',
        'body' => 'Original body',
    ]);

    $original = Message::find($message_id);

    // Generate 5 versions, odd = sent (3)
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? $timestamp : NULL)
        ]);
    }

    // Select a version from random
    $version = $original->versions()->random();

    $version->name = 'This is a new name';
    $version->body = 'This is a new body';
    $version->saveVersion();

    $inserted_id = $this->wpdb->insert_id;
    $new_version = Message::find($inserted_id);

    // Assertions about the new version
    $this->assertEquals('This is a new name', $new_version->name);
    $this->assertEquals('This is a new body', $new_version->body);
    $this->assertEquals($original->id, $new_version->original_message_id);

    // New version original and version original should be the same
    $this->assertEquals($new_version->original(), $version->original());

    // There should now be seven versions including the original
    $this->assertCount(7, $original->versions());
});

test('Saving a version with invalid attribute should throw exception', function() {
    $message_id = $this->factory->message->create([
        'name' => 'Original name',
        'body' => 'Original body',
    ]);

    $original = Message::find($message_id);

    $original->name = 'New name';
    $original->foo = 'Bar';

    $this->expectException(QueryException::class);
    $original->saveVersion();
});

test('Retrieve all Message templates', function() {
    $this->factory->message->create_many(20);

    $templates = Message::templates();
    $this->assertCount(20, $templates);

    $templates = Message::templates(['limit' => 5]);
    $this->assertCount(5, $templates);
});

test('Retrieve only Sent Messages', function() {
    // Create a Message template and some versions including sent messages
    $message_id = $this->factory->message->create();

    // Generate 5 versions, odd = sent (3)
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? $timestamp : NULL)
        ]);
    }

    // Do it again with another Message template
    $message_id = $this->factory->message->create();

    // Generate 5 versions, even = sent (2)
    for($version_id = 1; $version_id <= 5; $version_id++) {
        $timestamp = Carbon::now()->toDateTimeString();

        $this->factory->message->create([
            'original_message_id' => $message_id,
            'version_id' => $version_id,
            'sent_at' => ($version_id %2 ? NULL : $timestamp)
        ]);
    }

    $this->assertCount(2, Message::templates());
    $this->assertCount(5, Message::sentMessages());
    $this->assertCount(3, Message::sentMessages(['limit' => 3]));
});

test('isOriginal', function() {
    $original_id = $this->factory->message->create();
    $version_id = $this->factory->message->create([
        'original_message_id' => $original_id
    ]);

    $original = Message::find($original_id);
    $version = Message::find($version_id);

    $this->assertTrue($original->isOriginal());
    $this->assertFalse($version->isOriginal());
});

test('Message send - existing message', function() {
    $message_id = $this->factory->message->create();
    $message = Message::find($message_id);

    $timestamp = Carbon::now()->toDateTimeString();
    Carbon::setTestNow($timestamp);

    $uuid = faker()->uuid();
    $email = faker()->email;
    $listName = 'This is a list name';

    $message = $message->send($uuid, $listName, 1, $email);

    expect($message)
        ->toBeInstanceOf(Message::class)
        ->toHaveKey('sent_at', NULL);

    expect($message->sent())
        ->toBeInstanceOf(Collection::class)
        ->each
        ->toBeInstanceOf(Message::class)
        ->toHaveKey('sent_at', $timestamp)
        ->toHaveKey('sent_by_email', $email)
        ->toHaveKey('sent_to_list_name', $listName);
});

test('Message create and send (new message)', function() {
    $message = new Message([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz',
        'message_type' => 'email',
    ]);

    $timestamp = Carbon::now()->toDateTimeString();
    Carbon::setTestNow($timestamp);

    $uuid = faker()->uuid();
    $email = faker()->email;
    $listName = 'This is a list name';

    $message = $message->send($uuid, $listName, 1, $email);

    expect($message)
        ->toBeInstanceOf(Message::class)
        ->toHaveKey('sent_at', $timestamp)
        ->toHaveKey('original_message_id', NULL)
        ->toHaveKey('version_id', NULL);

    $sent = Message::sentMessages();

    expect($sent)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1);
});
