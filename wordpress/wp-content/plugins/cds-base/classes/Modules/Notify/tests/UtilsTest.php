<?php

use CDS\Modules\Notify\Utils;

test('parseServiceIds', function () {
    $serviceIdEnv = "serviceID1~mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a9-4b47-ade1-64a9094d00f7,serviceID2~mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a8-4b47-ade1-64a9094d00f7,serviceID3~mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a9-4b46-ade1-64a9094d00f7";
    $serviceIds = Utils::deserializeServiceIds($serviceIdEnv);
    expect($serviceIds)->toEqual(["serviceID1" => [
        'service_id' => 'e609f6b0-0390-45b0-aaae-f4cc92c713e4',
        'api_key' => 'mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a9-4b47-ade1-64a9094d00f7',
        'name' => 'serviceID1'
    ], "serviceID2" => [
        'service_id' => 'e609f6b0-0390-45b0-aaae-f4cc92c713e4',
        'api_key' => 'mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a8-4b47-ade1-64a9094d00f7',
        'name' => 'serviceID2'
    ], "serviceID3" => [
        'service_id' => 'e609f6b0-0390-45b0-aaae-f4cc92c713e4',
        'api_key' => 'mvp-e609f6b0-0390-45b0-aaae-f4cc92c713e4-1deede83-37a9-4b46-ade1-64a9094d00f7',
        'name' => 'serviceID3'
    ]]);
});

test('parseServiceIdsBadInput', function () {
    $serviceIdEnv = "serviceID1";
    $result = Utils::deserializeServiceIds($serviceIdEnv);
    expect($result)->toEqual(["serviceID1" => NULL]);
});

test('parseServiceIdsNoInput', function () {
    $result = Utils::deserializeServiceIds(null);
    expect($result)->toEqual([]);
});