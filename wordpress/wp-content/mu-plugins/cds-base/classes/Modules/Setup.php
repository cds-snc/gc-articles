<?php

namespace CDS\Modules;

require_once __DIR__ . '/../../vendor/autoload.php';

use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Utils;

class Setup
{
    public function __construct()
    {
        $this->setup_track_logins();
    }

    public function setup_track_logins()
    {
        $trackLogins = new TrackLogins();

        Utils::check_option_callback('cds_track_logins_installed', '1.0', function() use ($trackLogins) {
            $trackLogins->install();
        });
    }
}