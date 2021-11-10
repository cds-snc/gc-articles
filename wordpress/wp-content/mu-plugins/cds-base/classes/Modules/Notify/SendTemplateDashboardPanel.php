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
        $services = Utils::parseServiceIdsFromEnv($serviceIdData);
        
        // $services = Utils::parseServiceIdsFromEnv("MVP Updates~");
        // $services = Utils::parseServiceIdsFromEnv("MVP Updates~gc-articles-a7902fc7-37f0-419c-84c8-3ab499ee24c8-30569ea9-362b-41c4-a811-842ccf3db3dc");
        /*
        $services = Utils::parseServiceIdsFromEnv("MVP Updates~gc-articles-a7902fc7-37f0-419c-84c8-3ab499ee24c8-30569ea9-362b-41c4-a811-842ccf3db3dc,MVP Updates 2~,MVP Updates 3~gc-articles-a7902fc7-37f0-419c-84c8-3ab499ee24c8-30569ea9-362b-41c4-a811-842ccf3db3dc");
        $serviceIds = [];
        $str = "";

        foreach ($services as $key => $value) {
            if($value['name'] !== "" && $value['service_id'] !== "" && $value['api_key'] !== ""){
                
                $str.=$value['name'].'~'.$value['api_key'].",";
            }
        }

        print_r($services);

        if($str === ""){
            echo "<p> == Don't save == <p>";
        }else{
            echo "<p>== Save ==</p>";
            $str = rtrim($str, ",");
            echo $str;
        }
        */

        $serviceIds = [];
        foreach ($services as $key => $value) {
            array_push($serviceIds, $value['service_id']);
        }

        echo '<div id="notify-panel"></div>';
        $data = 'CDS.Notify.renderPanel({ "sendTemplateLink" :true , serviceId: "' . $serviceIds[0] . '"});';
        wp_add_inline_script('cds-snc-admin-js', $data, 'after');
    }
}
