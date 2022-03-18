<?php

declare(strict_types=1);

namespace CDS\Modules\Cli;

use CDS\Modules\EncryptedOption\EncryptedOption;
use WP_CLI;

class EncryptedOptionCLI
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
            WP_CLI::add_command('set_encrypted_option', [$instance, 'setEncryptedOption']);
        });
    }

    public function setEncryptedOption($args)
    {
        $this->encryptedOption->setOption($args[0], $args[1]);
        WP_CLI::success('Option set: ' . $args[0] . ' => ' . $args[1]);
    }
}
