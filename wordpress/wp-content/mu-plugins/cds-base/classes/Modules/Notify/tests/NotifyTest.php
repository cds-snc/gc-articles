<?php

namespace CDS\Tests\Unit;

require_once(__DIR__ . '/../../../../vendor/autoload.php');

use CDS\Modules\Notify\FormHelpers;
use CDS\Modules\Notify\Notices;
use CDS\Modules\Notify\NotifyTemplateSender;
use InvalidArgumentException;
use WP_Mock\Tools\TestCase;

class NotifyTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function tearDown(): void
    {
    }


    public function testParseServiceIds()
    {
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $serviceIdEnv = "serviceID1~apikey1,serviceID2~apikey2,serviceID3~apikey3";
        $serviceIds = $sender->parseServiceIdsFromEnv($serviceIdEnv);
        $this->assertEquals(
            ["serviceID1" => "apikey1", "serviceID2" => "apikey2", "serviceID3" => "apikey3"],
            $serviceIds
        );
    }

    public function testParseServiceIdsBadInput()
    {
        $this->expectException(InvalidArgumentException::class);
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $serviceIdEnv = "serviceID1";
        $sender->parseServiceIdsFromEnv($serviceIdEnv);
    }

    public function testParseServiceIdsNoInput()
    {
        $this->expectException(InvalidArgumentException::class);
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $sender->parseServiceIdsFromEnv(null);
    }

    public function testParseJsonOptions()
    {
        try {
            $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
            $options = $sender->parseJsonOptions('[{"id":"123", "type":"email", "label":"my-list"}]');
            $this->assertEquals([["id" => 123, "type" => "email", "label" => "my-list"]], $options);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function testParseJsonOptionsInvalidJson()
    {
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $options = $sender->parseJsonOptions("{");
        $this->assertEquals(null, $options);
    }

    public function testParseJsonOptionsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $sender = new NotifyTemplateSender(new FormHelpers(), new Notices());
        $options = $sender->parseJsonOptions("");
    }
}
