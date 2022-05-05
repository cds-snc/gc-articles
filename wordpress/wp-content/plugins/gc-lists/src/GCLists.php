<?php

declare(strict_types=1);

namespace GCLists;

use GCLists\Api\Messages;

class GCLists
{
    protected static $instance;
    protected $installer;

    public static function getInstance(): GCLists
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function setup()
    {
        $this->registerHooks();
        $this->registerRestRoutes();
    }

    public function registerHooks()
    {
        $installer = Install::getInstance();

        register_activation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$installer, 'install']);
        register_deactivation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$installer, 'uninstall']);
    }

    public function registerRestRoutes()
    {
        $messages = Messages::getInstance();
        add_action('rest_api_init', [$messages, 'registerRestRoutes']);
    }
}
