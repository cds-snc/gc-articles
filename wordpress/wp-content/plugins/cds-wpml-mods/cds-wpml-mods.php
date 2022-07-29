<?php

/**
 * Plugin Name:     CDS WPML Mods
 * Plugin URI:      https://github.com/cds-snc/gc-articles
 * Description:     Adds a translation panel to the Gutenberg block editor, as well as other modifications to WPML needed by GC Articles.
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     cds-wpml-mods
 * Domain Path:     /resources/languages
 * Version:         1.0.0
 *
 * @package         CDS_WPML_Mods
 */

namespace CDS\Wpml;

use Exception;

/**
 * Autoloader
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    throw new Exception('[CDS Wpml Mods] Autoload does not exist. You probably need to run composer install');
}

/**
 * Basic Constants
 */
define('CDS_WPML_PLUGIN_FILE_PATH', __FILE__);
define('CDS_WPML_PLUGIN_BASE_PATH', __DIR__);

$plugin = Wpml::getInstance();
$plugin->setup();
