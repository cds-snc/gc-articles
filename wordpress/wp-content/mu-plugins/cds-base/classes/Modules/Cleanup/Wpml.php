<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Wpml
{
    public static function setup()
    {
        add_action( 'setup_theme', function() {
            Utils::addOrUpdateOption('wpml_language_switcher_template_objects', []);
        }, 10, 1 );
    }
}