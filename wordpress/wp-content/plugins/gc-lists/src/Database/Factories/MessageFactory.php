<?php

namespace GCLists\Database\Factories;

use Carbon\Carbon;

class MessageFactory extends \WP_UnitTest_Factory_For_Thing
{
    protected $tableName;
    protected $wpdb;

    public function __construct($factory = null)
    {
        parent::__construct($factory);

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName =  $wpdb->prefix . "messages";

        $this->default_generation_definitions = array(
            'name' => 'This is a message',
            'subject' => 'Subject of the message',
            'body' => 'This is the body of the message it can be very long',
            'message_type' => 'email',
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
        );
    }

    public function create_object($args) // @codingStandardsIgnoreLine
    {
        $this->wpdb->insert($this->tableName, $args);
        $message_id = $this->wpdb->insert_id;
        return $message_id;
    }

    public function update_object($object, $fields) // @codingStandardsIgnoreLine
    {
        $fields['ID'] = $object->id;
        $this->wpdb->update($this->tableName, $fields, ['ID' => $object->id]);

        return $object->id;
    }

    public function get_object_by_id($message_id) // @codingStandardsIgnoreLine
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id=%d", $message_id)
        );
    }
}
