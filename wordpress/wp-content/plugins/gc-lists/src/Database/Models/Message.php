<?php

namespace GCLists\Database\Models;

class Message extends Model
{
    protected $table = "messages";
    protected $guarded = [];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        global $wpdb;
        $this->table = $wpdb->prefix . "messages";
    }

    public function send()
    {
        // this will send a message
    }
}
