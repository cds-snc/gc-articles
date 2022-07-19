<?php

namespace CDS\Wpml;

class Wpml
{
    protected static $instance;

    public static function getInstance(): Wpml
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }
}
