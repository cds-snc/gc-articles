<?php

declare(strict_types=1);

namespace CDS\Modules\Site;

class SiteSettings
{
    public static function register()
    {
        $instance = new self();

        add_action('admin_menu', [$instance, 'collectionSettingsAddPluginPage'], 99);
        add_action('admin_init', [$instance, 'collectionSettingsPageInit']);
        add_action('update_option', [$instance, 'logChangedOption'], 10, 4);

        add_filter('collection_settings_option_group', function ($capability) {
            return user_can('manage_options');
        });
    }

    public function logChangedOption($option, $old_value, $value)
    {
        $filterOptions = [
            'collection_mode',
            'collection_mode_maintenance_page',
            'show_on_front',
            'page_on_front',
            'collection_mode',
            'blogname',
            'blogdescription',
            'show_wet_menu',
            'show_search',
            'show_breadcrumbs',
            'fip_href',
        ];

        if (function_exists("SimpleLogger")) {
            if (in_array($option, $filterOptions)) {
                SimpleLogger()->info("$option changed from $old_value to $value");
            }
        }
    }

    public function collectionSettingsAddPluginPage()
    {
        add_options_page(
            __('Site Settings', "cds-snc"), // page_title
            __('Site Settings', "cds-snc"), // menu_title
            'manage_options', // capability
            'collection-settings', // menu_slug
            array($this, 'collectionSettingsCreateAdminPage') // function
        );
    }

    public function collectionSettingsCreateAdminPage()
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Site Settings', 'cds-snc') ?></h1>

