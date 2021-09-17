<?php

declare(strict_types=1);

namespace CDS\Modules\Subscriptions;

class Setup
{
    public function __construct()
    {
        new Confirm();
        new Unsubscribe();
    }
}