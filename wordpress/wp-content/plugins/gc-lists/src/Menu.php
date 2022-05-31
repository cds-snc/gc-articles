<?php

declare(strict_types=1);

namespace GCLists;

use CDS\Modules\Notify\FormHelpers;
use CDS\Modules\Notify\Notices;

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
            $this->noApiKey();
            exit;
        }

        $messages = "Hello";

        $this->render('messages', [
            'messages' => $messages
        ]);
    }

    public function renderSubscribers(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            $this->noApiKey();
            exit;
        }

        $subscribers = "Hello";

        $this->render('subscribers', [
            'subscribers' => $subscribers
        ]);
    }

    public function noApiKey()
    {
        $this->render('no_api_key');
    }

    /**
     * Render a php template. Accepts the template name without extension.
     * Template file must be in the resources folder, ie:
     * /resources/templates/[template].php
     *
     * $args is an associative array of variables available to the template.
     *
     * @param $template
     * @param $args
     */
    public function render(string $template, array $args = [])
    {
        extract($args);
        require_once(__DIR__ . "/../resources/templates/{$template}.php");
    }
}
