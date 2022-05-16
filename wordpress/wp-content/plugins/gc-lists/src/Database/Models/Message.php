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
        // this will send a message
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
     * @return Message|null
     */
    public function original(): Message|null
    {
        if (!$this->original_message_id) {
            return null;
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
        $latest_version = $this->getLastVersionId();
        $latest_version++;

        $original = $this->getAttributes();

        $version = new static();

        $version->forceFill(array_merge($this->getFillableFromArray($original), [
            'original_message_id' => $this->getAttribute('id'),
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
        $sent = static::all()->filter(function ($message) {
            return (bool)$message->attributes['sent_at'];
        });

        if (isset($options['limit'])) {
            return $sent->take($options['limit']);
        }

        return $sent;
    }

    /**
     * Retrieve the most recent version of a Message
     *
     * @param $original_message_id
     *
     * @return Message
     */
    public static function get($original_message_id): Message
    {
        $message = Message::find($original_message_id);

        if ($versions = $message->versions()) {
            return $versions->last();
        }

        return $message;
    }
}
