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
    public function __construct()
    {
        new NotifyTemplateSender(new FormHelpers(), new Notices());

        NotifySettings::register($this->setupEncryptedOption());

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

    /**
     * @return EncryptedOption
     * @throws Exception
     */
    protected function setupEncryptedOption(): EncryptedOption
    {
        /**
         * Setup Encrypted Options
         */
        $encryptionKey = getenv('ENCRYPTION_KEY');

        if (!$encryptionKey || $encryptionKey == '') {
            throw new Exception('No encryption key set in the environment');
        }

        return new EncryptedOption($encryptionKey);
    }
}
