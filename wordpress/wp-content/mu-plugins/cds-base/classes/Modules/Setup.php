<?php

declare(strict_types=1);

namespace CDS\Modules;

use CDS\Modules\Cleanup\PostsToArticles;
use CDS\Modules\Cleanup\SitesToCollections;
use CDS\Modules\Blocks\Blocks;
use CDS\Modules\Cleanup\AdminBar as CleanupAdminBar;
use CDS\Modules\Cleanup\AdminStyles as CleanupAdminStyles;
use CDS\Modules\Cleanup\Dashboard as CleanupDashboard;
use CDS\Modules\Cleanup\Login as CleanupLogin;
use CDS\Modules\Cleanup\Menus as CleanupMenus;
use CDS\Modules\Cleanup\Misc as CleanupMisc;
use CDS\Modules\Cleanup\Notices as CleanupNotices;
use CDS\Modules\Cleanup\Profile as CleanupProfile;
use CDS\Modules\Cleanup\Roles as CleanupRoles;
use CDS\Modules\Notify\NotifyClient;
use CDS\Modules\Notify\SendTemplateDashboardPanel;
use CDS\Modules\Notify\Setup as SetupNotify;
use CDS\Modules\Subscriptions\Setup as SetupSubscriptions;
use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Modules\TwoFactor\TwoFactor;
use CDS\Utils;

class Setup
{
    public function __construct()
    {
        $this->cleanup();
        $this->checkVersion();
        $this->setupTrackLogins();
        $this->setupNotifyTemplateSender();
        $this->setupBlocks();

        // @TODO: subscriptions not tested since refactor
        // $this->setupSubscriptions();
    }

    public function cleanup()
    {
        new SitesToCollections();
        new PostsToArticles();
        new CleanupRoles();
        new CleanupLogin();
        new CleanupMenus();
        new CleanupDashboard();
        new CleanupNotices();
        new CleanupAdminBar();
        new CleanupAdminStyles();
        new CleanupMisc();
        new CleanupProfile();
        new TwoFactor();
    }

    public function checkVersion()
    {
        $theme_data    = wp_get_theme();
        $theme_version = $theme_data["Version"];

        Utils::checkOptionCallback('theme_version', $theme_version, function () use ($theme_version) {
            $notifyClient = new NotifyClient();
            $notifyClient->sendMail(
                "tim.arney@cds-snc.ca",
                "377d0592-0039-4c04-b8c2-e302bab59d7c",
                ["version" => $theme_version],
                $ref = "container update"
            );
        });
    }

    public function setupTrackLogins()
    {
        $trackLogins = new TrackLogins();

        Utils::checkOptionCallback('cds_track_logins_installed', '1.0', function () use ($trackLogins) {
            $trackLogins->install();
        });
    }

    public function setupNotifyTemplateSender()
    {
        new SendTemplateDashboardPanel();
        new SetupNotify();
    }


    public function setupBlocks()
    {
        new Blocks();
    }

    public function setupSubscriptions()
    {
        new SetupSubscriptions();
    }
}
