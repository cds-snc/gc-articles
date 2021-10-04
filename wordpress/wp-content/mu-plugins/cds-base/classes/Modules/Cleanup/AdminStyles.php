<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class AdminStyles
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles'], 99);
    }

    public function enqueueStyles()
    {
        // add stylesheet to the wp admin
        wp_enqueue_style(
            'cds-base-style-admin',
            plugin_dir_url(__FILE__) . 'css/admin.css',
            [],
            BASE_PLUGIN_NAME_VERSION,
        );
    }
}
