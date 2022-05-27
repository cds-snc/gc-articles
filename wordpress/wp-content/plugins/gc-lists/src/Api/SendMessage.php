<?php

declare(strict_types=1);

namespace GCLists\Api;

class SendMessage
{
    public function __invoke($listId, $subject, $body)
    {
        $url = LIST_MANAGER_ENDPOINT . '/send';

        $args = [
            'method' => 'POST',
            'headers' => [
                'Authorization' => DEFAULT_LIST_MANAGER_API_KEY,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'job_name' => "gc-lists",
                'list_id' => $listId,
                'personalisation' => json_encode([
                    'subject' => $subject,
                    'message' => $body,
                ]),
                'template_id' => get_option('NOTIFY_GENERIC_TEMPLATE_ID'),
                'template_type' => "email",
            ]),
        ];

        // Proxy request to list-manager
        $response = wp_remote_request($url, $args);

        return json_decode(wp_remote_retrieve_body($response));
    }
}
