<?php

declare(strict_types=1);

namespace CDS\Modules\Forms;

use CDS\Modules\Notify\NotifyClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class Messenger
{
    public function __construct()
    {
    }

    public function getNotifyClient()
    {
        return new NotifyClient();
    }

    public function getGuzzleClient()
    {
        return new Client([]);
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

    protected function isJson($string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function detectTags($str)
    {
        if (str_contains($str, 'demo')) {
            return ['demo_request'];
        }

        if (str_contains($str, 'démo')) {
            return ['demo_request'];
        }

        return [];
    }

    public function mergeTags($goal)
    {
        return array_merge(['articles_api'], $this->detectTags($goal));
    }
}
