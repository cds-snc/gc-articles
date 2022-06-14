<?php

use GCLists\Database\Models\Message;

test('MessageFactory create a message', function() {
    $id = $this->factory->message->create([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz'
    ]);

    expect($id)->toBeInt();

    $message = Message::find($id);

    expect($message)
        ->toBeInstanceOf(Message::class)
        ->toHaveKey('name', 'Foo')
        ->toHaveKey('subject', 'Bar')
        ->toHaveKey('body', 'Baz');
});

test('MessageFactory update a message', function() {
    $object = $this->factory->message->create_and_get([
        'name' => 'Foo',
        'subject' => 'Bar',
        'body' => 'Baz'
    ]);

    $id = $this->factory->message->update_object($object, [
        'name' => 'Foo2',
        'subject' => 'Bar2',
        'body' => 'Baz2',
    ]);

    $message = Message::find($id);

    $this->assertEquals($id, $object->id);

    expect($message)
        ->toBeInstanceOf(Message::class)
        ->toHaveKey('name', 'Foo2');
});
