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

        add_action('rest_api_init', [$instance, 'registerRestRoutes']);

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

    public function registerRestRoutes(): void
    {
        register_rest_route('list-manager-settings', '/list/save', [
            'methods' => 'POST',
            'callback' => [$this, 'saveListValues'],
            'permission_callback' => function () {
                return true;
                #return current_user_can('administrator');
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
