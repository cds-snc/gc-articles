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

    /**
     * Extract ServiceID from API key
     *
     * @param $apiKey
     * @return string
     */
    public function extractServiceIdFromApiKey($apiKey): string
    {
        return substr($apiKey, -73, 36);
    }

    /**
     * Get ServiceId
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));
    }

    /**
     * Get services array
     *
     * @return array
     */
    public function getServices(): array
    {
        return [
            'name' => __('Your Lists', 'cds-snc'),
            'service_id' => $this->getServiceId()
        ];
    }

    /**
     * Build up a user permissions object for the current user
     *
     * @return \stdClass
     */
    public function getUserPermissions(): \stdClass
    {
        $user = new \stdClass();
        $user->hasEmail = current_user_can('list_manager_bulk_send');
        $user->hasPhone = current_user_can('list_manager_bulk_send_sms');

        return $user;
    }

    /**
     * Render messages template
     */
    public function renderMessages(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            $this->renderNoApiKey();
            exit;
        }

        $this->render('messages', [
            'title' => __('Messages', 'cds-snc'),
            'services' => $this->getServices(),
            'user' => $this->getUserPermissions(),
        ]);
    }

    /**
     * Render subscribers template
     */
    public function renderSubscribers(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            $this->renderNoApiKey();
            exit;
        }

        $this->render('subscribers', [
            'title' => __('Subscribers', 'cds-snc'),
            'services' => $this->getServices(),
            'user' => $this->getUserPermissions(),
        ]);
    }

    /**
     * Render NoApiKey error
     */
    public function renderNoApiKey()
    {
        $this->render('no_api_key', [
            'title' => esc_html(get_admin_page_title())
        ]);
    }

    public function enqueue($hook_suffix)
    {
        if (str_contains($hook_suffix, 'gc-lists_')) {
            try {
                $path  = plugin_dir_path(__FILE__) . '/../resources/js/build/asset-manifest.json';
                $json  = file_get_contents($path);
                $data  = json_decode($json, true);
                $files = $data['files'];

                wp_enqueue_style('gc-lists', $files['main.css'], null, '1.0.0');

                wp_enqueue_script(
                    'gc-lists',
                    $files['main.js'],
                    null,
                    '1.0.0',
                    true,
                );
            } catch (\Exception $exception) {
                error_log($exception->getMessage());
            }
        }
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
