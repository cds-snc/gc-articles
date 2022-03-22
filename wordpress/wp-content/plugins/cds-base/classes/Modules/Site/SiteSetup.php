<?php

declare(strict_types=1);

namespace CDS\Modules\Site;

class SiteSetup
{
    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();
        $instance->addActions();
    }

    public function addActions()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        remove_action('welcome_panel', 'wp_welcome_panel');
        add_action('admin_notices', [$this, 'finishPanel']);
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
    }

    public function enqueue()
    {
        wp_enqueue_style(
            'cds-base-finish-setup-css',
            plugin_dir_url(__FILE__) . '/css/styles.css',
            null,
            '1.0.0',
        );
        wp_enqueue_script(
            'cds-base-finish-setup-js',
            plugin_dir_url(__FILE__) . '/js/index.js',
            null,
            '1.0.0',
            true,
        );

        wp_localize_script('cds-base-finish-setup-js', 'ADMIN_REST', [
            'ENDPOINT' => site_url(),
        ]);
    }

    public function registerRestRoutes(): void
    {
        register_rest_route('setup/v1', '/set-options', [
            'methods' => 'POST',
            'callback' => [$this, 'setOptions'],
            'permission_callback' => function () {
                return current_user_can('administrator');
            }
        ]);

        register_rest_route('setup/v1', '/dismiss-options', [
            'methods' => 'POST',
            'callback' => [$this, 'dismissOptions'],
            'permission_callback' => function () {
                return current_user_can('administrator');
            }
        ]);
    }

    public function setOptions()
    {
        try {
            $homeId = "";
            $maintenanceId = "";

            update_option("collection_mode", "maintenance");
            update_option("blog_public", 0);
            update_option("options_set", 1);
            update_option("blogdescription", get_bloginfo("name"));
            update_option("show_search", "on");
            update_option("show_breadcrumbs", "on");

            if (isset($_POST['homeId'])) {
                $homeId = intval($_POST['homeId']);
                update_option("page_on_front", $homeId);
            }

            if (isset($_POST['maintenanceId'])) {
                $maintenanceId = intval($_POST['maintenanceId']);
                update_option("collection_mode_maintenance_page", $maintenanceId);
            }

            return ["homeId" => $homeId, "maintenanceId" => $maintenanceId];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function dismissOptions()
    {
        update_option("options_set", 1);
        return "options_set";
    }

    public function finishPanel()
    {
        $optionsSet = get_option("options_set");
        // $optionsSet = 0; debug

        if (intVal($optionsSet) >= 1) {
            return;
        }

        if (\CDS\Utils::isWpEnv()) {
            return;
        }

        $screen = get_current_screen();

        if (!is_super_admin() || $screen->id != "dashboard") {
            return;
        } ?>
        <div class="wrap">
            <div class="finish-setup-content">
                <span id="finish-setup-dismiss" class="notice-dismiss"><span class="screen-reader-text"><?php _e("Dismiss", 'cds'); ?></span></span>
                <h3><?php _e('Finish Site Setup', 'cds-snc'); ?></h3>
                <p><?php _e("You’re almost done.  Let’s create some pages and default settings.", 'cds-snc'); ?></p>
                <div class="actions">
                    <a class="button" id="add-pages" href="#"><?php _e("Let's go!", 'cds'); ?></a>
                </div>
                <div class="status">
                    <div class="text-status hidden">Initializing</div>
                    <div class="loader-container hidden">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
