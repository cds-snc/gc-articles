<?php

namespace NotifyClient\CDS;


use Http\Adapter\Guzzle6\Client;

class NotifyClient
{

    public $notifyClient;

    public function __construct()
    {
        $this->notifyClient = $this->setupClient();
    }

    private function setupClient(): \Alphagov\Notifications\Client
    {
        $NOTIFY_API_KEY = $_ENV['NOTIFY_API_KEY'];
        return new \Alphagov\Notifications\Client([
            'baseUrl' => "https://api.notification.canada.ca",
            'apiKey' => $NOTIFY_API_KEY,
            'httpClient' => new Client
        ]);
    }

    public function sendMail($emailTo, $templateId, $data = [], $ref = "")
    {
        try {
            $response = $this->notifyClient->sendEmail(
                $emailTo,
                $templateId,
                $data,
                $ref
            );
        } catch (NotifyException $e) {
            echo $e->getMessage();
        }
    }
}