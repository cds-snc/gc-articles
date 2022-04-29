<?php

declare(strict_types=1);

namespace GCLists;

class GCLists
{
    protected static $instance;

    public static function register()
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public static function install()
    {
        // db install stuff
    }
}
