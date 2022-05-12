<?php

namespace GCLists\Database\Models;

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

    public function sent()
    {
        // get all sent versions of this model
    }

    public function versions()
    {
        return static::whereEquals(['original_message_id' => $this->id]);
    }

    public function createNewVersion()
    {
        // create a new version of current model
    }

    /**
     * Static methods
     */

    public static function templates(array $options = ['limit' => 5])
    {
        // get all "templates" (no original_message_id)
    }

    public static function messages(array $options = ['limit' => 5])
    {
        // static method to get all sent Messages
    }

    public static function get($original_message_id)
    {
        $message = Message::find($original_message_id);

        if ($versions = $message->versions()) {
            return end($versions);
        }

        return $message;
    }
}
