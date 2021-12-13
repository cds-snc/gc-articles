<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class PrintStyles
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 100);
    }

    public function enqueueStyles()
    {
        // add media="print" specific stylesheet (the last parameter)
        wp_enqueue_style(
            'cds-base-style-print',
            plugin_dir_url(__FILE__) . 'css/print.css',
            [],
            BASE_PLUGIN_NAME_VERSION,
            "print"
        );
    }
}
