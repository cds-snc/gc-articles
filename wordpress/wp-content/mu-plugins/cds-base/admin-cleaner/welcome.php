<?php

declare(strict_types=1);

function cds_dashboard_widget(): void
{
    wp_add_dashboard_widget(
        'cds_notify_widget',
        __('Notify', 'cds'),
        'cds_notify_panel_handler',
    );
}

function cds_notify_panel_handler(): void
{
    echo '<div id="notify-panel"></div><script>CDS.Notify.renderPanel({ "sendTemplateLink" :true});</script>';
}

add_action('wp_dashboard_setup', 'cds_dashboard_widget');
