<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\EncryptedOption;

class NotifyApiSettings
{
    protected EncryptedOption $encryptedOption;

    private $NOTIFY_API_KEY;
    private $NOTIFY_GENERIC_TEMPLATE_ID;
    private $LIST_MANAGER_API_KEY;
    private $LIST_MANAGER_NOTIFY_SERVICES;
    private $LIST_MANAGER_SERVICE_ID;

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
        add_menu_page(
            'Notify API Settings', // page_title
            'Notify API Settings', // menu_title
            'manage_options', // capability
            'notify-api-settings', // menu_slug
            array( $this, 'notifyApiSettingsCreateAdminPage'), // function
            'dashicons-admin-generic', // icon_url
            99 // position
        );
    }

    public function notifyApiSettingsCreateAdminPage()
    {
        $this->NOTIFY_API_KEY = get_option('NOTIFY_API_KEY');
        $this->NOTIFY_GENERIC_TEMPLATE_ID = get_option('NOTIFY_GENERIC_TEMPLATE_ID');
        $this->LIST_MANAGER_API_KEY = get_option('LIST_MANAGER_API_KEY');
        $this->LIST_MANAGER_NOTIFY_SERVICES = get_option('LIST_MANAGER_NOTIFY_SERVICES');
        $this->LIST_MANAGER_SERVICE_ID = get_option('LIST_MANAGER_SERVICE_ID');
        ?>

        <div class="wrap">
            <h2>Notify API Settings</h2>
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
            'notify_api_settings_option_group', // option_group
            'NOTIFY_API_KEY',
            function($input) {
                if ($input == '') {
                    return get_option('NOTIFY_API_KEY');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'NOTIFY_GENERIC_TEMPLATE_ID',
            function($input) {
                if ($input == '') {
                    return get_option('NOTIFY_GENERIC_TEMPLATE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_API_KEY',
            function($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_API_KEY');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_NOTIFY_SERVICES',
            function($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_NOTIFY_SERVICES');
                }

                return sanitize_text_field($input);
            }
        );

        register_setting(
            'notify_api_settings_option_group', // option_group
            'LIST_MANAGER_SERVICE_ID',
            function($input) {
                if ($input == '') {
                    return get_option('LIST_MANAGER_SERVICE_ID');
                }

                return sanitize_text_field($input);
            }
        );

        add_settings_section(
            'notify_api_settings_setting_section', // id
            'Settings', // title
            array( $this, 'notifyApiSettingsSectionInfo'), // callback
            'notify-api-settings-admin' // page
        );

        add_settings_field(
            'notify_api_key', // id
            'NOTIFY_API_KEY', // title
            array( $this, 'notify_api_key_callback' ), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'notify_generic_template_id', // id
            'NOTIFY_GENERIC_TEMPLATE_ID', // title
            array( $this, 'notify_generic_template_id_callback' ), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'list_manager_api_key', // id
            'LIST_MANAGER_API_KEY', // title
            array( $this, 'list_manager_api_key_callback' ), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'list_manager_notify_services', // id
            'LIST_MANAGER_NOTIFY_SERVICES', // title
            array( $this, 'list_manager_notify_services_callback' ), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );

        add_settings_field(
            'list_manager_service_id', // id
            'LIST_MANAGER_SERVICE_ID', // title
            array( $this, 'list_manager_service_id_callback' ), // callback
            'notify-api-settings-admin', // page
            'notify_api_settings_setting_section' // section
        );
    }

    public function notifyApiSettingsSanitize($input)
    {
        if ($input != '') {
          return $input;
        }
    }

    public function sanitizeNotifyApiKey($input)
    {
        if ($input == '') {
          return get_option('NOTIFY_API_KEY');
        }

        return sanitize_text_field($input);
    }


    public function notifyApiSettingsSectionInfo()
    {
    }

    public function notify_api_key_callback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="NOTIFY_API_KEY" id="notify_api_key" value="">',
            $this->NOTIFY_API_KEY ? $this->stringify($this->NOTIFY_API_KEY) : ''
        );
    }

    public function stringify($string)
    {
        // if (is_string($string)) {
            return $this->truncate($this->obfuscate($string));
        // }

        //return $string;
    }

    public function truncate($string, $maxChars = 16)
    {
        $textLength = strlen($string);

        return substr_replace($string, '...', $maxChars/2, $textLength-$maxChars);
    }

    public function obfuscate($string, $replaceWith = 'X'): array|string|null
    {
        $chars = preg_quote('#/\!?@%^&*()_+=[]{}~"“”‘’\'`~<>,.|;:…—–-', '/');

        // u at the end is for unicode so it is multibyte safe
        // \s space, tab, newline, carriage return, vertical tab
        $string = preg_replace('/[^' . $chars . '\s]/u', $replaceWith, $string);

        return $string;
    }

    public function notify_generic_template_id_callback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="NOTIFY_GENERIC_TEMPLATE_ID" id="notify_generic_template_id" value="">',
            $this->NOTIFY_GENERIC_TEMPLATE_ID ? $this->NOTIFY_GENERIC_TEMPLATE_ID : ''
        );
    }

    public function list_manager_api_key_callback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_API_KEY" id="list_manager_api_key" value="">',
            $this->LIST_MANAGER_API_KEY ? $this->stringify($this->LIST_MANAGER_API_KEY) : ''
        );
    }

    public function list_manager_notify_services_callback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_NOTIFY_SERVICES" id="list_manager_notify_services" value="">',
            $this->LIST_MANAGER_NOTIFY_SERVICES ? $this->stringify($this->LIST_MANAGER_NOTIFY_SERVICES) : ''
        );
    }

    public function list_manager_service_id_callback()
    {
        printf(
            '<span class="hidden_keys">%s</span><input class="regular-text" type="text" name="LIST_MANAGER_SERVICE_ID" id="list_manager_service_id" value="">',
            $this->LIST_MANAGER_SERVICE_ID ? $this->stringify($this->LIST_MANAGER_SERVICE_ID) : ''
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