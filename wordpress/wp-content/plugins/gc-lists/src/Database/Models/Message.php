<?php

namespace GCLists\Database\Models;

use Illuminate\Support\Collection;

class Message extends Model
{
    protected string $tableSuffix = "messages";

    protected array $visible = [
        'id',
        'name',
        'subject',
        'body',
        'message_type',
        'sent_at',
        'sent_to_list_name',
        'sent_by_email',
        'original_message_id',
        'version_id',
        'created_at',
        'updated_at',
    ];

    protected array $fillable = [
        'name',
        'subject',
        'body',
        'message_type',
    ];

    public function send()
    {
        //
    }

    /**
     * Get sent versions of the current Message
     *
     * @return Collection
     */
    public function sent(): Collection
    {
        return $this->versions()->filter(function ($item) {
            return (bool)$item->attributes["sent_at"];
        });
    }

    /**
     * Get all versions of the current Message
     *
     * @return Collection|null
     */
    public function versions(): ?Collection
    {
        return static::whereEquals(['original_message_id' => $this->getAttribute('id')]);
    }

    /**
     * Get the Original message
     *
     * @return Message
     */
    public function original(): Message
    {
        if (!$this->original_message_id) {
            return $this;
        }

        $original = static::whereEquals(['id' => $this->getAttribute('original_message_id')]);

        return $original->first();
    }

    /**
     * Get the latest version of the current message
     *
     * @return static
     */
    public function latest(): static
    {
        if ($versions = $this->versions()) {
            return $versions->last();
        }

        return $this;
    }

    /**
     * Get the latest version_id
     *
     * @return int
     */
    public function getLastVersionId(): int
    {
        return $this->latest()->getAttribute('version_id') ?? 0;
    }

    /**
     * Create a new version of this message
     *
     * @return $this
     */
    public function saveVersion(): static
    {
        $original = $this->original();

        $latest_version = $original->getLastVersionId();
        $latest_version++;

        $attributes = $this->getAttributes();

        $version = new static();

        $version->forceFill(array_merge($original->getFillableFromArray($attributes), [
            'original_message_id' => $original->getAttribute('id'),
            'version_id' => $latest_version,
        ]));

        $version->performInsert();

        // Return a fresh model
        return $this->fresh();
    }

    /**
     * Retrieve Message templates
     *
     * @param  array  $options
     * @return Collection|null
     */
    public static function templates(array $options = []): ?Collection
    {
        return static::whereNull('original_message_id', $options);
    }

    /**
     * Retrieve sent messages
     *
     * @param  array  $options
     * @return Collection|null
     */
    public static function sentMessages(array $options = []): ?Collection
    {
        return static::whereNotNull(['original_message_id', 'sent_at'], $options);
    }
}
