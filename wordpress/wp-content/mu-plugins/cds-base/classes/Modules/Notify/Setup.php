<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

class Setup
{
    public function __construct()
    {
        new NotifyTemplateSender(new FormHelpers(), new Notices());

        new NotifySettings();

        if ($this->isNotifyConfigured()) {
            include __DIR__ . '/includes/wp-mail-notify-api.php';
        }
    }

    protected function isNotifyConfigured(): bool
    {
        return (bool)getenv('NOTIFY_API_KEY');
    }
}
