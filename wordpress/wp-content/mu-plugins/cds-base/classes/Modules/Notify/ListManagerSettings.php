<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\EncryptedOption\EncryptedOption;

class ListManagerSettings
{
    protected EncryptedOption $encryptedOption;
    protected string $admin_page = 'cds_notify_send';

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

        add_action('admin_menu', [$instance, 'listManagerSettingsAddPluginPage']);
        add_action('admin_init', [$instance, 'listManagerSettingsPageInit']);
        add_action('admin_head', [$instance, 'addStyles']); // @TODO

        add_filter('option_page_capability_list_manager_settings_option_group', function ($capability) {
            return 'manage_list_manager';
        });

        $encryptedOptions = [
            'LIST_MANAGER_API_KEY',
            'LIST_MANAGER_NOTIFY_SERVICES',
            'LIST_MANAGER_SERVICE_ID' // @TODO: does this need to be encrypted?
        ];

        foreach ($encryptedOptions as $option) {
            add_filter("pre_update_option_{$option}", [$instance, 'encryptOption']);
            add_filter("option_{$option}", [$instance, 'decryptOption']);
        }
    }

    public function listManagerSettingsAddPluginPage()
    {
        add_submenu_page(
            $this->admin_page,
            __('List Manager'),
            __('List Manager'),
            'manage_list_manager',
            'cds_list_manager_settings',
            [$this, 'listManagerSettingsCreateAdminPage'],
        );
    }

    public function listManagerSettingsCreateAdminPage()
    {
        $this->LIST_MANAGER_API_KEY = get_option('LIST_MANAGER_API_KEY') ?: '';
        $this->LIST_MANAGER_NOTIFY_SERVICES = get_option('LIST_MANAGER_NOTIFY_SERVICES') ?: '';
        $this->LIST_MANAGER_SERVICE_ID = get_option('LIST_MANAGER_SERVICE_ID') ?: '';
        $this->list_values = get_option('list_values') ?: '';
        ?>

        <div class="wrap">
            <h1><?php _e('List Manager Settings', 'cds-snc') ?></h1>
            <p></p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php" id="list_manager_settings_form" class="gc-form-wrapper">
                <?php
                settings_fields('list_manager_settings_option_group');
                do_settings_sections('list-manager-settings-admin');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function listManagerSettingsPageInit()
    {
        register_setting(
            'list_manager_settings_option_group',
            'list_values'
        );

        register_setting(
            'list_manager_settings_option_group', // option_group
            'LIST_MANAGER_API_KEY',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_API_KEY');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'list_manager_settings_option_group', // option_group
            'LIST_MANAGER_NOTIFY_SERVICES',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_NOTIFY_SERVICES');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'list_manager_settings_option_group', // option_group
            'LIST_MANAGER_SERVICE_ID',
            function ($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_SERVICE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        add_settings_section(
            'list_manager_settings_section', // id
            _('List manager', 'cds-snc'), // title
            array( $this, 'listManagerSettingsSectionInfo'), // callback
            'list-manager-settings-admin' // page
        );

        add_settings_field(
            'list_values', // id
            _('List Values JSON', 'cds-snc'), // title
            array( $this, 'listValuesCallback'), // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_values'
            ]
        );

        add_settings_field(
            'list_manager_api_key', // id
            _('List Manager API Key', 'cds-snc'), // title
            array( $this, 'listManagerApiKeyCallback'), // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_api_key'
            ]
        );

        add_settings_field(
            'list_manager_notify_services', // id
            _('List Manager Notify Services', 'cds-snc'), // title
            array( $this, 'listManagerNotifyServicesCallback'), // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_notify_services'
            ]
        );

        add_settings_field(
            'list_manager_service_id', // id
            _('List Manager Service Id', 'cds-snc'), // title
            array( $this, 'listManagerServiceIdCallback'), // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_service_id'
            ]
        );
    }

    public function listManagerSettingsSectionInfo()
    {
    }

    public function getObfuscatedOutputLabel($string, $labelId)
    {
        $startsWith = substr($string, 0, 4);
        $endsWith = substr($string, -4);


        printf(
            __('<span class="hidden_keys" id="%1$s">Current value: <span class="sr-only">Starts with </span>%2$s<span aria-hidden="true"> … </span><span class="sr-only"> and ends with</span>%3$s</span>', 'cds-snc'),
            $labelId,
            $startsWith,
            $endsWith
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
            padding-bottom: 3px;
            display: block;
            color: grey;
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