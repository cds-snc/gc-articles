<?php

use CDS\Modules\EncryptedOption\EncryptedOption;

test('Encrypt/Decrypt string', function() {
    $encryptedOption = new EncryptedOption(str_repeat('a', 32));
    $encrypted = $encryptedOption->encryptString('foo');
    $this->assertNotSame('foo', $encrypted);
    $this->assertSame('foo', $encryptedOption->decryptString($encrypted));
});

test('GenerateKey', function() {
    $encryptedOption = new EncryptedOption(str_repeat('a', 32));

    // key should be prefixed by 'base64:'
    $key = $encryptedOption->generateKey();
    $this->assertStringContainsString('base64:', $key);

    // key part should be 44 chars
    $keyparts = explode(':', $key);
    $this->assertTrue(strlen($keyparts[1]) === 44);
});

test('ParseKey', function() {
    $encryptedOption = new EncryptedOption(str_repeat('a', 32));
    $key = $encryptedOption->generateKey();

    $keyparts = explode(':', $key);
    $decoded = base64_decode($keyparts[1]);

    $parsedKey = $encryptedOption->parseKey($key);

    $this->assertFalse(\Illuminate\Support\Str::startsWith($parsedKey, 'base64:'));
    $this->assertEquals($parsedKey, $decoded);
});