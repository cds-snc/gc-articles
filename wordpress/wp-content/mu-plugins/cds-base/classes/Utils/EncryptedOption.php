<?php

declare(strict_types=1);

namespace CDS\Utils;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

use function add_option;
use function update_option;

class EncryptedOption
{

    private string|array|false $key;
    private string $cipher;
    private Encrypter $encrypter;

    public function __construct()
    {
        // @TODO: should the key be loaded through the contructor at init?
        $this->key = $this->getEncryptionKey();
        $this->cipher = 'aes-256-cbc';
        $this->encrypter = new Encrypter($this->key, $this->cipher);
    }

    public function get($option, $default = false)
    {
        $encrypted =  get_option($option, $default);

        if ($encrypted === $default) {
            return $default;
        }

        return $this->encrypter->decryptString($encrypted);
    }

    public function set($option, $value)
    {
        $encrypted = $this->encrypter->encryptString($value);

        if (get_option($option)) {
            return update_option($option, $encrypted);
        }

        add_option($option, $encrypted);
        return true;
    }

    public function getEncryptionKey()
    {
        // @TODO: handle if missing
        return $this->parseKey(getenv('ENCRYPTION_KEY'));
    }

    /**
     * Parse the encryption key.
     *
     * @return string
     */
    protected function parseKey($key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}
