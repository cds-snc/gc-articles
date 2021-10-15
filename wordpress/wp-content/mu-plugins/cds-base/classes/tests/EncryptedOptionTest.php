<?php

use CDS\EncryptedOption;

test('Encrypt/Decrypt string', function() {
    $e = new EncryptedOption(str_repeat('a', 32));
    $encrypted = $e->encryptString('foo');
    $this->assertNotSame('foo', $encrypted);
    $this->assertSame('foo', $e->decryptString($encrypted));
});
