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

    protected function handleException($e)
    {
        $exception = (string) $e->getResponse()->getBody();

        error_log("ZENDESK - ClientException" . $exception);

        if ($this->isJson($exception)) {
            try {
                return json_decode($exception);
            } catch (\Exception $e) {
                return __('ZenDesk client error', 'cds-snc');
            }
        }
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

    public function createTicket(
        string $goal,
        string $fullname,
        string $email,
        string $message
    ): array {
        try {
            $client = $this->getGuzzleClient();
            $response = $client->request('POST', getenv('FRESH_DESK_API_URL') . '/api/v2/tickets', [
                'auth' => [
                    getenv('FRESH_DESK_API_KEY'),
                    'X'
                ],
                'json' =>  [
                    'product_id' => (int)getenv('FRESH_DESK_PRODUCT_ID'),
                    'subject' => $goal,
                    'description' => $message,
                    'name' => $fullname,
                    'email' => $email,
                    'priority' => 1,
                    'status' => 2,
                    'tags' => $this->mergeTags($goal),
                    'type' => 'Question'
                ],
            ]);

            return ['success' => __('Success', 'cds-snc')];
        } catch (ClientException $exception) {
            return ['error' => ["exceptions" => $this->handleException($exception)], "error_message" => __('Internal server error', 'cds-snc')];
        } catch (\Exception $e) {
            error_log("ZENDESK - Exception" . $e->getMessage());
            return ['error' => true, "error_message" => __('ZenDesk server error', 'cds-snc')];
        }
    }
}
