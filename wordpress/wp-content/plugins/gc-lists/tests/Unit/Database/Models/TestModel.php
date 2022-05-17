<?php

use GCLists\Database\Models\Model;
use GCLists\Exceptions\InvalidAttributeException;
use GCLists\Exceptions\JsonEncodingException;

test('toArray', function() {
    $model = new ModelStub;
    $model->title = "Foo";
    $model->message = "Bar";

    $array = $model->toArray();

    $this->assertIsArray($array);
    $this->assertSame('Foo', $array['title']);
    $this->assertSame('Bar', $array['message']);
});

test('toJson fails on unencodeable data', function() {
    $model = new ModelStub;
    $model->foo = "b\xF8r";

    $this->expectException(JsonEncodingException::class);
    $model->toJson();
});

test('Attribute manipulation', function() {
    $model = new ModelStub;
    $model->title = "Foo";
    $model->message = "Bar";
    $this->assertSame('Foo', $model->title);
    $this->assertSame('Bar', $model->message);

    $model->message = "Baz";
    $this->assertSame('Baz', $model->message);

    $this->assertSame([
        'title' => 'Foo',
        'message'  => 'Baz'
    ], $model->getAttributes());
});

test('Fill with attributes', function() {
    $model = new ModelStub;
    $model->fill([
        'title' => 'This is a name',
        'message' => 'This is a message'
    ]);

    $this->assertIsObject($model);
    $this->assertTrue($model instanceof ModelStub);
});

test('Fill with invalid attribute', function() {
    $model = new ModelStub;

    $this->expectException(InvalidAttributeException::class);
    $model->fill([
        'title' => 'This is a name',
        'message' => 'This is a message',
        'notvalid' => 'This is not a fillable attribute'
    ]);
});

test('Serialize model toJson', function() {
    $model = new ModelStub;

    $model->fill([
        'title' => 'This is a name',
        'message' => 'This is a message'
    ]);

    $json = $model->toJson();
    expect($json)
        ->toBeJson()
        ->json()
        ->toHaveKeys(['title', 'message']);
});

test('getFillableFromArray', function() {
    $model = new ModelStub;
    $array = $model->getFillableFromArray([
        'title' => 'Foo',
        'message' => 'Bar',
        'notvalid' => 'Baz',
    ]);

    expect($array)
        ->toBeArray()
        ->toHaveKeys(['title', 'message'])
        ->not()->toHaveKey('notvalid');
});

test('getAttribute', function() {
    $model = new ModelStub([
        'title' => 'Foo',
        'message' => 'Bar',
    ]);

    $this->assertEquals('Foo', $model->getAttribute('title'));
    $this->assertEquals('Bar', $model->getAttribute('message'));
});

/**
 * Model stub for testing the base Model
 */
class ModelStub extends Model {
    protected string $tableSuffix = "tablenamesuffix";

    protected array $visible = [
        'title',
        'message',
    ];

    protected array $fillable = [
        'title',
        'message',
    ];
}
