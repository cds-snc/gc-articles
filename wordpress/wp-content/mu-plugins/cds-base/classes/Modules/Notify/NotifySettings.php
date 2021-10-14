<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\EncryptedOption;

class NotifySettings
{
    protected EncryptedOption $encryptedOption;
    protected string $admin_page = 'cds_notify_send';

    private string $NOTIFY_API_KEY;
    private string $NOTIFY_GENERIC_TEMPLATE_ID;
    private string $LIST_MANAGER_API_KEY;
    private string $LIST_MANAGER_NOTIFY_SERVICES;
    private string $LIST_MANAGER_SERVICE_ID;
    private string $list_values;

    public function __construct(EncryptedOption $encryptedOption)
    {
        $this->encryptedOption = $encryptedOption;
    }

    public static function register(EncryptedOption $encryptedOption)
    {
        $instance = new self($encryptedOption);

        add_action('admin_menu', [$instance, 'notifyApiSettingsAddPluginPage']);
        add_action('admin_init', [$instance, 'notifyApiSettingsPageInit']);
        add_action('admin_head', [$instance, 'addStyles']);

        $encryptedOptions = [
            'NOTIFY_API_KEY',
            'LIST_MANAGER_API_KEY',
            'LIST_MANAGER_NOTIFY_SERVICES',
            'LIST_MANAGER_SERVICE_ID'
        ];

        foreach ($encryptedOptions as $option) {
            add_filter("pre_update_option_{$option}", [$instance, 'encryptOption']);
            add_filter("option_{$option}", [$instance, 'decryptOption']);
        }
    }

    public function notifyApiSettingsAddPluginPage()
    {
        /* add_options_page(
            'Notify API Settings', // page_title
            'Notify API Settings', // menu_title
            'manage_options', // capability
            'notify-api-settings', // menu_slug
            array( $this, 'notifyApiSettingsCreateAdminPage' ) // function
        ); */
        /*
        add_menu_page(
            'Notify API Settings', // page_title
            'Notify API Settings', // menu_title
            'manage_options', // capability
            'notify-api-settings', // menu_slug
            array( $this, 'notifyApiSettingsCreateAdminPage'), // function
            'dashicons-admin-generic', // icon_url
            99 // position
        );*/
        add_submenu_page(
            $this->admin_page,
            __('Settings'),
            __('Settings'),
            'manage_options',
            $this->admin_page . '_settings',
            [$this, 'notifyApiSettingsCreateAdminPage'],
        );
    }

