<?php

declare(strict_types=1);

namespace GCLists;

class Menu
{
    protected static $instance;
    protected string $messagesPageSlug = 'gc-lists_messages';
    protected string $subscribersPageSlug = 'gc-lists_subscribers';
    protected string $capability = 'list_manager_bulk_send';

    public static function getInstance(): Menu
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function register()
    {
        add_action('admin_menu', [$this, 'addMenu']);
        add_action('admin_menu', [$this, 'addMessagesSubmenuItem']);
        add_action('admin_menu', [$this, 'addSubscriberListsSubmenuItem']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function addMenu()
    {
        add_menu_page(
            __('GC Lists', "cds-snc"),
            __('GC Lists', "cds-snc"),
            $this->capability,
            $this->messagesPageSlug,
            [$this, 'renderMessages'],
            'dashicons-email'
        );
    }

    public function addMessagesSubmenuItem()
    {
        add_submenu_page(
            $this->messagesPageSlug,
            __('Messages', 'cds-snc'),
            __('Messages', 'cds-snc'),
            $this->capability,
            $this->messagesPageSlug,
            [$this, 'renderMessages'],
        );
    }

    public function addSubscriberListsSubmenuItem()
    {
        add_submenu_page(
            $this->messagesPageSlug,
            __('Subscriber lists', 'cds-snc'),
            __('Subscriber lists', 'cds-snc'),
            $this->capability,
            $this->subscribersPageSlug,
            [$this, 'renderSubscribers'],
        );
    }

    public function renderMessages(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            $this->renderNoApiKey();
            exit;
        }

        $messages = "Hello";

        $this->render('messages', [
            'title' => 'Messages'
        ]);
    }

    public function renderSubscribers(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            $this->renderNoApiKey();
            exit;
        }

        $this->render('subscribers', [
            'title' => 'Subscribers'
        ]);
    }

    public function renderNoApiKey()
    {
        $this->render('no_api_key');
    }

    public function enqueue($hook_suffix)
    {
        // if ($hook_suffix == $this->subscribersPageSlug) {
        try {
            $path  = plugin_dir_path(__FILE__) . '/../resources/js/build/asset-manifest.json';
            $json  = file_get_contents($path);
            $data  = json_decode($json, true);
            $files = $data['files'];

            // wp_enqueue_style('list-manager', $files['main.css'], null, '1.0.0');

            wp_enqueue_script(
                'gc-lists',
                $files['main.js'],
                null,
                '1.0.0',
                true,
            );
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        // }
    }

    /**
     * Render a php template. Accepts the template name without extension.
     * Template file must be in the resources folder, ie:
     * /resources/templates/[template].php
     *
     * $args is an associative array of variables available to the template.
     *
     * @param  string  $template
     * @param  array  $args
     */
    public function render(string $template, array $args = [])
    {
        extract($args);
        require_once(__DIR__ . "/../resources/templates/{$template}.php");
    }
}
