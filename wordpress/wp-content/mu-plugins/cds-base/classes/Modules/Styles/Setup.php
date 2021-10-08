<?php

declare(strict_types=1);

namespace CDS\Modules\Styles;

class Setup
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue()
    {
        wp_enqueue_style('cds-base-css', plugin_dir_url(__FILE__) . 'template/css/styles.css', null, "1.0.0");
    }
}
