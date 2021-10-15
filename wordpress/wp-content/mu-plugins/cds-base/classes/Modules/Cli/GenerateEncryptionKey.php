<?php

declare(strict_types=1);

namespace CDS\Modules\Cli;

use CDS\EncryptedOption;
use Composer\Script\Event;
use Exception;
use WP_CLI;

class GenerateEncryptionKey
{
    private EncryptedOption $encryptedOption;

    public function __construct(EncryptedOption $encryptedOption)
    {
        $this->encryptedOption = $encryptedOption;
    }

    public static function register(EncryptedOption $encryptedOption)
    {
        $instance = new self($encryptedOption);

        add_action('cli_init', function () use ($instance) {
            WP_CLI::add_command('generate-encryption-key', [$instance, 'generateKey']);
        });
    }

    /**
     * This is a simplified version that can be called from a Composer script directly
     *
     * @param  Event  $event
     * @throws Exception
     */
    public static function composerGenerateEncryptionKey(Event $event)
    {
        $event->getIO()->write('Here is an encryption key, you should add it to your .env file as ENCRYPTION_KEY');
        $event->getIO()->write('base64:' . base64_encode(random_bytes(32)));
    }

    public function generateKey()
    {
        $key = $this->encryptedOption->generateKey();

        WP_CLI::success('Here is an encryption key, you should add it to your .env file as ENCRYPTION_KEY');
        WP_CLI::line($key);
    }
}
