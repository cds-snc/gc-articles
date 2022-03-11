<?php

namespace CDS\Modules\Forms;

use CDS\Modules\Forms\RequestSite\Setup as SetupRequestSiteForm;

class Setup
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        new SetupRequestSiteForm();

        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue()
    {
        wp_enqueue_script('cds-blocks-js', plugin_dir_url(__FILE__) . '/js/handler.js', ['jquery'], "1.0.0", true);
    }
}
