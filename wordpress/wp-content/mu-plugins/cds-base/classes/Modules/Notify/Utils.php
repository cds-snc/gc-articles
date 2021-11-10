<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use Exception;
use InvalidArgumentException;

class Utils
{
    public static function parseServiceIdsFromEnv($serviceIdData): array
    {
        if (!$serviceIdData) {
            throw new InvalidArgumentException('No service data');
        }

        try {
            $arr = explode(',', $serviceIdData);
            $service_ids = [];

            for ($i = 0; $i < count($arr); $i++) {
                $key_value = explode('~', $arr [$i]);

                $service_ids[$key_value[0]] = [
                    'service_id' => self::extractServiceIdFromApiKey($key_value[1]),
                    'api_key' => $key_value[1],
                    'name' => $key_value[0]
                ];
            }

            return $service_ids;
        } catch (Exception $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public static function extractServiceIdFromApiKey($apiKey): string
    {
        return substr($apiKey, -73, 36);
    }
}