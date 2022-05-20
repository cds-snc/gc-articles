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

    /**
     * Mark a message as sent
     *
     * @param  string  $sent_to_list_id
     * @param  string  $sent_to_list_name
     * @param  int  $sent_by_id
     * @param  string  $sent_by_email
     *
     * @return $this
     */
    public function send(string $sent_to_list_id, string $sent_to_list_name, int $sent_by_id, string $sent_by_email): static
    {
        $timestamp = $this->freshTimestamp();

        $this->updateUpdatedTimestamp($timestamp);

        $this->forceFill([
            'sent_at' => $this->freshTimestamp(),
            'sent_to_list_id' => $sent_to_list_id,
            'sent_to_list_name' => $sent_to_list_name,
            'sent_by_id' => $sent_by_id,
            'sent_by_email' => $sent_by_email
        ]);

        // @TODO: need to wire this up to the actual send
        return $this->saveVersion();
    }

    /**
     * Get sent versions of the current Message
     *
     * @param  array  $options
     * @return Collection
     */
    public function sent(array $options = []): Collection
    {
        $sent = $this->versions()->filter(function ($item) {
            return (bool)$item->attributes["sent_at"];
        });

        if (isset($options['limit'])) {
            return $sent->take($options['limit']);
        }

        return $sent;
    }

    /**
     * Get all versions of the current Message
     *
     * @param  array  $options
     * @return Collection|null
     */
    public function versions(array $options = []): ?Collection
    {
        return static::whereEquals(['original_message_id' => $this->getAttribute('id')], $options);
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
        $versions = $this->versions();

        if ($versions->count()) {
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
        // a version should always reference the original
        $original = $this->original();

        // get the latest version_id to increment
        $latest_version = $original->getLastVersionId();
        $latest_version++;

        // new model instance for the version
        $version = new static();

        $attributes = $this->getAttributes();

        // Don't copy the id obvi
        unset($attributes['id']);

        $version->forceFill(array_merge($attributes, [
            'original_message_id' => $original->getAttribute('id'),
            'version_id' => $latest_version,
        ]));

        $version->performInsert();

        // Once we've created the version, touch the updated_at on a fresh original
        $original->fresh()->touch();

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
