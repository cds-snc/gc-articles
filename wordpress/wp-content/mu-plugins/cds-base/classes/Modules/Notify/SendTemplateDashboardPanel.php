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
        wp_add_dashboard_widget(
            'cds_notify_widget',
            __('Notify', 'cds'),
            [$this, 'notifyPanelHandler'],
        );
    }

    public function notifyPanelHandler(): void
    {
        $sender = new NotifyTemplateSender();
        $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
        $services = $sender->parseServiceIdsFromEnv($serviceIdData);

        $serviceIds = [];
        foreach ($services as $key => $value) {
            array_push($serviceIds, $value['service_id']);
        }
        
        echo '<div id="notify-panel"></div>';
        $data = 'CDS.Notify.renderPanel({ "sendTemplateLink" :true , serviceId: "' . $serviceIds[0] . '"});';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
