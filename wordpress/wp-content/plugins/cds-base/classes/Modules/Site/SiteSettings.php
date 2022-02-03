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
            __('Site Settings', "cds-snc"), // page_title
            __('Site Settings', "cds-snc"), // menu_title
            'manage_options', // capability
            'collection-settings', // menu_slug
            array( $this, 'collectionSettingsCreateAdminPage' ) // function
        );
    }

    public function collectionSettingsCreateAdminPage()
    {

        ?>

        <div class="wrap">
            <h1><?php _e('Site Settings', 'cds-snc') ?></h1>
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
        // add section
        add_settings_section(
            'collection_settings_section', // id
            get_bloginfo("name"), // title
            null, // callback
            'collection-settings-admin' // page
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode',
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode_maintenance_page',
        );

        // reading options
        register_setting(
            'site_settings_group', // option_group
            'show_on_front',
        );

        register_setting(
            'site_settings_group', // option_group
            'page_on_front',
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode',
        );

        register_setting(
            'site_settings_group', // option_group
            'blogname',
        );

        register_setting(
            'site_settings_group', // option_group
            'blogdescription',
        );

        // add fields
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

        add_settings_field(
            'page_on_front', // id
            _('Home Page', 'cds-snc'), // title
            array( $this, 'readingSettingsCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'page_on_front'
            ]
        );

        add_settings_field(
            'blogname', // id
            _('Site Name', 'cds-snc'), // title
            array( $this, 'blogNameCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'blogname'
            ]
        );

        add_settings_field(
            'blogdescription', // id
            _('Site Description', 'cds-snc'), // title
            array( $this, 'blogDescriptionCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'blogdescription'
            ]
        );

        add_settings_field(
            'blog_public', // id
            _('Search engine visibility', 'cds-snc'), // title
            array( $this, 'indexSiteCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section', // section
            [
                'label_for' => 'blog_public'
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

    public function readingSettingsCallback()
    {

           echo '<input name="show_on_front" type="hidden" value="page">';

            wp_dropdown_pages(
                array(
                    'name' => 'page_on_front',
                    'echo' => 1,
                    'show_option_none' => __('&mdash; Select &mdash;'),
                    'option_none_value' => '0',
                    'selected' => get_option('page_on_front'),
                )
            );
    }

    public function blogNameCallback()
    {
        ?>
        <input name="blogname" type="text" id="blogname" class="regular-text" value="<?php echo get_option("blogname");?>">
        <?php
    }

    public function blogDescriptionCallback()
    {
        ?>
        <input name="blogdescription" type="text" id="blogdescription" class="regular-text" value="<?php echo get_option("blogdescription");?>">
        <?php
    }

    public function indexSiteCallback()
    {
        ?>
        <input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked('0', get_option('blog_public')); ?> />
        <?php _e('Discourage search engines from indexing this site'); ?>
        <p class="description"><?php _e('It is up to search engines to honor this request.'); ?></p>
        <?php
    }
}