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
    protected Permissions $permissions;

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
        $this->permissions      = Permissions::getInstance();

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

        // Register User profile permissions
        add_action('edit_user_profile', [$this->permissions, 'displayListManagerMeta']);
        add_action('edit_user_profile_update', [$this->permissions,'updateListManagerMeta']);

        add_filter(
            'option_page_capability_list_manager_settings_option_group',
            function ($capability) {
                return 'manage_list_manager';
            },
        );

        if (!get_option('gc-lists_roles_cleanup')) {
            add_action('plugins_loaded', [$this->permissions, 'cleanupCustomCapsForRoles'], 11);
        }

        add_action('set_user_role', [$this->permissions, 'addDefaultUserCapsForRole'], 10, 3);
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
