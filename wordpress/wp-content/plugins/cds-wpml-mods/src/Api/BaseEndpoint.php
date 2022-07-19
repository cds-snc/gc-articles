<?php

declare(strict_types=1);

namespace CDS\Wpml\Api;

class BaseEndpoint
{
    protected string $namespace;

    public function __construct()
    {
        $this->namespace = "cds/wpml";
    }
}
