<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Dashboard
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'removeDashboardMeta']);
    }

    public function removeDashboardMeta(): void
    {
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_primary', 'dashboard', 'normal');
        remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        remove_meta_box('task_dashboard', 'dashboard', 'normal');

        /* plugins */
        remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
        remove_meta_box('wp_mail_smtp_reports_widget_lite', 'dashboard', 'normal');
    }
}