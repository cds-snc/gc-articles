<?php

declare(strict_types=1);

namespace GCLists\Api;

class BaseEndpoint
{
    protected $wpdb;
    protected string $namespace;
    protected string $tableName;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tableName = $wpdb->prefix . "messages";

        $this->namespace = "gc-lists";
    }
}
