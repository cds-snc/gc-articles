<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\EncryptedOption\EncryptedOption;
use InvalidArgumentException;

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

        add_filter('option_page_capability_notify_api_settings_option_group', function ($capability) {
            return 'manage_notify';
        });

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
        add_submenu_page(
            $this->admin_page,
            __('Settings'),
            __('Settings'),
            'manage_notify',
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
            <h1><?php _e('Notify and List Manager Settings', 'cds-snc') ?></h1>
            <p></p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php" id="notify_settings_form" class="gc-form-wrapper">
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
            _('Notify', 'cds-snc'), // title
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
            _('List Manager API Key', 'cds-snc'), // title
            array( $this, 'listManagerApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_api_key'
            ]
        );

        add_settings_field(
            'list_manager_notify_services', // id
            _('List Manager Notify Services', 'cds-snc'), // title
            array( $this, 'listManagerNotifyServicesCallback'), // callback
            'notify-api-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_notify_services'
            ]
        );

        add_settings_field(
            'list_manager_service_id', // id
            _('List Manager Service Id', 'cds-snc'), // title
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

    public function getObfuscatedOutputLabel($string, $labelId, $print = true)
    {
        $startsWith = substr($string, 0, 4);
        $endsWith = substr($string, -4);

        $hint = sprintf(
            __('<span class="hidden_keys" id="%1$s">Current value: <span class="sr-only">Starts with </span>%2$s<span aria-hidden="true"> … </span><span class="sr-only"> and ends with</span>%3$s</span>', 'cds-snc'),
            $labelId,
            $startsWith,
            $endsWith
        );

        if ($print) {
            echo $hint;
            return;
        }

        return $hint;
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

    // @todo pull this from NotifyTemplateSender
    public function parseServiceIdsFromEnv($serviceIdData): array
    {
        if (!$serviceIdData) {
            throw new InvalidArgumentException('No service data');
        }

        try {
            $arr = explode(',', $serviceIdData);
            $service_ids = [];

            for ($i = 0; $i < count($arr); $i++) {
                $key_value = explode('~', $arr [$i]);
                $service_ids[$key_value[0]] = $key_value[1];
            }

            return $service_ids;
        } catch (Exception $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function listManagerNotifyServicesCallback()
    {
        try {
            $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
            $service_ids = $this->parseServiceIdsFromEnv($serviceIdData);
        } catch (InvalidArgumentException $e) {
            error_log($e->getMessage());
            $service_ids = [];
        }

        $values = [];
        $i = 0;
        foreach ($service_ids as $key => $value) {
            $hint = $this->getObfuscatedOutputLabel($value, 'list_manager_notify_services_value', false);
            array_push($values, ["id" => $i, "apiKey" => "", "name" => $key , "hint" => $hint]);
            $i++;
        }

        if (count($values) < 1) {
            array_push($values, ["id" => "", "apiKey" => "", "name" => "" , "hint" => ""]);
        }

        $values = json_encode($values);

        printf('<div id="notify-services-repeater-form" style="margin-top:20px;">notify services</div>');
        $data = 'CDS.renderNotifyServicesRepeaterForm(' . $values . ');';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
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
        printf('<div id="list-values-repeater-form"></div>');
        $data = 'CDS.renderListValuesRepeaterForm(' . $this->list_values . ');';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
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