<?php

declare(strict_types=1);

namespace GCLists\Database\Models;

class Model
{
    protected $wpdb;
    protected string $tableName;
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $this->wpdb->prefix . $this->tableName;

        $this->fill($attributes);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        return $this->attributes[$key] ?? null;
    }

    /**
     *  Actions
     */

    public static function find($id)
    {
        $instance = new static();

        $result = $instance->wpdb->get_row(
            $instance->wpdb->prepare("SELECT * FROM {$instance->tableName} WHERE id = %s", $id)
        );

        if (!$result) {
            return null;
        }

        return new Message((array) $result);
    }

    public static function delete($id)
    {
        $instance = new static();

        return $instance->wpdb->delete(
            $instance->tableName,
            ['id' => $id]
        );
    }

    public function save(array $options = [])
    {
        //
    }

    public static function update(array $attributes = [], array $options = [])
    {
        $instance = new static();

        return $instance->fill($attributes)->save($options);
    }
}
