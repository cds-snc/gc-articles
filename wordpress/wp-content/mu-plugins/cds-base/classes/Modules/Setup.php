<?php

declare(strict_types=1);

namespace CDS\Modules;

use CDS\Modules\Cleanup\Login as CleanupLogin;
use CDS\Modules\Cleanup\Roles as CleanupRoles;
use CDS\Modules\Notify\NotifyClient;
use CDS\Modules\Notify\SendTemplateDashboardPanel;
use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Utils;

class Setup
{
    public function __construct()
    {
        $this->cleanup();
        $this->checkVersion();
        $this->setupTrackLogins();
        $this->setupNotifyTemplateSender();
    }

    public function cleanup()
    {
        new CleanupRoles();
        new CleanupLogin();
    }

    public function checkVersion()
    {
        $theme_data    = wp_get_theme();
        $theme_version = $theme_data["Version"];
        
        Utils::check_option_callback('theme_version', $theme_version, function () use ($theme_version) {
            $notifyClient = new NotifyClient();
            $notifyClient->sendMail("tim.arney@cds-snc.ca", "377d0592-0039-4c04-b8c2-e302bab59d7c",
                ["version" => $theme_version], $ref = "container update");
        });
    }

    public function setupTrackLogins()
    {
        $trackLogins = new TrackLogins();

        Utils::check_option_callback('cds_track_logins_installed', '1.0', function () use ($trackLogins) {
            $trackLogins->install();
        });
    }

    public function setupNotifyTemplateSender()
    {
        new SendTemplateDashboardPanel();
    }
}