<?php

declare(strict_types=1);

namespace CDS;

use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use WP_CLI;

class EncryptedOption
{

    private string $key;
    private string $cipher;
    public Encrypter $encrypter;

    /**
     * @param $key
     * @param  string  $cipher
     *
     * @throws \Exception
     */
    public function __construct($key, string $cipher = 'aes-256-cbc')
    {
        $this->key = $this->parseKey($key);
        $this->cipher = $cipher;
        $this->encrypter = new Encrypter($this->key, $this->cipher);
    }

    /**
     * Encrypt a string
     *
     * @param $string
     * @return string
     */
    public function encryptString($string): string
    {
        return $this->encrypter->encryptString($string);
    }

    /**
     * Decrypt a string
     *
     * @param $string
     * @return string
     */
    public function decryptString($string): string
    {
        return $this->encrypter->decryptString($string);
    }

    /**
     * Get and decrypt an Option
     *
     * @param $option
     * @param  string|null  $default
     * @return bool|string
     */
    public function getOption($option, string|null $default = null): bool|string
    {
        $encrypted =  get_option($option, $default);

        if ($encrypted === $default) {
            return $default;
        }

        return $this->decryptString($encrypted);
    }

    /**
     * Encrypt and Add or Update an Option
     *
     * @param $option
     * @param $value
     * @return bool
     */
    public function setOption($option, $value): bool
    {
        $encrypted = $this->encryptString($value);

        if (get_option($option)) {
            return update_option($option, $encrypted);
        }

        add_option($option, $encrypted);
        return true;
    }

    /**
     * Generate a base64 encoded encryption key
     */
    public function generateKey()
    {
        $key = Encrypter::generateKey($this->cipher);

        return 'base64:'.base64_encode($key);
    }

    /**
     * Parse the encryption key.
     *
     * @param $key
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
