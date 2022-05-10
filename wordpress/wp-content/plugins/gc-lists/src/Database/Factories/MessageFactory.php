<?php

namespace GCLists\Database\Factories;

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
            'message_type' => 'email'
        );
    }

    public function create_object($args) // @codingStandardsIgnoreLine
    {
        $this->wpdb->insert($this->tableName, $args);
        $message_id = $this->wpdb->insert_id;
        return $message_id;
    }

    public function update_object($id, $fields) // @codingStandardsIgnoreLine
    {
        $fields['ID'] = $id;
        $this->wpdb->update($this->tableName, $fields);

        return $id;
    }

    public function get_object_by_id($message_id) // @codingStandardsIgnoreLine
    {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->tableName} WHERE id=%d", $message_id)
        );
    }
}
