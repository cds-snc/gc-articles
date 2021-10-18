<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\EncryptedOption\EncryptedOption;

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
            <h1>Notify and List Manager Settings</h1>
            <p></p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php" id="notify_settings_form">
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
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_values'
            ]
        );

        add_settings_field(
            'notify_api_key', // id
            _('Notify API Key', 'cds-snc'), // title
            array( $this, 'notifyApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_api_key'
            ]
        );

        add_settings_field(
            'notify_generic_template_id', // id
            _('Notify Generic TemplateId', 'cds-snc'), // title
            array( $this, 'notifyGenericTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_generic_template_id'
            ]
        );

        add_settings_field(
            'list_manager_api_key', // id
            _('List Manager API Key'), // title
            array( $this, 'listManagerApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_api_key'
            ]
        );

        add_settings_field(
            'list_manager_notify_services', // id
            _('List Manager Notify Services'), // title
            array( $this, 'listManagerNotifyServicesCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_notify_services'
            ]
        );

        add_settings_field(
            'list_manager_service_id', // id
            _('List Manager ServiceId'), // title
            array( $this, 'listManagerServiceIdCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_service_id'
            ]
        );
    }

    public function notifyApiSettingsSectionInfo()
    {
    }

    public function getObfuscatedOutputLabel($string, $labelId)
    {
        $startsWith = substr($string,0, 4);
        $endsWith = substr($string, -4);

        printf(
            '<span class="hidden_keys" id="%s">Current value: <span class="sr-only">Starts with </span>%s<span aria-hidden="true"> … </span><span class="sr-only"> and ends with</span>%s</span>', $labelId, $startsWith, $endsWith
        );
    }

    public function notifyApiKeyCallback()
    {
        if ($string = $this->NOTIFY_API_KEY) {
            $this->getObfuscatedOutputLabel($string, 'notify_api_key_value');
        }
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_API_KEY" id="notify_api_key" aria-describedby="notify_api_key_value" value="">'
        );
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
        if ($string = $this->LIST_MANAGER_API_KEY) {
            $this->getObfuscatedOutputLabel($string, 'list_manager_api_key_value');
        }
        printf(
            '<input class="regular-text" type="text" name="LIST_MANAGER_API_KEY" id="list_manager_api_key" aria-describedby="list_manager_api_key_value" value="">'
        );
    }

    public function listManagerNotifyServicesCallback()
    {
        if ($string = $this->LIST_MANAGER_NOTIFY_SERVICES) {
            $this->getObfuscatedOutputLabel($string, 'list_manager_notify_services_value');
        }
        printf(
            '<input class="regular-text" type="text" name="LIST_MANAGER_NOTIFY_SERVICES" id="list_manager_notify_services" aria-describedby="list_manager_notify_services_value" value="">'
        );
    }

    public function listManagerServiceIdCallback()
    {
        if ($string = $this->LIST_MANAGER_SERVICE_ID) {
            $this->getObfuscatedOutputLabel($string, 'list_manager_service_id_value');
        }
        printf(
            '<input class="regular-text" type="text" name="LIST_MANAGER_SERVICE_ID" id="list_manager_service_id" aria-describedby="list_manager_service_id_value" value="">'
        );
    }

    public function listValuesCallback()
    {
        printf(
            '<textarea name="list_values" id="list_values" rows="4" cols="50">%s</textarea>

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
            font-size: 1.2em;
            padding: 1px;
            display: block;
            color: grey;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            border: 0;
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