<?php

namespace CDS\Wpml;

use CDS\Wpml\Api\Endpoints;

class Wpml
{
    protected static $instance;

    protected Endpoints $endpoints;

    public static function getInstance(): Wpml
    {
        is_null(self::$instance) and self::$instance = new self();

        return self::$instance;
    }

    public function setup()
    {
        $this->endpoints = Endpoints::getInstance();

        $this->addHooks();
    }

    public function addHooks()
    {
        //
    }
}
