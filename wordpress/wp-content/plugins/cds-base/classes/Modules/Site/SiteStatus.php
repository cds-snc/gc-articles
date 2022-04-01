<?php

declare(strict_types=1);

namespace CDS\Modules\Site;

class SiteStatus
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
        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function dashboardWidget(): void
    {
        if (!is_super_admin()) {
            return;
        }

        wp_add_dashboard_widget(
            'cds_status_widget',
            __('Site Status', 'cds'),
            [$this, 'siteStatusPanelHandler'],
        );
    }

    public function renderSiteAvailability()
    {
        $label = __('Site Availability', 'cds-snc');
        $status = __('Live', 'cds-snc');
        if (get_option("collection_mode") === "maintenance") {
            $status =  __('Maintenance', 'cds-snc');
        }

        return sprintf("<tr><td>%s</td><td>%s</td></tr>", $label, $status);
    }

    public function renderSearchEngineVisibility()
    {
        $label = __('Search Engine Visibility', 'cds-snc');
        $status = __('On', 'cds-snc');
        if (intVal(get_option("blog_public")) === 0) {
            $status =  __('Off', 'cds-snc');
        }

        return sprintf("<tr><td>%s</td><td>%s</td></tr>", $label, $status);
    }

    public function siteStatusPanelHandler(): void
    {
        $instance = new self();

        $updateText = sprintf(
            __('Update your <a href="%s">site settings</a>.', 'cds-snc'),
            esc_url('options-general.php?page=collection-settings')
        );

        echo '<div id="site-status-panel">';
        echo '<table class="wp-list-table widefat">';
        echo $instance->renderSiteAvailability();
        echo $instance->renderSearchEngineVisibility();
        echo '</table>';
        echo '<p>' . $updateText . '</p>';
        echo '</div>';
    }
}
