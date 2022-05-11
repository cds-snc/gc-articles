<?php

declare(strict_types=1);

namespace GCLists\Database\Models;

use Carbon\Carbon;

class Model
{
    public int $id;
    public bool $exists = false;

    protected $wpdb;
    protected string $tableName;
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $this->wpdb->prefix . $this->table;

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

    protected function performUpdate()
    {
        $time = Carbon::now()->timestamp;
        $this->updateUpdatedTimestamp($time);

        $this->wpdb->update($this->tableName, $this->attributes, [
            'id' => $this->id,
        ]);

        return $this;
    }

    protected function performInsert()
    {
        $time = Carbon::now()->timestamp;
        $this->updateCreatedTimestamp($time);
        $this->updateUpdatedTimestamp($time);

        $this->wpdb->insert($this->tableName, $this->attributes);

        $this->exists = true;
        $this->id = $this->wpdb->insert_id;

        return $this;
    }

    protected function updateCreatedTimestamp($time)
    {
        $this->created_at = $time;
    }

    protected function updateUpdatedTimestamp($time)
    {
        $this->updated_at = $time;
    }

    /**
     * Instance actions
     */
    public function delete()
    {
        return $this->wpdb->delete(
            $this->tableName,
            ['id' => $this->attributes["id"]]
        );
    }

    public function save()
    {
        if ($this->exists) {
            return $this->performUpdate();
        } else {
            return $this->performInsert();
        }
    }

    /**
     *  Static actions
     */

    public static function find($id)
    {
        $instance = new static();

        $data = $instance->wpdb->get_row(
            $instance->wpdb->prepare("SELECT * FROM {$instance->tableName} WHERE id = %s", $id)
        );

        if (!$data) {
            return null;
        }

        $class = get_called_class();

        $model = new $class((array) $data);
        $model->exists = true;
        return $model;
    }

    public static function create(array $attributes = [])
    {
        $instance = new static();

        $instance->fill($attributes)->save();

        return $instance;
    }

    public static function all()
    {
        $instance = new static();

        $data = $instance->wpdb->get_results(
            "SELECT * FROM {$instance->tableName}"
        );

        if (!$data) {
            return null;
        }

        $class = get_called_class();

        $func = function ($data) use ($class) {
            return new $class((array) $data);
        };

        return array_map($func, $data);
    }

    public static function whereEquals(array $params)
    {
        $instance = new static();

        $query = "SELECT * FROM {$instance->tableName} WHERE 1=1";

        foreach ($params as $key => $value) {
            $query .= $instance->wpdb->prepare(" AND {$key} = %s", $value);
        }

        $data = $instance->wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        $class = get_called_class();

        $func = function ($data) use ($class) {
            return new $class((array) $data);
        };

        return array_map($func, $data);
    }
}
