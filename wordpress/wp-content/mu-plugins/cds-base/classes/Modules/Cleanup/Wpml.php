<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Wpml
{
    public static function setup()
    {
        /*
         * We need to set this option to an empty array. This is supposed to be an array of directories that WPML will
         * scan looking for language switchers, but it has problems with s3: prefixed URLs. We don't use these
         * language switchers anyway, so just kill it.
         */
        add_action('setup_theme', function () {
            Utils::addOrUpdateOption('wpml_language_switcher_template_objects', []);
        }, 10, 1);
    }
}
