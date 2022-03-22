<?php

declare(strict_types=1);

namespace CDS\Modules\Cli;

use CDS\Modules\Wpml\Installer as WPMLInstaller;
use WP_CLI;

class InstallWpmlCLI
{
    protected WPMLInstaller $wpml;

    public function __construct(WPMLInstaller $wpml)
    {
        $this->wpml = $wpml;
    }

    public static function register(WPMLInstaller $wpml)
    {
        $instance = new self($wpml);

        add_action('cli_init', function () use ($instance) {
            WP_CLI::add_command('install-wpml', [$instance, 'installWpml']);
        });
    }

    public function installWpml()
    {
        $this->wpml->installWpml();
    }
}
