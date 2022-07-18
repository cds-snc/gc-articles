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

            add_filter('publishpress_checklists_supported_module_post_types_args', [$this, 'onlyPublicPostTypes']);
        }
    }

    /* Only show public post types in the settings (we don't want Navigation Menus, for example)
       https://github.com/publishpress/PublishPress-Checklists/blob/73b3a4b48de65f116b22671431f948fe0b527694/core/Legacy/Module.php#L96
    */
    public function onlyPublicPostTypes($postTypeArgs)
    {
        $postTypeArgs['public'] = true;
        return $postTypeArgs;
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
                <h2><?php _e('Are you sure you want to publish?', 'cds-snc'); ?></h2>
                <p class="ppc-popup-description"><?php _e('There are still recommended items remaining on your checklist. What would you like to do?', 'cds-snc'); ?></p>
                <div class="ppc-button-wrapper">
                    <button class="ppc-popup-option-dontpublish"><?php _e('Don’t publish', 'cds-snc'); ?></button>
                    <button class="ppc-popup-options-publishanyway"><?php _e('Publish anyway', 'cds-snc'); ?></button>
                </div>
            </div>
        </div>
        <div class="ppc-modal-prevent">
            <div id="ppc_notifications" class="ppc-popup-prevent" tabindex="-1">
                <h2><?php _e('Publishing not allowed', 'cds-snc'); ?></h2>
                <p class="ppc-popup-description"> <?php _e('Please complete all the required checklist items before publishing.', 'cds-snc'); ?></p>
                <div class="ppc-prevent-button-wrapper">
                    <button class="ppc-popup-option-okay"><?php _e('Okay, take me to the list.', 'cds-snc'); ?></button>
                </div>
            </div>
        </div>
        <div class="ppc-button-container">
            <button type="button" class="components-button is-button is-primary ppc-publish" id="ppc-publish"><?php _e('Publish…', 'cds-snc'); ?></button>
            <button type="button" class="components-button is-button is-primary ppc-publish" id="ppc-update"><?php _e('Update…', 'cds-snc'); ?></button>
        </div>
        <?php
    }
}
