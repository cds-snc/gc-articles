<?php

declare(strict_types=1);

namespace GCLists\Database\Models;

use Carbon\Carbon;

class Model
{
    public int $id;
    public bool $exists = false;

    protected string $tableName;
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . $this->table;

        $this->fill($attributes);
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected static function loadModels($data): array
    {
        $class = get_called_class();

        $func = function ($data) use ($class) {
            return new $class((array)$data);
        };

        return array_map($func, $data);
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
        global $wpdb;
        $time = Carbon::now()->timestamp;
        $this->updateUpdatedTimestamp($time);

        $wpdb->update($this->tableName, $this->attributes, [
            'id' => $this->id,
        ]);

        return $this;
    }

    protected function performInsert()
    {
        global $wpdb;
        $time = Carbon::now()->timestamp;
        $this->updateCreatedTimestamp($time);
        $this->updateUpdatedTimestamp($time);

        $wpdb->insert($this->tableName, $this->attributes);

        $this->exists = true;
        $this->id = $wpdb->insert_id;

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

    public function __serialize(): array
    {
        $model = [];
        foreach ($this->attributes as $attribute => $value) {
            $model[$attribute] = $value;
        }

        return $model;
    }

    /**
     * Instance actions
     */
    public function delete()
    {
        global $wpdb;

        return $wpdb->delete(
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

    public function asJson()
    {
        return json_encode($this->attributes);
    }

    /**
     *  Static actions
     */

    public static function find($id)
    {
        global $wpdb;
        $instance = new static();

        $data = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$instance->tableName} WHERE id = %s", $id)
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
        global $wpdb;

        $instance = new static();

        $data = $wpdb->get_results(
            "SELECT * FROM {$instance->tableName}"
        );

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }

    public static function whereEquals(array $params)
    {
        global $wpdb;
        $instance = new static();

        $query = "SELECT * FROM {$instance->tableName} WHERE 1=1";

        foreach ($params as $key => $value) {
            $query .= $wpdb->prepare(" AND {$key} = %s", $value);
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }

    public static function whereNotNull($columns)
    {
        global $wpdb;

        if (is_string($columns)) {
            $columns = [$columns];
        }

        $instance = new static();

        $query = "SELECT * FROM {$instance->tableName} WHERE 1=1";

        foreach ($columns as $column) {
            $query .= " AND {$column} IS NOT NULL";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }

    public static function whereNull($columns)
    {
        global $wpdb;

        if (is_string($columns)) {
            $columns = [$columns];
        }

        $instance = new static();

        $query = "SELECT * FROM {$instance->tableName} WHERE 1=1";

        foreach ($columns as $column) {
            $query .= " AND {$column} IS NULL";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }
}
