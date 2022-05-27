<?php

declare(strict_types=1);

namespace GCLists;

use CDS\Modules\Notify\FormHelpers;
use CDS\Modules\Notify\Notices;

class Menu
{
    protected static $instance;
    protected string $admin_page = 'gc_lists';

    public static function getInstance(): Menu
    {
        is_null(self::$instance) and self::$instance = new self();
        return self::$instance;
    }

    public function register()
    {
        add_action('admin_menu', [$this, 'addMenu']);
    }

    public function addMenu()
    {
        add_menu_page(
            __('Send Notify Template', "cds-snc"),
            __('GC Lists', "cds-snc"),
            'list_manager_bulk_send',
            $this->admin_page,
            [$this, 'renderForm'],
            'dashicons-email'
        );
    }

    public function renderForm(): void
    {
        if (!get_option('NOTIFY_API_KEY')) {
            FormHelpers::noApiKey();
        } else {
            echo "Hello";
        }
    }
}
