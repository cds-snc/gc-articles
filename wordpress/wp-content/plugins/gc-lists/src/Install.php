<?php

declare(strict_types=1);

namespace GCLists;

class Install
{
    protected static $instance;
    protected string $tableName;
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $wpdb->prefix . "messages";
    }

    public static function getInstance(): Install
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function install()
    {
        $charsetCollate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (
        id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    	name text,
    	subject text,
    	body text,
    	message_type varchar(20) NOT NULL DEFAULT 'email',
    	sent_at TIMESTAMP NULL,
    	sent_to_list_id varchar(50) NULL,
    	sent_to_list_name varchar(255) NULL,
    	sent_by_id bigint(20) NULL,
    	sent_by_email varchar(100) NULL,
    	original_message_id bigint(20) NULL,
        version_id bigint(20) NULL,
    	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    	) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function uninstall()
    {
        $this->wpdb->query("DROP TABLE IF EXISTS $this->tableName");
    }
}
