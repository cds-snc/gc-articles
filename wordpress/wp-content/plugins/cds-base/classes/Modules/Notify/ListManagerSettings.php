<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Utils as OptionUtils;
use CDS\Modules\Notify\Utils;
use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Modules\ListManager\ListManager;
use InvalidArgumentException;

class ListManagerSettings
{
    protected string $admin_page = 'cds_notify_send';
    public function __construct(EncryptedOption $encryptedOption)
    {
        $this->encryptedOption = $encryptedOption;
    }

    public static function register(EncryptedOption $encryptedOption)
    {
        $instance = new self($encryptedOption);

        add_action('admin_menu', [
            $instance,
            'listManagerSettingsAddPluginPage',
        ]);

        add_filter(
            'option_page_capability_list_manager_settings_option_group',
            function ($capability) {
                return 'manage_list_manager';
            },
        );

        ListManager::register();
    }

    public function listManagerSettingsAddPluginPage()
    {
        if (is_super_admin()) {
            add_submenu_page(
                $this->admin_page,
                __('Lists', 'cds-snc'),
                __('Lists', 'cds-snc'),
                'manage_list_manager',
                'lists',
                [$this, 'listManagerAppPage'],
            );
        }
    }

    public function listManagerAppPage()
    {
        $serviceId = Utils::extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));
        $services[] = ["name" => __("Your Lists", "cds-snc") , "service_id" => $serviceId];
        ?>
        <!-- app -->
        <div class="wrap">
            <h1><?php _e('List Manager', 'cds-snc'); ?></h1>
            <div id="list-manager-app" data-ids='<?php echo json_encode($services); ?>'>
            </div>
        </div>
        <?php
    }

    public function listManagerSettingsPageInit()
    {
        register_setting('list_manager_settings_option_group', 'list_values');

        register_setting(
            'list_manager_settings_option_group', // option_group
            'LIST_MANAGER_NOTIFY_SERVICES',
            function ($input) {
                return Utils::mergeListManagerServicesString(
                    sanitize_text_field($input),
                    get_option('LIST_MANAGER_NOTIFY_SERVICES'),
                );
            },
        );

        add_settings_section(
            'list_manager_settings_section', // id
            __('List manager', 'cds-snc'), // title
            [$this, 'listManagerSettingsSectionInfo'], // callback
            'list-manager-settings-admin', // page
        );

        add_settings_field(
            'list_values', // id
            __('List details', 'cds-snc'), // title
            [$this, 'listValuesCallback'], // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_values',
            ],
        );

        add_settings_field(
            'list_manager_notify_services', // id
            __('Notify Services', 'cds-snc'), // title
            [$this, 'listManagerNotifyServicesCallback'], // callback
            'list-manager-settings-admin', // page
            'list_manager_settings_section', // section
            [
                'label_for' => 'list_manager_notify_services',
            ],
        );
    }

    public function listManagerSettingsSectionInfo()
    {
    }

    public function getObfuscatedOutputLabel($string, $labelId, $print = true)
    {
        $startsWith = substr($string, 0, 4);
        $endsWith = substr($string, -4);

        $hint = sprintf(
            __(
                '<span class="hidden_keys" id="%1$s">Current value: <span class="sr-only">Starts with </span>%2$s<span aria-hidden="true"> … </span><span class="sr-only"> and ends with</span>%3$s</span>',
                'cds-snc',
            ),
            $labelId,
            $startsWith,
            $endsWith,
        );

        if ($print) {
            echo $hint;
            return;
        }

        return $hint;
    }

    public function listManagerNotifyServicesCallback()
    {
        $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
        $service_ids = Utils::deserializeServiceIds($serviceIdData);

        $values = [];
        $i = 0;
        foreach ($service_ids as $key => $value) {
            $hint = '';

            // get obfuscated `hint` label
            if (isset($value['api_key'])) {
                $hint = $this->getObfuscatedOutputLabel(
                    $value['api_key'],
                    'list_manager_notify_services_value',
                    false,
                );
            }

            array_push($values, [
                'id' => $i,
                'apiKey' => '', // don't re-display in form field
                'name' => $key,
                'hint' => $hint,
            ]);
            $i++;
        }

        if (count($values) < 1) {
            array_push($values, [
                'id' => '',
                'apiKey' => '',
                'name' => '',
                'hint' => '',
            ]);
        }

        $values = json_encode($values);

        printf(
            '<p class="desc">' .
                __(
                    "Add the <a href='%s'>sending service</a> for your subscription lists.",
                    'cds-snc',
                ) .
                '</p>',
            'https://notification.canada.ca/accounts',
        );

        printf(
            '<div id="notify-services-repeater-form" style="margin-top:20px;">notify services</div>',
        );
        $data = 'CDS.renderNotifyServicesRepeaterForm(' . $values . ');';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }

    public function listValuesCallback()
    {
        $values = [];

        if ($this->list_values) {
            $values = json_decode($this->list_values);
        }

        if (count($values) < 1) {
            array_push($values, [
                'id' => '',
                'label' => '',
                'type' => '',
            ]);
        }

        $values = json_encode($values);
        printf(
            "<p class='desc'>%s</p>",
            __('Add details for each of your subscription lists.', 'cds-snc'),
        );
        printf('<div id="list-values-repeater-form"></div>');
        $data = 'CDS.renderListValuesRepeaterForm(' . $values . ');';
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

    public function registerRestRoutes(): void
    {
        register_rest_route('list-manager', '/list/save', [
            'methods' => 'POST',
            'callback' => [$this, 'saveListValues'],
            'permission_callback' => function () {
                return current_user_can('administrator');
            }
        ]);
    }

    /**
     * Saves list value settinhs
     * @param  WP_REST_Request  $request
     *
     * @return WP_REST_Response
     */
    public function saveListValues($request)
    {
        try {
            OptionUtils::addOrUpdateOption("list_values", json_encode($request['list_values']));
            return ["success" => true];
        } catch (\Exception $e) {
            return ["success" => false, "error_message" => $e->getMessage()];
        }
    }
}
