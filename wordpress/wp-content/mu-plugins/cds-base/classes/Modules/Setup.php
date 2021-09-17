<?php

namespace CDS\Modules;

use CDS\Modules\Cleanup\Login as CleanupLogin;
use CDS\Modules\Cleanup\Roles as CleanupRoles;
use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Utils;
use CDS\Modules\Notify\NotifyClient;

class Setup
{
    public function __construct()
    {
        $this->cleanup();
        $this->check_version();
        $this->setup_track_logins();
    }

    public function check_version()
    {
        $theme_data =  wp_get_theme();
        $theme_version = $theme_data["Version"];
        Utils::check_option_callback('theme_version', $theme_version, function() use ($theme_version) {
            $notifyClient = new NotifyClient();
            $notifyClient->sendMail("tim.arney@cds-snc.ca", "377d0592-0039-4c04-b8c2-e302bab59d7c", ["version" => $theme_version], $ref = "container update");
        });
    }

    public function setup_track_logins()
    {
        $trackLogins = new TrackLogins();

        Utils::check_option_callback('cds_track_logins_installed', '1.0', function() use ($trackLogins) {
            $trackLogins->install();
        });
    }

    public function cleanup()
    {
        new CleanupRoles();
        new CleanupLogin();
    }
}