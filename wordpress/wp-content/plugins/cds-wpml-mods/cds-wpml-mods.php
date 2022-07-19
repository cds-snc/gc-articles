<?php

/**
 * Plugin Name:     Cds Wpml Mods
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     cds-wpml-mods
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Cds_Wpml_Mods
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
define('GC_LISTS_PLUGIN_FILE_PATH', __FILE__);
define('GC_LISTS_PLUGIN_BASE_PATH', __DIR__);

$plugin = Wpml::getInstance();
$plugin->setup();
