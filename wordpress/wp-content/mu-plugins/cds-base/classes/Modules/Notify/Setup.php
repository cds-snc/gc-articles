<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\EncryptedOption;
use Exception;

class Setup
{
    /**
     * @throws Exception
     */
    public function __construct(EncryptedOption $encryptedOption)
    {
        new NotifyTemplateSender(new FormHelpers(), new Notices());

        NotifySettings::register();

        NotifyApiSettings::register($encryptedOption);

        if ($this->isNotifyConfigured()) {
            include __DIR__ . '/includes/wp-mail-notify-api.php';
        }
    }

    /**
     * @return bool
     */
    protected function isNotifyConfigured(): bool
    {
        return (bool)getenv('NOTIFY_API_KEY');
    }
}
