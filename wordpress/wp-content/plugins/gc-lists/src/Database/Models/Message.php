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

    protected array $excludeAttributesWhenCopying = [
        'sent_at',
        'sent_to_list_id',
        'sent_to_list_name',
        'sent_by_id',
        'sent_by_email'
    ];

    /**
     * Mark a message as sent
     *
     * @param  string   $sent_to_list_id
     * @param  string   $sent_to_list_name
     * @param  int      $sent_by_id
     * @param  string   $sent_by_email
     *
     * @return $this
     */
    public function send(
        string $sent_to_list_id,
        string $sent_to_list_name,
        int $sent_by_id,
        string $sent_by_email
    ): static {
        if ($this->exists) {
            $timestamp = $this->freshTimestamp();

            $this->updateUpdatedTimestamp($timestamp);

            $this->forceFill([
                'sent_at'           => $this->freshTimestamp(),
                'sent_to_list_id'   => $sent_to_list_id,
                'sent_to_list_name' => $sent_to_list_name,
                'sent_by_id'        => $sent_by_id,
                'sent_by_email'     => $sent_by_email
            ]);

            return $this->saveSentVersion();
        }

        $this->forceFill([
            'sent_at'           => $this->freshTimestamp(),
            'sent_to_list_id'   => $sent_to_list_id,
            'sent_to_list_name' => $sent_to_list_name,
            'sent_by_id'        => $sent_by_id,
            'sent_by_email'     => $sent_by_email
        ]);

        return $this->save();
    }

    /**
     * Get sent versions of the current Message
     *
     * @param  array  $options
     *
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
     *
     * @return Collection|null
     */
    public function versions(array $options = []): ?Collection
    {
        $original = $this->original();

        $versions = static::whereEquals(['original_message_id' => $original->getAttribute('id')], $options);
        $versions->prepend($original);

        return $versions;
    }

    /**
     * Get the Original message
     *
     * @return Message
     */
    public function original(): Message
    {
        if (! $this->original_message_id) {
            return $this;
        }

        $original = static::whereEquals(['id' => $this->getAttribute('original_message_id')]);

        return $original->first();
    }

    /**
     * Is this message the original?
     *
     * @return bool
     */
    public function isOriginal(): bool
    {
        return $this->getAttribute('original_message_id') === null;
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

        // Some attributes shouldn't be copied to the new version
        $unset = array_merge(['id'], $this->excludeAttributesWhenCopying);

        $attributes = array_diff_key($attributes, array_flip($unset));

        $version->forceFill(array_merge($attributes, [
            'original_message_id' => $original->getAttribute('id'),
            'version_id'          => $latest_version,
        ]));

        $version->performInsert();

        // Once we've created the version, touch the updated_at on a fresh original
        $original->fresh()->touch();

        // Return a fresh model
        return $this->fresh();
    }

    /**
     * Save sent version of a message. Ensures sent attributes are saved.
     *
     * @return $this
     */
    public function saveSentVersion(): static
    {
        // Don't exclude the sent_at/by/to attributes
        $this->excludeAttributesWhenCopying = [];

        return $this->saveVersion();
    }

    /**
     * Retrieve Message templates. If a message has multiple versions, retrieve the most recent name.
     *
     * @param  array  $options
     *
     * @return Collection|null
     */
    public static function templates(array $options = []): ?Collection
    {
        $messages = static::where([
            'original_message_id IS NULL',
            'sent_at IS NULL',
        ]);

        // In case the name of the template has been changed, display latest
        $messages->map(function ($message) {
            $message->name = $message->latest()->name;
        });

        // Apply sort if provided
        if (isset($options['sort']) && $options['sort'] === 'desc') {
            $messages = $messages->sortByDesc(function ($message) {
                return strtotime($message->created_at);
            })->values();
        }

        // Apply limit if provided
        if (isset($options['limit'])) {
            $messages = $messages->take((int)$options['limit']);
        }

        return $messages;
    }

    /**
     * Retrieve sent messages
     *
     * @param  array  $options
     *
     * @return Collection|null
     */
    public static function sentMessages(array $options = []): ?Collection
    {
        $messages = static::whereNotNull(['sent_at']);

        // Apply sort if provided
        if (isset($options['sort']) && $options['sort'] === 'desc') {
            $messages = $messages->sortByDesc(function ($message) {
                return strtotime($message->created_at);
            })->values();
        }

        // Apply limit if provided
        if (isset($options['limit'])) {
            $messages = $messages->take((int)$options['limit']);
        }

        return $messages;
    }
}
