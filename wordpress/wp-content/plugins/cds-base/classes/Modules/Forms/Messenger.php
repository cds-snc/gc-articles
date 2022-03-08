<?php

declare(strict_types=1);

namespace CDS\Modules\Forms;

use CDS\Modules\Notify\NotifyClient;

class Messenger
{
    public function __construct()
    {
    }

    public function getNotifyClient()
    {
        return new NotifyClient();
    }

    public function sendMail(string $email, string $message): array
    {
        try {
            $notifyTemplateId = '125002c5-cf95-4eec-a6c8-f97eda56550a';
            $response = $this->getNotifyClient()->sendMail($email, $notifyTemplateId, [
                'email' => $email,
                'message' => $message,
            ]);

            return ['success' => __('Thanks for the message', 'cds-snc')];
        } catch (\Exception $exception) {
            error_log($exception->getMessage());
            return ['error' => $exception->getMessage(), "error_message" => __('Error sending email', 'cds-snc')];
        }
    }
}