    public function notifyApiSettingsCreateAdminPage()
    {
        $this->NOTIFY_API_KEY = get_option('NOTIFY_API_KEY') ?: '';
        $this->NOTIFY_GENERIC_TEMPLATE_ID = get_option('NOTIFY_GENERIC_TEMPLATE_ID') ?: '';
        $this->LIST_MANAGER_API_KEY = get_option('LIST_MANAGER_API_KEY') ?: '';
        $this->LIST_MANAGER_NOTIFY_SERVICES = get_option('LIST_MANAGER_NOTIFY_SERVICES') ?: '';
        $this->LIST_MANAGER_SERVICE_ID = get_option('LIST_MANAGER_SERVICE_ID') ?: '';
        $this->list_values = get_option('list_values') ?: '';
        ?>

        <div class="wrap">
            <h2>Notify and List Manager Settings</h2>
            <p></p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                settings_fields('notify_api_settings_option_group');
                do_settings_sections('notify-api-settings-admin');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function notifyApiSettingsPageInit()
    {
        register_setting(
            'notify_api_settings_option_group',
            'list_values'
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'NOTIFY_API_KEY',
            function ($input) {
                if ($input == '') {
                    return get_option('NOTIFY_API_KEY');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'NOTIFY_GENERIC_TEMPLATE_ID',
            function ($input) {
                if ($input == '') {
                    return get_option('NOTIFY_GENERIC_TEMPLATE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_API_KEY',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_API_KEY');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_NOTIFY_SERVICES',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_NOTIFY_SERVICES');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_SERVICE_ID',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_SERVICE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        add_settings_section(
            'notify_api_settings_setting_section', // id
            'Notify', // title
            array( $this, 'notifyApiSettingsSectionInfo'), // callback
            'notify-api-settings-admin' // page
        );

        add_settings_section(
            'list_manager_settings_section', // id
            'List manager', // title
            array( $this, 'notifyApiSettingsSectionInfo'), // callback
            'notify-api-settings-admin' // page
        );

        add_settings_field(
            'list_values', // id
            _('List Values JSON', 'cds-snc'), // title
            array( $this, 'listValuesCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section' // section
        );

        add_settings_field(
            'notify_api_key', // id
            'NOTIFY_API_KEY', // title
            array( $this, 'notifyApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'notify_generic_template_id', // id
            'NOTIFY_GENERIC_TEMPLATE_ID', // title
            array( $this, 'notifyGenericTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'list_manager_api_key', // id
            'LIST_MANAGER_API_KEY', // title
            array( $this, 'listManagerApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section' // section
        );

        add_settings_field(
            'list_manager_notify_services', // id
            'LIST_MANAGER_NOTIFY_SERVICES', // title
            array( $this, 'listManagerNotifyServicesCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section' // section
        );

        add_settings_field(
            'list_manager_service_id', // id
            'LIST_MANAGER_SERVICE_ID', // title
            array( $this, 'listManagerServiceIdCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section' // section
        );
    }

    public function notifyApiSettingsSectionInfo()
    {
    }

    public function notifyApiKeyCallback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="NOTIFY_API_KEY" id="notify_api_key" value="">',
            $this->NOTIFY_API_KEY ? $this->obfuscate($this->NOTIFY_API_KEY) : ''
        );
    }

    /**
     * Obfuscate the string
     *
     * @param $string
     * @param  int  $maxChars
     * @return array|string
     */
    public function obfuscate($string, $maxChars = 8): array|string
    {
        $textLength = strlen($string);

        return substr_replace($string, '......', $maxChars / 2, $textLength - $maxChars);
    }

    public function notifyGenericTemplateIdCallback()
    {
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_GENERIC_TEMPLATE_ID" id="notify_generic_template_id" value="%s">',
            $this->NOTIFY_GENERIC_TEMPLATE_ID ? $this->NOTIFY_GENERIC_TEMPLATE_ID : ''
        );
    }

    public function listManagerApiKeyCallback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_API_KEY" id="list_manager_api_key" value="">',
            $this->LIST_MANAGER_API_KEY ? $this->obfuscate($this->LIST_MANAGER_API_KEY) : ''
        );
    }

    public function listManagerNotifyServicesCallback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_NOTIFY_SERVICES" id="list_manager_notify_services" value="">',
            $this->LIST_MANAGER_NOTIFY_SERVICES ? $this->obfuscate($this->LIST_MANAGER_NOTIFY_SERVICES) : ''
        );
    }

    public function listManagerServiceIdCallback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_SERVICE_ID" id="list_manager_service_id" value="">',
            $this->LIST_MANAGER_SERVICE_ID ? $this->obfuscate($this->LIST_MANAGER_SERVICE_ID) : ''
        );
    }

    public function listValuesCallback()
    {
        printf(
            '<textarea name="list_values" rows="4" cols="50">%s</textarea>

                <p class="description" id="new-admin-email-description">
                    ' . _('Format', 'cds-snc') . ':
                    <pre>[{"id":"123", "type":"email", "label":"my-list"}]</pre>
                </p>',
            $this->list_values ?: ''
        );
    }

    public function addStyles()
    {
        ?><style type="text/css">
        .hidden_keys {
            font-size: 1.5em;
            padding: 1px;
            display: block;
        }
    </style><?php
    }

    public function encryptOption($value): string
    {
        return $this->encryptedOption->encryptString($value);
    }

    public function decryptOption($value): string
    {
        return $this->encryptedOption->decryptString($value);
    }
}