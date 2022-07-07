<?php

declare(strict_types=1);

namespace GCLists\Api;

use WP_REST_Response;

class SendMessage
{
    public static function handle($listId, $subject, $body, $messageType)
    {
        $url = getenv('LIST_MANAGER_ENDPOINT') . '/send';
        [$notify_api_key, $notify_template_id] = self::getNotifyIdsFromType($messageType);

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
                    'subject' => $subject, // Unneeded for "phone" type, but it's okay to send extra parameters
                    'message' => $body,
                ]),
                'template_type' => $messageType,
                'service_api_key' => $notify_api_key,
                'template_id' => $notify_template_id,
            ]),
        ];

        // Proxy request to list-manager
        $proxy_response = wp_remote_request($url, $args);

        $response_body = wp_remote_retrieve_body($proxy_response);

        $response = new WP_REST_Response(json_decode($response_body));

        return rest_ensure_response($response);
    }

    public static function getNotifyIdsFromType($messageType)
    {
        $notify_api_key = get_option('NOTIFY_API_KEY');
        $notify_template_id = get_option('NOTIFY_GENERIC_TEMPLATE_ID');

        if ($messageType === 'phone') {
            // if an SMS message, use the generic GC Articles Notify service with our generic phone template
            $notify_api_key = getenv('DEFAULT_NOTIFY_API_KEY');
            $notify_template_id = getenv('DEFAULT_NOTIFY_PHONE_TEMPLATE');
        }

        return [$notify_api_key, $notify_template_id];
    }
}
