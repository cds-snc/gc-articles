<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use Exception;
use InvalidArgumentException;

class Utils
{
    public static function deserializeServiceIds($serviceIdData): array
    {
        if (!$serviceIdData) {
            return [];
        }

        try {
            $arr = explode(',', $serviceIdData);
            $service_ids = [];

            for ($i = 0; $i < count($arr); $i++) {
                $key_value = explode('~', $arr [$i]);

                if (array_key_exists(1, $key_value)) {
                    $service_ids[$key_value[0]] = [
                        'service_id' => self::extractServiceIdFromApiKey($key_value[1]),
                        'api_key'    => $key_value[1],
                        'name'       => $key_value[0]
                    ];
                } else {
                    $service_ids[$key_value[0]] = null;
                }
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
