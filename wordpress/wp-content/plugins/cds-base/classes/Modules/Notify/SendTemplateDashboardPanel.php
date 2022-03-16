<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\Notify\NotifyTemplateSender;
use CDS\Modules\Notify\Utils;

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
        $service_id = Utils::extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));
        echo '<div id="notify-panel"></div>';
        $data = 'CDS.Notify.renderPanel({ "sendTemplateLink" :true , serviceId: "' . $service_id . '"});';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
