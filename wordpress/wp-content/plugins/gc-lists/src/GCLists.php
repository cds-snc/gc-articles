<?php

declare(strict_types=1);

namespace GCLists;

class GCLists
{
    protected static $instance;

    public static function register(): GCLists
    {
        is_null(self::$instance) and self::$instance = new self();

        self::$instance->registerHooks();

        return self::$instance;
    }

    public function registerHooks()
    {
        $installer = new Install();

        register_activation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$installer, 'install']);
        register_deactivation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$installer, 'uninstall']);
    }
}
