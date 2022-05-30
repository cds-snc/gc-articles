<?php

declare(strict_types=1);

namespace GCLists\Api;

use WP_REST_Response;

class SendMessage
{
    public static function handle($listId, $subject, $body)
    {
        $url = getenv('LIST_MANAGER_ENDPOINT') . '/send';

        $args = [
            'method' => 'POST',
            'headers' => [
                'Authorization' => getenv('DEFAULT_LIST_MANAGER_API_KEY'),
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
        $proxy_response = wp_remote_retrieve_body(wp_remote_request($url, $args));

        $response = new WP_REST_Response(json_decode($proxy_response));

        return rest_ensure_response($response);
    }
}
