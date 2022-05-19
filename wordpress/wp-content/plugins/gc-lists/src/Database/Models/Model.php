<?php

declare(strict_types=1);

namespace GCLists\Database\Models;

use Carbon\Carbon;
use GCLists\Exceptions\InvalidAttributeException;
use GCLists\Exceptions\JsonEncodingException;
use GCLists\Exceptions\QueryException;
use Illuminate\Support\Collection;
use JsonSerializable;

class Model implements JsonSerializable
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
    public string $tableName;

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
     * @return array
     */
    protected static function loadModelsFromDbResults($data): array
    {
        $class = get_called_class();

        $func = function ($data) use ($class) {
            $model = new $class();
            $model->exists = true;
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
    public function forceFill(array $attributes): static
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
    protected function isFillable($key): bool
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
    public function getFillableFromArray(array $attributes): array
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

        return $this->getAttributes()[$key] ?? null;
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Perform a model update
     *
     * @return $this
     */
    protected function performUpdate(): static
    {
        global $wpdb;
        $wpdb->suppress_errors(true);

        $time = $this->freshTimestamp();
        $this->updateUpdatedTimestamp($time);

        $updated = $wpdb->update($this->tableName, $this->getAttributes(), [
            'id' => $this->id,
        ]);

        if (false === $updated) {
            throw new QueryException($wpdb->last_error);
        }

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
        $wpdb->suppress_errors(true);

        $time = $this->freshTimestamp();
        $this->updateCreatedTimestamp($time);
        $this->updateUpdatedTimestamp($time);

        $inserted = $wpdb->insert($this->tableName, $this->getAttributes());

        if (false === $inserted) {
            throw new QueryException($wpdb->last_error);
        }

        $this->exists = true;
        $this->id = $wpdb->insert_id;

        return $this;
    }

    /**
     * Reload a fresh model instance from the database
     *
     * @return static
     */
    public function fresh(): static
    {
        if (!$this->exists) {
            return $this;
        }

        return static::find($this->getAttribute('id'));
    }

    /**
     * Update the created_at timestamp on the model
     *
     * @param $time
     * @return Model
     */
    protected function updateCreatedTimestamp($time): static
    {
        $this->created_at = $time;
        return $this;
    }

    /**
     * Update the updated_at timestamp on the model
     *
     * @param $time
     * @return Model
     */
    protected function updateUpdatedTimestamp($time): static
    {
        $this->updated_at = $time;
        return $this;
    }

    /**
     * Get a fresh timestamp
     *
     * @return string
     */
    protected function freshTimestamp()
    {
        return Carbon::now()->toDateTimeString();
    }

    /**
     * Update the model's updated_at timestamp
     */
    protected function touch(): static
    {
        $this->updateUpdatedTimestamp($this->freshTimestamp());

        return $this->save();
    }

    /**
     * Delete the model from the database
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }

        global $wpdb;

        $wpdb->delete(
            $this->tableName,
            ['id' => $this->attributes["id"]]
        );

        $this->exists = false;

        return true;
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
     * Update an existing model with provided attributes
     *
     * @param  array  $attributes
     * @return $this
     */
    public function update(array $attributes): static
    {
        if (!$this->exists) {
            return false;
        }

        return $this->fill($attributes)->save();
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws JsonEncodingException
     */
    public function toJson($options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Find a model by ID
     *
     * @param $id
     *
     * @return mixed
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
     * @return Collection|null
     */
    public static function all(array $options = []): ?Collection
    {
        global $wpdb;
        $instance = new static();

        $query = "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName}";

        if (isset($options['limit'])) {
            $query .= " LIMIT {$options['limit']}";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return collect();
        }

        return collect(self::loadModelsFromDbResults($data));
    }

    /**
     * Simple query filter accepts an array of attribute => value pairs.
     *
     * @param  array  $params
     * @return Collection|null
     */
    public static function whereEquals(array $params, array $options = []): ?Collection
    {
        global $wpdb;
        $instance = new static();

        $query = "SELECT {$instance->getVisibleColumns()} FROM {$instance->tableName} WHERE 1=1";

        foreach ($params as $key => $value) {
            $query .= $wpdb->prepare(" AND {$key} = %s", $value);
        }

        if (isset($options['limit'])) {
            $query .= " LIMIT {$options['limit']}";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return collect();
        }

        return collect(self::loadModelsFromDbResults($data));
    }

    /**
     * Simple NOT NULL query accepts an array of attributes that must be NOT NULL
     *
     * @param $columns
     * @return Collection|null
     */
    public static function whereNotNull($columns, array $options = []): ?Collection
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

        if (isset($options['limit'])) {
            $query .= " LIMIT {$options['limit']}";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return collect();
        }

        return collect(self::loadModelsFromDbResults($data));
    }

    /**
     * Simple IS NULL query accepts an array of attributes that must be NULL
     *
     * @param $columns
     * @return Collection|null
     */
    public static function whereNull($columns, array $options = []): ?Collection
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

        if (isset($options['limit'])) {
            $query .= " LIMIT {$options['limit']}";
        }

        $data = $wpdb->get_results($query);

        if (!$data) {
            return collect();
        }

        return collect(self::loadModelsFromDbResults($data));
    }

    /**
     * Returns the Model instance as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getAttributes();
    }

    /**
     * Convert the object into something JSON serializable
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
