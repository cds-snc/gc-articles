<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

class Endpoints extends BaseEndpoint
{
    protected static $instance;

    public static function getInstance(): Endpoints
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }
}
