<?php

declare(strict_types=1);

namespace GCLists;

use GCLists\Api\Messages;
use GCLists\ListManager\Proxy;

class GCLists
{
    protected static $instance;

    protected Messages $messagesApi;
    protected Install $installer;
    protected Menu $menu;
    protected Proxy $listManagerProxy;

    public static function getInstance(): GCLists
    {
        is_null(self::$instance) && self::$instance = new self();
        return self::$instance;
    }

    public function setup()
    {
        $this->installer        = Install::getInstance();
        $this->messagesApi      = Messages::getInstance();
        $this->menu             = Menu::getInstance();
        $this->listManagerProxy = Proxy::getInstance();

        $this->addHooks();
    }

    public function addHooks()
    {
        // Activation hooks
        register_activation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$this->installer, 'install']);
        register_deactivation_hook(GC_LISTS_PLUGIN_FILE_PATH, [$this->installer, 'uninstall']);

        // Load text domain
        add_action('init', [$this, 'loadTextdomain']);

        // Enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);

        // Register REST routes
        add_action('rest_api_init', [$this->messagesApi, 'registerRestRoutes']);
        add_action('rest_api_init', [$this->listManagerProxy, 'registerRestRoutes']);

        // Register admin menu
        add_action('admin_menu', [$this->menu, 'addMenu']);
        add_action('admin_menu', [$this->menu, 'addMessagesSubmenuItem']);
        add_action('admin_menu', [$this->menu, 'addSubscriberListsSubmenuItem']);
    }

    public function enqueue($hook_suffix)
    {
        if (str_contains($hook_suffix, 'gc-lists_')) {
            try {
                $path  = GC_LISTS_PLUGIN_BASE_PATH . '/resources/js/build/asset-manifest.json';
                $json  = file_get_contents($path);
                $data  = json_decode($json, true);
                $files = $data['files'];

                wp_enqueue_style('gc-lists-css', $files['main.css'], null, '1.0.0');

                wp_register_script(
                    'gc-lists-js',
                    $files['main.js'],
                    ['wp-element', 'wp-i18n'],
                    '1.0.0',
                    true,
                );

                wp_enqueue_script(
                    'gc-lists-js'
                );

                wp_set_script_translations('gc-lists-js', 'gc-lists', GC_LISTS_PLUGIN_BASE_PATH . '/resources/languages/');
            } catch (\Exception $exception) {
                error_log($exception->getMessage());
            }
        }
    }

    public function loadTextdomain()
    {
        return load_plugin_textdomain('gc-lists', false, 'gc-lists/resources/languages');
    }
}
