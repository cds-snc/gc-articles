<?php

use CDS\Modules\Notify\Utils;

test('mergeListManagerServicesString: Update all existing', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = 'LIST1~newapikey1,LIST2~newapikey2,LIST3~newapikey3';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($incoming, $new);
});

test('mergeListManagerServicesString: Update one existing', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = 'LIST1~,LIST2~newapikey2modified,LIST3~';

    $expected = 'LIST1~oldapikey1,LIST2~newapikey2modified,LIST3~oldapikey3';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});

test('mergeListManagerServicesString: Remove one', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = 'LIST1~oldapikey1,LIST3~oldapikey3';

    $expected = 'LIST1~oldapikey1,LIST3~oldapikey3';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});

test('mergeListManagerServicesString: Create (no existing)', function() {
    $existing = false;
    $incoming = 'LIST1~oldapikey1,LIST3~oldapikey3';

    $expected = 'LIST1~oldapikey1,LIST3~oldapikey3';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});

test('mergeListManagerServicesString: Update add new lists', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = 'LIST1~,LIST2~,LIST3~,LIST4~apikey4,LIST5~apikey5';

    $expected = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3,LIST4~apikey4,LIST5~apikey5';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});

test('mergeListManagerServicesString: Update with new entries and modify existing', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = 'LIST1~newapikey1,LIST2~,LIST3~,LIST4~apikey4,LIST5~apikey5';

    $expected = 'LIST1~newapikey1,LIST2~oldapikey2,LIST3~oldapikey3,LIST4~apikey4,LIST5~apikey5';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});

test('mergeListManagerServicesString: Pass empty new string', function() {
    $existing = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';
    $incoming = '';

    $expected = 'LIST1~oldapikey1,LIST2~oldapikey2,LIST3~oldapikey3';

    $new = Utils::mergeListManagerServicesString($incoming, $existing);

    $this->assertSame($new, $expected);
});