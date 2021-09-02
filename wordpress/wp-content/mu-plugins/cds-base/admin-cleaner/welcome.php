<?php

declare(strict_types=1);

function cds_dashboard_widget(): void
{
    wp_add_dashboard_widget('cds_welcome_widget', __('Welcome', 'cds'), 'cds_text_handler');
}

function cds_text_handler(): void
{
    _e('<a href=/wp-admin/post-new.php#">Create Article</a>', 'cds');
}

add_action('wp_dashboard_setup', 'cds_dashboard_widget');
