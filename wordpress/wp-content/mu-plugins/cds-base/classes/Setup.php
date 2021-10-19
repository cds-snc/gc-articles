<?php

declare(strict_types=1);

namespace CDS;

use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Modules\Blocks\Blocks;
use CDS\Modules\Cleanup\AdminBar as CleanupAdminBar;
use CDS\Modules\Cleanup\AdminStyles as CleanupAdminStyles;
use CDS\Modules\Cleanup\Dashboard as CleanupDashboard;
use CDS\Modules\Cleanup\Login as CleanupLogin;
use CDS\Modules\Cleanup\Menus as CleanupMenus;
use CDS\Modules\Cleanup\Misc as CleanupMisc;
use CDS\Modules\Cleanup\PostsToArticles;
use CDS\Modules\Cleanup\Profile as CleanupProfile;
use CDS\Modules\Cleanup\Roles as CleanupRoles;
use CDS\Modules\Cleanup\SitesToCollections;
use CDS\Modules\Cli\GenerateEncryptionKey;
use CDS\Modules\Contact\Setup as ContactForm;
use CDS\Modules\FlashMessage\FlashMessage;
use CDS\Modules\Meta\Favicon;
use CDS\Modules\Meta\MetaTags;
use CDS\Modules\Notify\NotifyClient;
use CDS\Modules\Notify\SendTemplateDashboardPanel;
use CDS\Modules\Notify\Setup as SetupNotify;
use CDS\Modules\Styles\Setup as Styles;
use CDS\Modules\Subscribe\Setup as SubscriptionForm;
use CDS\Modules\Subscriptions\Setup as SetupSubscriptions;
use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Modules\TwoFactor\TwoFactor;
use Exception;
use CDS\Modules\UserCollections\UserCollections;

class Setup
{
    public EncryptedOption $encryptedOption;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->encryptedOption = $this->getEncryptedOption();

        $this->cleanup();
        $this->checkVersion();
        $this->setupTrackLogins();
        $this->setupNotifyTemplateSender();
        $this->setupBlocks();
        $this->setupMeta();
        $this->setupCli();
        new SubscriptionForm();
        new ContactForm();
        new FlashMessage();
        new Styles();
        new UserCollections();

        // @TODO: subscriptions not tested since refactor
        // $this->setupSubscriptions();
    }

    /**
     * @return EncryptedOption
     * @throws Exception
     */
    protected function getEncryptedOption(): EncryptedOption
    {
        $encryptionKey = $this->getEncryptionKey();

        /**
         * Setup Encrypted Options
         */
        if (!$encryptionKey || $encryptionKey == '') {
            throw new Exception('No encryption key set in the environment');
        }

        return new EncryptedOption($encryptionKey);
    }

    /**
     * Get Encryption Key either from CONSTANTS or environment
     *
     * @return bool|array|string
     */
    protected function getEncryptionKey(): bool|array|string
    {
        /**
         * If we're in a wp-env dev, test, or cli environment, return a hard-coded key. This works because the
         * environment variable is not available in the wp-env environment, but is available in our docker cli.
         */
        if ((Utils::isWpEnv()) || (defined('WP_CLI') && WP_CLI)) {
            return getenv('ENCRYPTION_KEY') ?: "base64:cELNoBToBqa9NtubmEoo+Tsh3nz2gAVz79eGrwzg9ZE=";
        }

        return getenv('ENCRYPTION_KEY');
    }

    public function cleanup()
    {
        new SitesToCollections();
        new PostsToArticles();
        new CleanupRoles();
        new CleanupLogin();
        new CleanupMenus();
        new CleanupDashboard();
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

    /**
     * @throws Exception
     */
    public function setupNotifyTemplateSender()
    {
        new SendTemplateDashboardPanel();
        new SetupNotify($this->encryptedOption);
    }

    public function setupBlocks()
    {
        new Blocks();
    }

    public function setupMeta()
    {
        new Favicon();
        new MetaTags();
    }

    public function setupCli()
    {
        GenerateEncryptionKey::register($this->encryptedOption);
    }

    public function setupSubscriptions()
    {
        new SetupSubscriptions();
    }
}
