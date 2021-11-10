<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use Exception;
use InvalidArgumentException;

class Utils
{
    public static function parseServicesStringToArray($serviceIdData): array
    {
        if (!$serviceIdData) {
            throw new InvalidArgumentException('No service data');
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

    public static function convertServicesArrayToString($services)
    {
        $str = "";

        foreach ($services as $key => $value) {
            if (is_array($value)) {
                if ($value['name'] !== "" && $value['service_id'] !== "" && $value['api_key'] !== "") {
                    $str .= $value['name'] . '~' . $value['api_key'] . ",";
                }
            }
        }

        return trim($str, ',');
    }

    public static function mergeListManagerServicesString(string $incoming, string $existing): string
    {
        // If existing is empty no reason to merge just return incoming
        if (!$existing) {
            return $incoming;
        }

        // Likewise if incoming is empty no reason to merge just return existing
        if (!$incoming) {
            return $existing;
        }

        $incomingArray = self::parseServicesStringToArray($incoming);
        $existingArray = self::parseServicesStringToArray($existing);

        if (count($incomingArray)) {
            foreach ($existingArray as $key => $details) {
                if (array_key_exists($key, $incomingArray)) {
                    if ($incomingArray[$key] == null || $incomingArray[$key]['service_id'] == '') {
                        // no change
                        $incomingArray[$key] = $details;
                    } else {
                        // update
                        $existingArray[$key] = $incomingArray[$key];
                    }
                } else {
                    // delete
                    unset($existingArray[$key]);
                }
            }

            $merged = array_merge($incomingArray, $existingArray);
            return self::convertServicesArrayToString($merged);
        }

        return $existing;
    }
}
