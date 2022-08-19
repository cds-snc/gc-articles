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
        add_action('admin_enqueue_scripts', [$instance, 'enqueue']);

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

    /* Copy to Clipboard */
    public static function templateText($key)
    {
        switch ($key) {
            case 'alertText':
                return  __('Copied to clipboard', 'cds-snc');
            ;
                break;
            case 'subject':
                return "((subject))";
                break;
            case 'messageTemplate':
                return "((message))\n
You may unsubscribe by clicking this link:\n
((unsubscribe_link))\n
Vous pouvez vous desabonner en cliquant ce lien:\n
((unsubscribe_link))";
                break;
            case 'subscribeTemplate':
                return "Thank you for subscribing to updates about ((name)).\n
To verify your email and activate your subscription, use this link: ((confirm_link))\n
If you did not subscribe, please ignore this message.";
                break;
            default:
                return "";
        }
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-copy-to-clipboard-js', plugin_dir_url(__FILE__) . './src/copy-to-clipboard.js', [], "1.0.0", true);

        wp_localize_script('cds-copy-to-clipboard-js', 'CDS_VARS', [
            'alertText' => self::templateText('alertText'),
            'subject' => self::templateText('subject'),
            'messageTemplate' => self::templateText('messageTemplate'),
            'subscribeTemplate' => self::templateText('subscribeTemplate')
        ]);
    }

    public function notifyApiSettingsAddPluginPage()
    {
        add_submenu_page(
            "gc-lists_messages",
            __('Set up GC Lists', 'cds-snc'), // page_title
            __('Setup', 'cds-snc'), // menu_title
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
            __('Set up GC Lists', 'cds-snc'), // title
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
            'notify_generic_template_id', // id
            __('Message template ID', 'cds-snc'), // title
            array( $this, 'notifyGenericTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_generic_template_id'
            ]
        );

        add_settings_field(
            'notify_subscribe_template', // id
            __('Subscribe template ID', 'cds-snc'), // title
            array( $this, 'notifySubscribeTemplateIdCallback'), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section', // section
            [
                'label_for' => 'notify_subscribe_template'
            ]
        );
    }

    public function notifyApiSettingsSectionInfo()
    {
        printf('<p>%s</p>', __('To start creating mailing lists and sending messages, you must connect a GC Notify service to GC Lists.', 'cds-snc'));
        printf(
            '<p>%s <a href="%s" target="_blank">%s</a> %s.</p>',
            __('You can generate the required information below once you have a live', 'cds-snc'),
            __('https://notification.canada.ca/', 'cds-snc'),
            __('GC Notify', 'cds-snc'),
            __('service', 'cds-snc'),
        );
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
        printf(
            '<div class="role-desc description">
            <details>
                <summary>%s</summary>
                <ul>
                    <li><a href="#" target="_blank">%s</a></li>
                    <li>%s</li>
                    <li>%s</li>
                    <li>%s</li>
                </ul>
                <p class="description">%s</p>
                <code>example_notify_key-26785a09-ab16-4eb0-8407-a37497a57506-3d844edf-8d35-48ac-975b-e847b4f122b0</code>
            </details>',
            __('How to get an API key', 'cds-snc'),
            __('Sign in to GC Notify', 'cds-snc'),
            __('Go to the API integration page', 'cds-snc'),
            __('Select API keys', 'cds-snc'),
            __('Select Create an API key', 'cds-snc'),
            __('The API key should follow this format:', 'cds-snc')
        );
    }

    public function notifyGenericTemplateIdCallback()
    {
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_GENERIC_TEMPLATE_ID" id="notify_generic_template_id" value="%s">',
            $this->NOTIFY_GENERIC_TEMPLATE_ID ? esc_attr($this->NOTIFY_GENERIC_TEMPLATE_ID) : ''
        );

        printf(
            '<p class="description smaller">%s</p>',
            __('The messages you send will follow this format. It includes a way for people to unsubscribe from the mailing list.', 'cds-snc'),
        );

        printf(
            '<div class="role-desc description">
        <details>
            <summary>%s</summary>
            <ul>
                <li><a href="#" target="_blank">%s</a></li>
                <li>%s</li>
                <li>%s</li>
                <li>%s</li>
            </ul>
            <p class="description">%s</p>
            <p><strong>%s</strong></p>
            <code>%s</code>
            <button type="button" class="button button-secondary button--copy-to-clipboard" id="subject">%s</button>
            <p><strong>%s</strong></p>
            <code>
            %s
            </code>
            <button type="button" class="button button-secondary button--copy-to-clipboard" id="messageTemplate">%s</button>
        </details>',
            __('How to get an message template ID', 'cds-snc'),
            __('Sign in to GC Notify', 'cds-snc'),
            __('Select Create a template', 'cds-snc'),
            __('Choose the type of message', 'cds-snc'),
            __('Pick a name for your list', 'cds-snc'),
            __('Enter below text into the corresponding fields in GC Notify:', 'cds-snc'),
            __('Subject line of the email:', 'cds-snc'),
            self::templateText('subject'),
            __('Copy to clipboard', 'cds-snc'),
            __('Message:', 'cds-snc'),
            nl2br(self::templateText('messageTemplate')),
            __('Copy to clipboard', 'cds-snc')
        );
    }

    public function notifySubscribeTemplateIdCallback()
    {
        printf(
            '<input class="regular-text" type="text" name="NOTIFY_SUBSCRIBE_TEMPLATE_ID" id="notify_subscribe_template_id" value="%s">',
            $this->NOTIFY_SUBSCRIBE_TEMPLATE_ID ? esc_attr($this->NOTIFY_SUBSCRIBE_TEMPLATE_ID) : ''
        );

        printf(
            '<p class="description smaller">%s</p>',
            __('People will receive this message to verify their email address after subscribing to your mailing list.', 'cds-snc'),
        );

        printf(
            '<div class="role-desc description">
        <details>
            <summary>%s</summary>
            <ul>
                <li><a href="#" target="_blank">%s</a></li>
                <li>%s</li>
                <li>%s</li>
                <li>%s</li>
            </ul>
            <p class="description">%s</p>
            <p><strong>%s</strong></p>
            <code>%s</code>
            <button type="button" class="button button-secondary button--copy-to-clipboard" id="subject">%s</button>
            <p><strong>%s</strong></p>
            <code>
            %s
            </code>
            <button type="button" class="button button-secondary button--copy-to-clipboard" id="subscribeTemplate">%s</button>
        </details>',
            __('How to get an message template ID', 'cds-snc'),
            __('Sign in to GC Notify', 'cds-snc'),
            __('Select Create a template', 'cds-snc'),
            __('Choose the type of message', 'cds-snc'),
            __('Pick a name for your list', 'cds-snc'),
            __('Enter below text into the corresponding fields in GC Notify:', 'cds-snc'),
            __('Subject line of the email:', 'cds-snc'),
            self::templateText('subject'),
            __('Copy to clipboard', 'cds-snc'),
            __('Message:', 'cds-snc'),
            nl2br(self::templateText('subscribeTemplate')),
            __('Copy to clipboard', 'cds-snc')
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