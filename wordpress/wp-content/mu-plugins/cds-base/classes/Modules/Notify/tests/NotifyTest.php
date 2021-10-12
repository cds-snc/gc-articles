<?php

namespace CDS\Tests\Unit;

use CDS\Modules\Notify\FormHelpers;
use CDS\Modules\Notify\Notices;
use CDS\Modules\Notify\NotifyTemplateSender;
use InvalidArgumentException;

test('parseServiceIds', function () {
    $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
    $serviceIdEnv = "serviceID1~apikey1,serviceID2~apikey2,serviceID3~apikey3";
    $serviceIds = $sender->parseServiceIdsFromEnv($serviceIdEnv);
    expect($serviceIds)->toEqual(["serviceID1" => "apikey1", "serviceID2" => "apikey2", "serviceID3" => "apikey3"]);
});

test('parseServiceIdsBadInput', function () {
    $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
    $serviceIdEnv = "serviceID1";
    $sender->parseServiceIdsFromEnv($serviceIdEnv);
})->throws(InvalidArgumentException::class);

test('parseServiceIdsNoInput', function () {
    $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
    $sender->parseServiceIdsFromEnv(null);
})->throws(InvalidArgumentException::class);

test('parseJsonOptions', function () {
    try {
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $options = $sender->parseJsonOptions('[{"id":"123", "type":"email", "label":"my-list"}]');
        expect($options)->toEqual([["id" => 123, "type" => "email", "label" => "my-list"]]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});

test('parseJsonOptionsInvalidJson', function () {
    $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
    $options = $sender->parseJsonOptions("{");
    expect($options)->toEqual(null);
});

test('parseJsonOptionsEmpty', function () {
    $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
    $options = $sender->parseJsonOptions("");
})->throws(InvalidArgumentException::class);