            <form method="post" action="options.php" id="collection_settings_form" class="gc-form-wrapper">
                <?php
                settings_fields('site_settings_group');
                do_settings_sections('collection-settings-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function collectionSettingsPageInit()
    {
        // add section GENERAL
        add_settings_section(
            'collection_settings_section_general', // id
            __("General"), // title
            null, // callback
            'collection-settings-admin' // page
        );

        // add section MAINTENANCE MODE
        add_settings_section(
            'collection_settings_section_maintenance', // id
            __("Maintenance mode", 'cds-snc'), // title
            array($this, 'maintenanceDescriptionCallback'), // callback
            'collection-settings-admin' // page
        );

        // add section SITE CONFIGURATION
        add_settings_section(
            'collection_settings_section_config', // id
            __("Site configuration", 'cds-snc'), // title
            null, // callback
            'collection-settings-admin' // page
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode_maintenance_page',
            function ($input) {
                return intval($input);
            }
        );

        // reading options
        register_setting(
            'site_settings_group', // option_group
            'show_on_front',
            function ($input) {
                return 'page'; // this is a hardcoded hidden field
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'blog_public',
            function ($input) {
                if ($input === 0) {
                    return 0;
                }

                return 1;
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'page_on_front',
            function ($input) {
                return intval($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'collection_mode',
            function ($input) {
                if (in_array($input, ['maintenance', 'live'])) {
                    return $input;
                }

                return 'live';
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'blogname',
            function ($input) {
                return sanitize_text_field($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'blogdescription',
            function ($input) {
                return sanitize_text_field($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'show_wet_menu',
            function ($input) {
                if (in_array($input, ['canada', 'custom', 'none'])) {
                    return $input;
                }

                return '';
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'show_search',
            function ($input) {
                if (in_array($input, ['on', 'off'])) {
                    return $input;
                }

                return 'off';
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'show_breadcrumbs',
            function ($input) {
                if (in_array($input, ['on', 'off'])) {
                    return $input;
                }

                return 'off';
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'fip_href',
            function ($input) {
                return esc_url_raw($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'analytics_id',
            function ($input) {
                return sanitize_text_field($input);
            }
        );

        register_setting(
            'site_settings_group', // option_group
            'google_tag_manager_id',
            function ($input) {
                return sanitize_text_field($input);
            }
        );

        // add fields GENERAL
        add_settings_field(
            'blogname', // id
            __('Site Name', 'cds-snc'), // title
            array($this, 'blogNameCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_general', // section
            [
                'label_for' => 'blogname'
            ]
        );

        add_settings_field(
            'blogdescription', // id
            __('Site Description', 'cds-snc'), // title
            array($this, 'blogDescriptionCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_general', // section
            [
                'label_for' => 'blogdescription'
            ]
        );

        add_settings_field(
            'page_on_front', // id
            __('Home Page', 'cds-snc'), // title
            array($this, 'readingSettingsCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_general', // section
            [
                'label_for' => 'page_on_front'
            ]
        );

        // add fields MAINTENANCE
        $maintenanceTitle = __('Activate maintenance mode', 'cds-snc');
        add_settings_field(
            'collection_mode', // id
            $maintenanceTitle, // title
            array($this, 'collectionModeCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_maintenance', // section
            [
                'label_for' => 'collection_mode',
                'title'     => $maintenanceTitle
            ]
        );

        add_settings_field(
            'collection_mode_maintenance_page', // id
            __('Maintenance Page', 'cds-snc'), // title
            array($this, 'collectionMaintenancePageCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_maintenance', // section
            [
                'label_for' => 'collection_mode_maintenance_page'
            ]
        );

        add_settings_field(
            'blog_public', // id
            __('Search engine visibility', 'cds-snc'), // title
            array($this, 'indexSiteCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_general', // section
            [
                'label_for' => 'blog_public'
            ]
        );

        // add fields MAINTENANCE
        $menuTitle = __('Top menu', 'cds-snc');
        add_settings_field(
            'show_wet_menu', // id
            $menuTitle, // title
            array($this, 'wetMenuCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_config', // section
            [ // args
                'label_for' => 'show_wet_menu',
                'title'     => $menuTitle
            ],
        );

        $searchTitle = __('Search bar', 'cds-snc');
        add_settings_field(
            'show_search', // id
            $searchTitle, // title
            array($this, 'showSearchCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_config', // section
            [
                'label_for' => 'show_search',
                'title'     => $searchTitle
            ]
        );

        /**
         * Setting to control the display of breadcrumbs
         */
        $breadcrumbsTitle = __('Breadcrumbs', 'cds-snc');
        add_settings_field(
            'show_breadcrumbs', // id
            $breadcrumbsTitle, // title
            array($this, 'breadcrumbsCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_config', // section
            [
                'label_for' => 'show_breadcrumbs',
                'title'     => $breadcrumbsTitle
            ]
        );

        add_settings_field(
            'fip_href', // id
            __('Where should the Canada.ca header link to?', 'cds-snc'), // title
            array($this, 'fipHrefCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_config', // section
            [
                'label_for' => 'fip_href'
            ]
        );

        // add section Analytics
        add_settings_section(
            'collection_settings_section_analytics', // id
            __("Analytics"), // title
            null, // callback
            'collection-settings-admin' // page
        );

        add_settings_field(
            'analytics_id', // id
            __('Analytics id', 'cds-snc'), // title
            array($this, 'analyticsCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_analytics', // section
            [
                'label_for' => 'analytics_id'
            ]
        );

        add_settings_field(
            'google_tag_manager_id', // id
            __('Google Tag Manager id', 'cds-snc'), // title
            array($this, 'googleTagManagerCallback'), // callback
            'collection-settings-admin', // page
            'collection_settings_section_analytics', // section
            [
                'label_for' => 'google_tag_manager_id'
            ]
        );
    }

    public function collectionModeCallback($args)
    {
        $collection_mode = get_option('collection_mode');

        echo('<fieldset>');
        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $args['title']);
        printf(
            '<input type="radio" name="collection_mode" id="collection_maintenance" value="maintenance" %s /> <label for="collection_maintenance">%s</label><br />',
            checked('maintenance', $collection_mode, false),
            __('Turn on', "cds-snc")
        );
        printf(
            '<input type="radio" name="collection_mode" id="collection_live" value="live" %s /> <label for="collection_live">%s</label><br />',
            checked('live', $collection_mode, false),
            __('Turn off', "cds-snc")
        );
        echo('</fieldset>');
    }

    public function wetMenuCallback($args)
    {
        $show_wet_menu = get_option('show_wet_menu');
        $locations = get_nav_menu_locations();
        $customHeaderMenu = (array_key_exists('header', $locations) && is_int($locations['header'])) ? $locations['header'] : false;
        if ($customHeaderMenu) {
            // set it to the name of the menu
            $customHeaderMenu = wp_get_nav_menu_object($customHeaderMenu)->name;
        }

        echo('<fieldset>');
        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $args['title']);
        printf(
            '<input type="radio" name="show_wet_menu" id="show_wet_menu_canada" value="canada" %s /> <label for="show_wet_menu_canada">%s</label><br />',
            checked("canada", $show_wet_menu, false),
            __('Show Canada.ca menu', "cds-snc")
        );
        printf(
            '<input type="radio" name="show_wet_menu" id="show_wet_menu_custom" value="custom" %s %s /> <label for="show_wet_menu_custom">%s%s</label><br />',
            checked("custom", $show_wet_menu, false),
            $customHeaderMenu ? "" : "disabled", // if no menu, disabled input
            __('Show custom menu', "cds-snc"),
            $customHeaderMenu ? ': “' . $customHeaderMenu . '”' : '' // if menu, echo its name into the label
        );

        if (!$customHeaderMenu) {
            printf(
                '<p class="description" style="margin-top: -5px; margin-bottom: 5px;">%s <a href="%s">%s</a>.</p>',
                __("You can set a custom menu in", "cds-snc"),
                get_admin_url() . 'nav-menus.php',
                __("Appearance > Menus", "cds-snc")
            );
        }

        printf(
            '<input type="radio" name="show_wet_menu" id="show_wet_menu_none" value="none" %s /> <label for="show_wet_menu_none">%s</label><br />',
            checked("none", $show_wet_menu, false),
            __('Hide top menu', "cds-snc")
        );
        echo('</fieldset>');
    }

    public function showSearchCallback($args)
    {
        $show_search = get_option('show_search');

        echo('<fieldset>');
        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $args['title']);
        printf(
            '<input type="radio" name="show_search" id="show_search_on" value="on" %s /> <label for="show_search_on">%s</label><br />',
            checked('on', $show_search, false),
            __('Show the search bar', "cds-snc")
        );
        printf(
            '<input type="radio" name="show_search" id="show_search_off" value="off" %s /> <label for="show_search_off">%s</label><br />',
            checked('off', $show_search, false),
            __('Hide the search bar', "cds-snc")
        );
        echo('</fieldset>');
    }

    public function breadcrumbsCallback($args)
    {
        $show_breadcrumbs = get_option('show_breadcrumbs');

        echo('<fieldset>');
        printf('<legend class="screen-reader-text"><span>%s</span></legend>', $args['title']);
        printf(
            '<input type="radio" name="show_breadcrumbs" id="show_breadcrumbs_on" value="on" %s /> <label for="show_breadcrumbs_on">%s</label><br />',
            checked("on", $show_breadcrumbs, false),
            __('Show breadcrumbs', "cds-snc")
        );
        printf(
            '<input type="radio" name="show_breadcrumbs" id="show_breadcrumbs_off" value="off" %s /> <label for="show_breadcrumbs_off">%s</label><br />',
            checked("off", $show_breadcrumbs, false),
            __('Hide breadcrumbs', "cds-snc")
        );
        echo('</fieldset>');
    }

    public function fipHrefCallback()
    {
        $fipUrl = get_option("fip_href", "");
        $value  = $fipUrl ? esc_url($fipUrl) : home_url();
        $value  = str_replace('http://', 'https://', $value);

        ?>
        <input name="fip_href" type="text" id="fip_href" class="regular-text" value="<?php echo $value; ?>">
        <?php
    }

    public function analyticsCallback()
    {
        $analyticsId = get_option("analytics_id", "");
        ?>
        <input name="analytics_id" type="text" id="analytics_id" class="regular-text"
               value="<?php echo esc_attr($analyticsId); ?>">
        <?php
    }

    public function googleTagManagerCallback()
    {
        $googleTagManagerId = get_option("google_tag_manager_id", "");
        ?>
        <input name="google_tag_manager_id" type="text" id="google_tag_manager_id" class="regular-text"
               value="<?php echo esc_attr($googleTagManagerId); ?>">
        <?php
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
                'name'              => 'page_on_front',
                'echo'              => 1,
                'show_option_none'  => __('&mdash; Select &mdash;'),
                'option_none_value' => '0',
                'selected'          => get_option('page_on_front'),
            )
        );
    }

    public function blogNameCallback()
    {
        ?>
        <input name="blogname" type="text" id="blogname" class="regular-text"
             value="<?php echo esc_attr(get_option("blogname")); ?>">
        <?php
    }

    public function blogDescriptionCallback()
    {
        ?>
        <input name="blogdescription" type="text" id="blogdescription" class="regular-text"
             value="<?php echo esc_attr(get_option("blogdescription")); ?>">
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

    public function maintenanceDescriptionCallback()
    {
        echo '<p>';
        echo __(
            'In maintenance mode, pages and articles you publish will <strong>not</strong> be publicly visible.',
            'cds-snc'
        );
        echo '<br />';
        echo __(
            'Logged-in users will be able to create and view content, but all other visitors will be redirected to the maintenance page.',
            'cds-snc'
        );
        echo '</p>';
    }
}
