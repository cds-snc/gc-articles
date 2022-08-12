<?php

declare(strict_types=1);

namespace GCLists;

use GCLists\Concerns\RendersTemplates;

class Menu
{
    use RendersTemplates;

    protected static $instance;
    protected string $messagesPageSlug = 'gc-lists_messages';
    protected string $subscribersPageSlug = 'gc-lists_subscribers';
    protected string $capability = 'list_manager_bulk_send';

    public static function getInstance(): Menu
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    private function isNotifyConfigured(): bool
    {
        if (
            get_option('NOTIFY_API_KEY') &&
            get_option('NOTIFY_GENERIC_TEMPLATE_ID') &&
            get_option('NOTIFY_SUBSCRIBE_TEMPLATE_ID')
        ) {
            return true;
        }

        return false;
    }

    public function addMenu()
    {
        add_menu_page(
            __('GC Lists', "gc-lists"),
            __('GC Lists', "gc-lists"),
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
            __('Messages', 'gc-lists'),
            __('Messages', 'gc-lists'),
            $this->capability,
            $this->messagesPageSlug,
            [$this, 'renderMessages'],
        );
    }

    public function addSubscriberListsSubmenuItem()
    {
        add_submenu_page(
            $this->messagesPageSlug,
            __('Mailing lists', 'gc-lists'),
            __('Mailing lists', 'gc-lists'),
            $this->capability,
            $this->subscribersPageSlug,
            [$this, 'renderSubscribers'],
        );
    }

    /**
     * Render messages template
     */
    public function renderMessages(): void
    {
        if (!$this->isNotifyConfigured()) {
            $this->renderNoApiKey();
            exit;
        }

        $this->render('messages', [
            'title' => __('Messages', 'gc-lists'),
            'services' => Utils::getServices(),
            'user' => Utils::getUserPermissions(),
        ]);
    }

    /**
     * Render subscribers template
     */
    public function renderSubscribers(): void
    {
        if (!$this->isNotifyConfigured()) {
            $this->renderNoApiKey();
            exit;
        }

        $this->render('subscribers', [
            'title' => __('Mailing lists', 'gc-lists'),
            'services' => Utils::getServices(),
            'user' => Utils::getUserPermissions(),
        ]);
    }

    /**
     * Render NoApiKey error
     */
    public function renderNoApiKey()
    {
        $values = [
            'NOTIFY_API_KEY' => get_option('NOTIFY_API_KEY'),
            'NOTIFY_GENERIC_TEMPLATE_ID' => get_option('NOTIFY_GENERIC_TEMPLATE_ID'),
            'NOTIFY_SUBSCRIBE_TEMPLATE_ID' => get_option('NOTIFY_SUBSCRIBE_TEMPLATE_ID')
        ];

        $missingValues = array_filter($values, function ($var) {
            return !$var ? true : false; // return falsey values
        });

        $this->render('no_api_key', [
            'title' => esc_html(get_admin_page_title()),
            'missingValues' => array_keys($missingValues)
        ]);
    }
}
