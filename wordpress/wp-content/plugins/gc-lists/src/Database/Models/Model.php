<?php

declare(strict_types=1);

namespace GCLists\Database\Models;

use Carbon\Carbon;
use GCLists\Exceptions\InvalidAttributeException;

class Model
{
    /**
     * The visible columns of the model (for queries)
     *
     * @var array
     */
    protected array $visible;

    /**
     * The attributes that are fillable on the model
     *
     * @var array
     */
    protected array $fillable;

    /**
     * Indicates if the model exists (edit) or not (create)
     *
     * @var bool
     */
    public bool $exists = false;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected string $tableName;

    /**
     * The model's attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Create a new model instance
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . $this->tableSuffix;

        $this->fill($attributes);
    }

    /**
     * Take an array of db results and turn it into an array of models
     *
     * @param $data
     *
     * @return array
     */
    protected static function loadModels($data): array
    {
        $class = get_called_class();

        $func = function ($data) use ($class) {
            $model = new $class();
            return $model->forceFill((array)$data);
        };

        return array_map($func, $data);
    }

    /**
     * Retrieve an imploded (comma-separated) list of visible columns for querying
     *
     * @return string
     */
    protected function getVisibleColumns(): string
    {
        return implode(',', $this->visible);
    }

    /**
     * Dynamically retrieve attributes on the model
     *
     * @param $key
     * @return mixed|void|null
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Fill the model with an array of attributes
     *
     * @param  array  $attributes
     * @return $this
     */
    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            } else {
                throw new InvalidAttributeException(
                    sprintf(
                        '[%s] does not exist as a fillable property on the model [%s]',
                        $key,
                        get_class($this)
                    )
                );
            }
        }

        return $this;
    }

    /**
     * Fill the model with an array of attributes. Force mass assignment.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function forceFill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Determine if the given attribute can be mass assigned
     *
     * @param $key
     * @return bool
     */
    protected function isFillable($key)
    {
        if (in_array($key, $this->fillable)) {
            return true;
        }
        return false;
    }

    /**
     * Get fillable attributes from array
     *
     * @param  array  $attributes
     * @return array
     */
    protected function getFillableFromArray(array $attributes): array
    {
        if (count($this->fillable)) {
            return array_intersect_key($attributes, array_flip($this->fillable));
        }

        return $attributes;
    }

    /**
     * Set an attribute on the model
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get an attribute on the model
     *
     * @param $key
     * @return mixed|void|null
     */
    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        return $this->attributes[$key] ?? null;
    }

    /**
     * Perform a model update
     *
     * @return $this
     */
    protected function performUpdate(): static
    {
        global $wpdb;
        $time = Carbon::now()->timestamp;
        $this->updateUpdatedTimestamp($time);

        $wpdb->update($this->tableName, $this->getFillableFromArray($this->attributes), [
            'id' => $this->id,
        ]);

        return $this;
    }

    /**
     * Perform a model insert
     *
     * @return $this
     */
    protected function performInsert(): static
    {
        global $wpdb;
        $time = Carbon::now()->timestamp;
        $this->updateCreatedTimestamp($time);
        $this->updateUpdatedTimestamp($time);

        $wpdb->insert($this->tableName, $this->getFillableFromArray($this->attributes));

        $this->exists = true;
        $this->id = $wpdb->insert_id;

        return $this;
    }

    /**
     * Update the created_at timestamp on the model
     *
     * @param $time
     */
    protected function updateCreatedTimestamp($time)
    {
        $this->created_at = $time;
    }

    /**
     * Update the updated_at timestamp on the model
     *
     * @param $time
     */
    protected function updateUpdatedTimestamp($time)
    {
        $this->updated_at = $time;
    }

    /**
     * How to serialize the model
     *
     * @return array
     */
    public function __serialize(): array
    {
        // @TODO: this should probably use $model->visible
        $model = [];
        foreach ($this->attributes as $attribute => $value) {
            $model[$attribute] = $value;
        }

        return $model;
    }

    /**
     * Delete the model from the database
     *
     * @return mixed
     */
    public function delete(): mixed
    {
        global $wpdb;

        return $wpdb->delete(
            $this->tableName,
            ['id' => $this->attributes["id"]]
        );
    }

    /**
     * Save the model to the database
     *
     * @return $this
     */
    public function save(): static
    {
        if ($this->exists) {
            return $this->performUpdate();
        } else {
            return $this->performInsert();
        }
    }

    /**
     * Serialize the model to Json
     *
     * @return false|string
     */
    public function asJson(): bool|string
    {
        // @TODO: This should use $model->visible
        return json_encode($this->attributes);
    }


    /**
     * Find a model by ID
     *
     * @param $id
     * @return mixed|null
     */
    public static function find($id): mixed
    {
        global $wpdb;
        $instance = new static();

        $data = $wpdb->get_row(
            $wpdb->prepare("SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName} WHERE id = %s", $id)
        );

        if (!$data) {
            return null;
        }

        $class = get_called_class();

        $model = new $class();
        $model->forceFill((array) $data);
        $model->exists = true;

        return $model;
    }

    /**
     * Create a model from attributes
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = []): static
    {
        $instance = new static();

        $instance->fill($attributes)->save();

        return $instance;
    }

    /**
     * Get all models from db
     *
     * @return array|null
     */
    public static function all()
    {
        global $wpdb;
        $instance = new static();

        $data = $wpdb->get_results(
            "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName}"
        );

        if (!$data) {
            return null;
        }

        // @TODO: should filter the columns by $visible
        return collect($data);
    }

    /**
     * Simple query filter accepts an array of attribute => value pairs.
     *
     * @param  array  $params
     * @return array|null
     */
    public static function whereEquals(array $params): ?array
    {
        global $wpdb;
        $instance = new static();

        $query = "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName} WHERE 1=1";

        foreach ($params as $key => $value) {
            $query .= $wpdb->prepare(" AND {$key} = %s", $value);
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }

    /**
     * Simple NOT NULL query accepts an array of attributes that must be NOT NULL
     *
     * @param $columns
     * @return array|null
     */
    public static function whereNotNull($columns): ?array
    {
        global $wpdb;

        if (is_string($columns)) {
            $columns = [$columns];
        }

        $instance = new static();

        $query = "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName} WHERE 1=1";

        foreach ($columns as $column) {
            $query .= " AND {$column} IS NOT NULL";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return null;
        }

        return self::loadModels($data);
    }

    /**
     * Simple IS NULL query accepts an array of attributes that must be NULL
     *
     * @param $columns
     * @return array|null
     */
    public static function whereNull($columns): ?array
    {
        global $wpdb;

        if (is_string($columns)) {
            $columns = [$columns];
        }

        $instance = new static();

        $query = "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName} WHERE 1=1";

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
