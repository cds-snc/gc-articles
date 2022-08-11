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
    private string $NOTIFY_SUBSCRIBE_TEMPLATE_ID;

    public function __construct(EncryptedOption $encryptedOption)
    {
        $this->encryptedOption = $encryptedOption;
    }

    public static function register(EncryptedOption $encryptedOption)
    {
        $instance = new self($encryptedOption);

        add_action('admin_menu', [$instance, 'notifyApiSettingsAddPluginPage'], 100);
        add_action('admin_init', [$instance, 'notifyApiSettingsPageInit']);
        add_action('admin_head', [$instance, 'addStyles']);

        add_filter('option_page_capability_notify_api_settings_option_group', function ($capability) {
            return 'manage_notify';
        });

        $encryptedOptions = [
            'NOTIFY_API_KEY',
        ];

        if (!\CDS\Utils::isWpEnv()) {
            foreach ($encryptedOptions as $option) {
                add_filter("pre_update_option_{$option}", [$instance, 'encryptOption']);
                add_filter("option_{$option}", [$instance, 'decryptOption']);
            }
        }
    }

    public function notifyApiSettingsAddPluginPage()
    {
        add_submenu_page(
            "gc-lists_messages",
            __('API Settings', 'cds-snc'), // page_title
            __('API Settings', 'cds-snc'), // menu_title
            'manage_notify',
            "settings",
            [ $this, 'notifyApiSettingsCreateAdminPage' ] // function
        );
    }

    public function notifyApiSettingsCreateAdminPage()
    {
        $this->NOTIFY_API_KEY = get_option('NOTIFY_API_KEY') ?: '';
        $this->NOTIFY_GENERIC_TEMPLATE_ID = get_option('NOTIFY_GENERIC_TEMPLATE_ID') ?: '';
        $this->NOTIFY_SUBSCRIBE_TEMPLATE_ID = get_option('NOTIFY_SUBSCRIBE_TEMPLATE_ID') ?: '';
        ?>

        <div class="wrap">
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
            'NOTIFY_SUBSCRIBE_TEMPLATE_ID',
            function ($input) {
                if ($input == '') {
                    return get_option('NOTIFY_SUBSCRIBE_TEMPLATE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        add_settings_section(
            'notify_api_settings_setting_section', // id
            __('Notify API Settings', 'cds-snc'), // title
            array( $this, 'notifyApiSettingsSectionInfo'), // callback
            'notify-api-settings-admin' // page
        );

        add_settings_field(
            'notify_api_key', // id
            __('API key', 'cds-snc'), // title
            array( $this, 'notifyApiKeyCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_api_key'
            ]
        );

        add_settings_field(
            'notify_subscribe_template', // id
            __('Subscribe template id', 'cds-snc'), // title
            array( $this, 'notifySubscribeTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_subscribe_template'
            ]
        );

        add_settings_field(
            'notify_generic_template_id', // id
            __('Email integration template ID', 'cds-snc'), // title
            array( $this, 'notifyGenericTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_generic_template_id'
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

        $link = __('Read <a href="https://documentation.notification.canada.ca/en/keys.html" target="_blank">API keys</a> for details.', 'cds-snc');
        printf('<div class="role-desc description">
        <details>
            <summary>%s. (%s)</summary>
            <code>example_notify_key-26785a09-ab16-4eb0-8407-a37497a57506-3d844edf-8d35-48ac-975b-e847b4f122b0</code>
        </details>
        <p class="description">%s</p>', __('Enter your API Key', 'cds-snc'), __('See example key format.', 'cds-snc'), $link);
    }

    public function notifyGenericTemplateIdCallback()
    {
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_GENERIC_TEMPLATE_ID" id="notify_generic_template_id" value="%s">',
            $this->NOTIFY_GENERIC_TEMPLATE_ID ? esc_attr($this->NOTIFY_GENERIC_TEMPLATE_ID) : ''
        );

        $link = __('Read the <a href="https://notification.canada.ca/format" target="_blank">Email formatting guide</a> for details.', 'cds-snc');
        printf('<div class="role-desc description">
        <details>
            <summary>%s. (%s)</summary>
            <code>ex4mp1e0-d248-4661-a3d6-0647167e3720</code>
        </details>
        <p class="description">%s</p>', __('Enter your generic Email Template ID', 'cds-snc'), __('See example template ID format.', 'cds-snc'), $link);
    }

    public function notifySubscribeTemplateIdCallback()
    {
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_SUBSCRIBE_TEMPLATE_ID" id="notify_subscribe_template_id" value="%s">',
            $this->NOTIFY_SUBSCRIBE_TEMPLATE_ID ? esc_attr($this->NOTIFY_SUBSCRIBE_TEMPLATE_ID) : ''
        );

        printf('<div class="role-desc description">
        <details>
            <summary>(%s)</summary>
            <code>ex4mp1e0-d248-4661-a3d6-0647167e3720</code>
        </details>
        ', __('See example template ID format.', 'cds-snc'));
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