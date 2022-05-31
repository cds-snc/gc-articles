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
        ?>
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>
            <?php
            echo sprintf(
                __('You must configure your <a href="%s">Notify API Key</a>', 'cds-snc'),
                admin_url("options-general.php?page=notify-settings")
            );
            ?>
        </p>
        <?php
    }

    public function render($template, $args)
    {
        extract($args);
        require_once(__DIR__ . "/../resources/templates/{$template}.php");
    }
}
