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

        // Both of these need to be called _before_ wp_loaded

        // called if plugin is active
        add_action('publishpress_checklists_modules_loaded', [$this, 'addChecklistRole']);
        // called when plugin is deactivated
        add_action('deactivate_publishpress-checklists/publishpress-checklists.php', [$this, 'removeChecklistRole']);
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

    public function addChecklistRole(): void
    {
        $this->modifyChecklistRole(addRole: true);
    }

    public function removeChecklistRole(): void
    {
        $this->modifyChecklistRole(addRole: false);
    }

    public function modifyChecklistRole(bool $addRole = true): void
    {
        $roles = ['administrator', 'gceditor'];

        foreach ($roles as $role) {
            $roleObj = get_role($role);

            if (!is_null($roleObj)) {
                if ($addRole) {
                    $roleObj->add_cap('manage_checklists');
                } else {
                    $roleObj->remove_cap('manage_checklists');
                }
            }
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
