<?php

declare(strict_types=1);

namespace CDS\Modules\Forms\Subscribe;

class Setup
{
    public static function register()
    {
        Endpoints::register();
        SubscriptionForm::register();
    }
}
