<?php

declare(strict_types=1);

namespace CDS;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class EncryptedOption
{

    private string $key;
    private string $cipher;
    private Encrypter $encrypter;

    public function __construct($key, $cipher = 'aes-256-cbc')
    {
        $this->key = $this->parseKey($key);
        $this->cipher = $cipher;
        $this->encrypter = new Encrypter($this->key, $this->cipher);
    }

    public function get($option, $default = false): bool|string
    {
        $encrypted =  get_option($option, $default);

        if ($encrypted === $default) {
            return $default;
        }

        return $this->encrypter->decryptString($encrypted);
    }

    public function set($option, $value): bool
    {
        $encrypted = $this->encrypter->encryptString($value);

        if (get_option($option)) {
            return update_option($option, $encrypted);
        }

        add_option($option, $encrypted);
        return true;
    }

    /**
     * Parse the encryption key.
     *
     * @return string
     */
    protected function parseKey($key): string
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}
