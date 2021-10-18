<?php

use CDS\EncryptedOption;

test('Encrypt/Decrypt string', function() {
    $encryptedOption = new EncryptedOption(str_repeat('a', 32));
    $encrypted = $encryptedOption->encryptString('foo');
    $this->assertNotSame('foo', $encrypted);
    $this->assertSame('foo', $encryptedOption->decryptString($encrypted));
});
