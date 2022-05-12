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

    public function sentMessages()
    {
        // get all sent versions of this model
    }

    public function previousVersions()
    {
        // versions of current model
    }

    public function createNewVersion()
    {
        // create a new version of current model
    }

    public static function sent()
    {
        // static method to get all sent Messages
    }
}
