<?php

namespace GCLists\Database\Models;

class Message extends Model
{
    protected $table = "messages";

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
