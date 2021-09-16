<?php

namespace CDS\Modules;

require_once __DIR__ . '/../../vendor/autoload.php';

use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Utils;
use CDS\Modules\Notify\NotifyClient;

class Setup
{
    public function __construct()
    {
        $this->check_version();
        $this->setup_track_logins();
    }

    public function check_version()
    {
        $theme_data =  wp_get_theme();
        $theme_version = $theme_data["Version"];
        Utils::check_option_callback('theme_version', $theme_version, function() use ($theme_version) {
            $notifyClient = new NotifyClient();
            $notifyClient->sendMail("tim.arney@cds-snc.ca", "91732dfb-740b-45c2-aee9-8114ae39f2e1", ["version" => $theme_version], $ref = "container update");
        });
    }

    public function setup_track_logins()
    {
        $trackLogins = new TrackLogins();

        Utils::check_option_callback('cds_track_logins_installed', '1.0', function() use ($trackLogins) {
            $trackLogins->install();
        });
    }
}