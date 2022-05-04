<?php

declare(strict_types=1);

namespace GCLists\Api;

class Messages
{
    protected static Messages $instance;

    public static function get_instance(): Messages
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }
}
