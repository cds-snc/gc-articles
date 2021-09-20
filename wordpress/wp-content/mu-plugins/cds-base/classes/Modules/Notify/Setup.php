<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

class Setup
{
    public function __construct()
    {
        new NotifyTemplateSender(new FormHelpers(), new Notices());

        new NotifySettings();
    }
}