<?php

declare(strict_types=1);

namespace GCLists\Api;

class BaseEndpoint
{
    protected string $namespace;

    public function __construct()
    {
        $this->namespace = "gc-lists";
    }
}
