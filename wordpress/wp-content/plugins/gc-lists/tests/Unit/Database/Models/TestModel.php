<?php

use GCLists\Database\Models\Model;

test('toArray', function() {
    $model = new ModelStub;
    $model->title = "Foo";
    $model->message = "Bar";

    $array = $model->toArray();

    $this->assertIsArray($array);
    $this->assertSame('Foo', $array['title']);
    $this->assertSame('Bar', $array['message']);
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
