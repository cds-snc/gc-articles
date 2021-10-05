<?php

namespace CDS\Modules\Notify;

use Alphagov\Notifications\Exception\NotifyException;
use Http\Adapter\Guzzle6\Client;
use Http\Client\Exception;

class NotifyClient
{

    public $notifyClient;

    public function __construct()
    {
        $this->notifyClient = $this->setupClient();
    }

    private function setupClient(): ?\Alphagov\Notifications\Client
    {
        try {
            $NOTIFY_API_KEY = getenv('NOTIFY_API_KEY');

            if (!$NOTIFY_API_KEY) {
                return null;
            }

            return new \Alphagov\Notifications\Client([
                'baseUrl' => "https://api.notification.canada.ca",
                'apiKey' => $NOTIFY_API_KEY,
                'httpClient' => new Client()
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function sendMail($emailTo, $templateId, $data = [], $ref = "")
    {
        if (!$this->notifyClient) {
            error_log("notifyClient doesn't exist");
            return false;
        }

        $this->notifyClient->sendEmail(
            $emailTo,
            $templateId,
            $data,
            $ref
        );
    }
}
