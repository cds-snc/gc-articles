<?php

declare(strict_types=1);

namespace GCLists;

class Utils
{
    /**
     * Extract ServiceID from API key
     *
     * @param $apiKey
     * @return string
     */
    public static function extractServiceIdFromApiKey($apiKey): string
    {
        return substr($apiKey, -73, 36);
    }

    /**
     * Get ServiceId
     *
     * @return string
     */
    public static function getServiceId(): string
    {
        return static::extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));
    }

    /**
     * Get services array
     *
     * @return array
     */
    public static function getServices(): array
    {
        return [
            'name' => __('Your Lists', 'gc-lists'),
            'service_id' => static::getServiceId()
        ];
    }

    /**
     * Build up a user permissions object for the current user
     *
     * @return \stdClass
     */
    public static function getUserPermissions(): \stdClass
    {
        $user = new \stdClass();
        $user->hasEmail = current_user_can('list_manager_bulk_send');
        $user->hasPhone = current_user_can('list_manager_bulk_send_sms');

        return $user;
    }
}
