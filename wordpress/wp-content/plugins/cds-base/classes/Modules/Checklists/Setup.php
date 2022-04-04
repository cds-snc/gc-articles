<?php

declare(strict_types=1);

namespace CDS\Modules\Checklists;

class Setup
{
    public function __construct()
    {
        //
    }

    public static function register()
    {
        $instance = new self();
        $instance->init();
    }

    public function init()
    {
        add_action('wp_loaded', [$this, 'addActions']);
    }

    public function addActions()
    {
        // this condition never returns true if it runs it too early
        if (class_exists('PPCH_Checklists')) {
            // only add hooks when Checklists plugin is installed
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
            add_action('admin_init', [$this, 'removeUpgradeToProLink']);
        }
    }


    public function removeUpgradeToProLink()
    {
            remove_submenu_page('ppch-checklists', 'ppch-checklists-menu-upgrade-link');
    }

    public function enqueue()
    {
        wp_enqueue_style(
            'cds-base-checklists-css',
            plugin_dir_url(__FILE__) . '/css/styles.css',
            null,
            '1.0.0',
        );
    }
}
