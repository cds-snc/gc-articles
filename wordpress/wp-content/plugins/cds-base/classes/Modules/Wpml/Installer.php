<?php

declare(strict_types=1);

namespace CDS\Modules\Wpml;

use WPML\Collect\Support\Collection;
use WPML\Media\Setup\Endpoint\PrepareSetup;
use WPML\Media\Translate\Endpoint\DuplicateFeaturedImages;
use WPML\Media\Translate\Endpoint\FinishMediaTranslation;
use WPML\Media\Translate\Endpoint\PrepareForTranslation;
use WPML\Media\Translate\Endpoint\TranslateExistingMedia;
use WPML\Setup\Endpoint\AddressStep;
use WPML\Setup\Endpoint\FinishStep;
use WPML\Setup\Endpoint\LicenseStep;
use WPML\Setup\Endpoint\SetOriginalLanguage;
use WPML\Setup\Endpoint\SetSecondaryLanguages;
use WPML\Setup\Endpoint\SetSupport;
use WPML\TM\ATE\Sitekey\Endpoint;
use WPML\TranslationMode\Endpoint\SetTranslateEverything;

class Installer
{
    /**
     * @var array|array[]
     */
    protected array $steps;

    public function __construct()
    {
        /**
         * These were collected from payloads sent to admin-ajax.php during WPML setup. These may be subject to change
         * in the future, and we should monitor for breaking changes when upgrading WPML.
         */
        $this->steps = [
            [
                "endpoint" => SetOriginalLanguage::class,
                "data" => ["languageCode" => "en"]
            ],
            [
                "endpoint" => SetSecondaryLanguages::class,
                "data" => ["languages" => ["fr"]]
            ],

            [
                "endpoint" => AddressStep::class,
                "data" => [
                    "mode" => "directory",
                    "domains" => [
                        "fr" => ""
                    ],
                    "gotUrlRewrite" => true,
                    "siteUrl" => $this->getSiteUrl(),
                    "validDomainFormats" => [
                        "fr" => true
                    ],
                    "duplicateDomains" => [
                        "fr" => false
                    ],
                    "invalidUrls" => [],
                    "ignoreInvalidUrls" => false,
                    "isCompleted" => true
                ]
            ],
            [
                "endpoint" => LicenseStep::class,
                "data" => ["siteKey" => $this->getWpmlKey()]
            ],
            [
                "endpoint" => SetTranslateEverything::class,
                "data" => [
                    "method" => "manual",
                    "translateEverything" => false
                ]
            ],
            [
                "endpoint" => SetSupport::class,
                "data" => ["agree" => "0","repo" => "wpml"]
            ],
            [
                "endpoint" => FinishStep::class,
                "data" => ["finished" => true]
            ],
            [
                "endpoint" => Endpoint::class,
                "data" => []
            ],
            [
                "endpoint" => PrepareSetup::class,
                "data" => ["key" => ""]
            ],
            [
                "endpoint" => PrepareForTranslation::class,
                "data" => ["key" => ""]
            ],
            [
                "endpoint" => TranslateExistingMedia::class,
                "data" => []
            ],
            [
                "endpoint" => DuplicateFeaturedImages::class,
                "data" => ["remaining" => []]
            ],
            [
                "endpoint" => FinishMediaTranslation::class,
                "data" => []
            ],
        ];
    }

    /**
     * Instantiate the class and get things rolling
     */
    public static function register()
    {
        $instance = new self();

        add_action('wp_initialize_site', [$instance, 'onInit']);
    }

    /**
     * Get the WPML SiteKey from the environment
     * @return bool|array|string
     */
    protected function getWpmlKey(): bool|array|string
    {
        return getenv("WPML_SITE_KEY");
    }

    /**
     * @return mixed
     */
    protected function getSiteUrl(): mixed
    {
        return get_site_url();
    }

    /**
     * Setup WPML on a new site
     * @param $newSite
     */
    public function onInit($newSite)
    {
        switch_to_blog($newSite->id);

        // If this doesn't exist, WPML doesn't exist
        if (function_exists('icl_sitepress_activate')) {
            // Activates the plugin and installs database tables for new site
            icl_sitepress_activate();

            // Loop over and execute each of the setup steps
            foreach ($this->steps as $step) {
                $this->runAction($step['endpoint'], new Collection($step['data']));
            }
        }

        restore_current_blog();
    }

    /**
     * Instantiate the given IHandler class, and run it with the provided payload
     * @param $class
     * @param  Collection $data
     */
    protected function runAction($class, Collection $data)
    {
        $instance = new $class();
        $instance->run($data);
    }
}