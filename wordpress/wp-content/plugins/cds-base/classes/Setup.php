<?php

declare(strict_types=1);

namespace CDS;

use CDS\Modules\Cache\Cache;
use CDS\Modules\Cleanup\Wpml;
use CDS\Modules\EncryptedOption\EncryptedOption;
use CDS\Modules\Blocks\Blocks;
use CDS\Modules\Cleanup\AdminBar as CleanupAdminBar;
use CDS\Modules\Cleanup\AdminStyles as CleanupAdminStyles;
use CDS\Modules\Cleanup\Dashboard as CleanupDashboard;
use CDS\Modules\Cleanup\Login as CleanupLogin;
use CDS\Modules\Cleanup\Menus as CleanupMenus;
use CDS\Modules\Cleanup\Misc as CleanupMisc;
use CDS\Modules\Cleanup\PostsToArticles;
use CDS\Modules\Cleanup\PrintStyles as CleanupPrintStyles;
use CDS\Modules\Cleanup\Profile as CleanupProfile;
use CDS\Modules\Cleanup\Roles as CleanupRoles;
use CDS\Modules\Cleanup\SitesToCollections;
use CDS\Modules\Cleanup\Media;
use CDS\Modules\Cli\GenerateEncryptionKey;
use CDS\Modules\Contact\Setup as ContactForm;
use CDS\Modules\Meta\Favicon;
use CDS\Modules\Meta\MetaTags;
use CDS\Modules\Notify\SendTemplateDashboardPanel;
use CDS\Modules\Notify\Setup as SetupNotify;
use CDS\Modules\Styles\Setup as Styles;
use CDS\Modules\Subscribe\Setup as SubscriptionForm;
use CDS\Modules\TrackLogins\TrackLogins;
use CDS\Modules\TwoFactor\TwoFactor;
use CDS\Modules\Users\Users;
use CDS\Modules\UserCollections\UserCollections;
use CDS\Modules\DBInsights\DBInsights;
use CDS\Modules\Releases\Releases;
use CDS\Modules\Site\SiteSettings;
use Exception;

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
        $this->setupNotifyTemplateSender();
        $this->setupBlocks();
        $this->setupMeta();
        $this->setupCli();

        TrackLogins::register();
        DBInsights::register();
        Releases::register();
        SiteSettings::register();
        Cache::register();
        \CDS\Modules\Wpml\Wpml::register();

        new SubscriptionForm();
        new ContactForm();
        new Styles();
        new UserCollections();
        new Users();
        new Media();

        Wpml::setup();
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
        new CleanupPrintStyles();
        new CleanupMisc();
        new CleanupProfile();
        new TwoFactor();
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
}
