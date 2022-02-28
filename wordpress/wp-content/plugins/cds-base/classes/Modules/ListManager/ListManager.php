<?php

declare(strict_types=1);

namespace CDS\Modules\ListManager;

class ListManager
{
    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue()
    {
        wp_enqueue_style(
            'list-manager',
            plugin_dir_url(__FILE__) . 'app/build/static/css/main.6ae49f62.css',
            null,
            '1.0.0',
        );
        
        wp_enqueue_script(
            'list-manager',
            plugin_dir_url(__FILE__) . 'app/build/static/js/main.3b907e1c.js',
            null,
            '1.0.0',
            true,
        );
    }
}
