<?php

declare(strict_types=1);

namespace CDS\Modules\Site;

class SiteSettings
{
    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();

        add_action('admin_menu', [$instance, 'collectionSettingsAddPluginPage']);
        add_action('admin_init', [$instance, 'collectionSettingsPageInit']);

        add_filter('collection_settings_option_group', function ($capability) {
            return user_can('manage_options');
        });
    }

    public function collectionSettingsAddPluginPage()
    {
        add_options_page(
            __('Collection Settings'), // page_title
            __('Collection Settings'), // menu_title
            'manage_options', // capability
            'collection-settings', // menu_slug
            array( $this, 'collectionSettingsCreateAdminPage' ) // function
        );
    }

    public function collectionSettingsCreateAdminPage()
    {

        ?>

        <div class="wrap">
            <h1><?php _e('Collection Settings', 'cds-snc') ?></h1>
            <?php settings_errors(); ?>

            <form method="post" action="options.php" id="collection_settings_form" class="gc-form-wrapper">
                <?php
                settings_fields('site_settings_group');
                do_settings_sections('collection-settings-admin');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function collectionSettingsPageInit()
    {
        register_setting(
            'site_settings_group', // option_group
            'collection_mode',
            function ($input) {
                if ($input !== "maintenance" || $input !== "live") {
                    return "maintenance";
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode_maintenance_page',
        );

        add_settings_section(
            'collection_settings_section', // id
            get_bloginfo("name"), // title
            null, // callback
            'collection-settings-admin' // page
        );

        add_settings_field(
            'collection_mode', // id
            _('Mode', 'cds-snc'), // title
            array( $this, 'collectionModeCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'collection_mode'
            ]
        );

        add_settings_field(
            'collection_mode_maintenance_page', // id
            _('Maintenance Page', 'cds-snc'), // title
            array( $this, 'collectionMaintenancePageCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'collection_mode_maintenance_page'
            ]
        );
    }

    public function collectionModeCallback()
    {
        $collection_mode = get_option('collection_mode');

        printf('<input type="radio" name="collection_mode" id="collection_maintenance" value="maintenance" %s /> <label for="collection_maintenance">Maintenance</label><br />', checked('maintenance', $collection_mode, false));
        printf('<input type="radio" name="collection_mode" id="collection_live" value="live" %s /> <label for="collection_live">Live</label><br />', checked('live', $collection_mode, false));
    }

    public function collectionMaintenancePageCallback()
    {
        wp_dropdown_pages(
            array(
                'name'              => 'collection_mode_maintenance_page',
                'echo'              => 1,
                'show_option_none'  => __('&mdash; Select &mdash;'),
                'option_none_value' => '0',
                'selected'          => get_option('collection_mode_maintenance_page'),
            )
        );
    }
}