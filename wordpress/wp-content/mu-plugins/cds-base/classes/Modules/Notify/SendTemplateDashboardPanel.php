<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\Notify\NotifyTemplateSender;

class SendTemplateDashboardPanel
{
    public function __construct()
    {
        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function dashboardWidget(): void
    {
        if (!current_user_can('list_manager_bulk_send')) {
            return;
        }

        wp_add_dashboard_widget(
            'cds_notify_widget',
            __('Notify', 'cds'),
            [$this,'notifyPanelHandler']
        );
    }

    public function notifyPanelHandler(): void
    {
        $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
        $serviceIds = [];

        $services = Utils::deserializeServiceIds($serviceIdData);

        foreach ($services as $key => $value) {
            array_push($serviceIds, $value['service_id']);
        }

        // catch and add empty id
        if (empty($serviceIds)) {
            array_push($serviceIds, '');
        }

        echo '<div id="notify-panel"></div>';
        $data =
            'CDS.Notify.renderPanel({ "sendTemplateLink" :true , serviceId: "' .
            $serviceIds[0] .
            '"});';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
