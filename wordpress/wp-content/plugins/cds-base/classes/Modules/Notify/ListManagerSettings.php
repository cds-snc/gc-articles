<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Utils as OptionUtils;
use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Modules\ListManager\ListManager;
use WP_REST_Request;
use WP_REST_Response;

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
        if ($notifyApiKey = get_option('NOTIFY_API_KEY')) {
            $serviceId = Utils::extractServiceIdFromApiKey($notifyApiKey);

            $services[] = ["name" => __("Your Lists", "cds-snc"), "service_id" => $serviceId];

            ?>
              <!-- app -->
              <div class="wrap">
                <h1><?php _e('GC Lists', 'cds-snc'); ?></h1>
                <div id="list-manager-app" data-ids='<?php echo json_encode($services); ?>'>
                </div>
              </div>
            <?php
        } else {
            ?>
              <!-- app -->
              <div class="wrap">
                <h1><?php _e('GC Lists', 'cds-snc'); ?></h1>
                <p>
                  <?php
                    echo sprintf(
                        __('You must configure your <a href="%s">Notify API Key</a>', 'cds-snc'),
                        admin_url("options-general.php?page=notify-settings")
                    );
                    ?>
                </p>

              </div>
            <?php
        }
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
    public function saveListValues(WP_REST_Request $request): WP_REST_Response
    {
        try {
            OptionUtils::addOrUpdateOption("list_values", json_encode($request['list_values']));
            return new WP_REST_Response(["success" => true]);
        } catch (\Exception $e) {
            return new WP_REST_Response(["success" => false, "error_message" => $e->getMessage()]);
        }
    }
}
