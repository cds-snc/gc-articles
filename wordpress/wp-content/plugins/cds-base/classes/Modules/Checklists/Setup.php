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
            add_action('enqueue_block_editor_assets', [$this, 'enqueueGutenbergScripts']);
            add_action('admin_footer', [$this, 'ppcMarkup']);
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

    public function enqueueGutenbergScripts()
    {
        wp_enqueue_script(
            'cds-base-checklists-gutenberg-js',
            plugin_dir_url(__FILE__) . '/js/index.js',
            array('wp-blocks', 'wp-element'),
            '1.0.0'
        );

        wp_enqueue_script(
            'cds-base-checklists-meta-box-js',
            plugin_dir_url(__FILE__) . '/js/meta-box.js',
            array('jquery', 'wp-blocks', 'wp-element'),
            '1.0.0'
        );
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

    /**
     * Function for HTML markup of notification.
     *
     * Shows the pop-up of warning a user or preventing a user.
     *
     * @since 1.0.0
     */
    public function ppcMarkup()
    {
        $ppc_screen = get_current_screen();
        // If not edit or add new page, post or custom post type window then return.
        if (! isset($ppc_screen->parent_base) || ( isset($ppc_screen->parent_base) && 'edit' !== $ppc_screen->parent_base )) {
            return;
        }
        ?>
        <div class="ppc-modal-warn" >
            <div id="ppc_notifications" class="ppc-popup-warn" tabindex="-1">
                <h2><?php esc_html_e('Pre-Publish Checklist', 'pre-publish-checklist'); ?></h2>
                <p class="ppc-popup-description"><?php esc_html_e('Your Pre-Publish Checklist is incomplete. What would you like to do?', 'pre-publish-checklist'); ?></p>
                <div class="ppc-button-wrapper">
                    <button class="ppc-popup-option-dontpublish"><?php esc_html_e("Don't Publish", 'pre-publish-checklist'); ?></button>
                    <button class="ppc-popup-options-publishanyway"><?php esc_html_e('Publish Anyway', 'pre-publish-checklist'); ?></button>
                </div>
            </div>
        </div>
        <div class="ppc-modal-prevent">
            <div id="ppc_notifications" class="ppc-popup-prevent" tabindex="-1">
                <h2><?php esc_html_e('Pre-Publish Checklist', 'pre-publish-checklist'); ?></h2>
                <p class="ppc-popup-description"> <?php esc_html_e('Please check all the checklist items before publishing.', 'pre-publish-checklist'); ?></p>
                <div class="ppc-prevent-button-wrapper">
                    <button class="ppc-popup-option-okay"><?php esc_html_e('Okay, Take Me to the List!', 'pre-publish-checklist'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }
}
