<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

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
        $serviceId = get_option('LIST_MANAGER_SERVICE_ID');
        echo '<div id="notify-panel"></div>';
        $data = 'CDS.Notify.renderPanel({ "sendTemplateLink" :true , serviceId: "' . $serviceId . '"});';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
