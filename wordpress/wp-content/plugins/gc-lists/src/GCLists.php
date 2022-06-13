<?php

declare(strict_types=1);

namespace GCLists;

use GCLists\Api\Messages;

class GCLists
{
    protected static $instance;

    public static function getInstance(): GCLists
    {
        is_null(self::$instance) && self::$instance = new self();
        return self::$instance;
    }

    public function setup()
    {
        $this->registerHooks();
        $this->registerRestRoutes();
        $this->registerMenu();

        add_action('init', [$this, 'loadTextdomain']);
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
        $messages->register();
    }

    public function registerMenu()
    {
        $menu = Menu::getInstance();
        $menu->register();
    }

    public function loadTextdomain()
    {
        return load_plugin_textdomain('gc-lists', false, 'gc-lists/resources/languages');
    }
}
