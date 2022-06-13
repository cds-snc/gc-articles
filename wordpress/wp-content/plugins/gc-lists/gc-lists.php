<?php

/**
 * Plugin Name:     GC Lists
 * Description:     Plugin for GC Lists
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     gc-lists
 * Domain Path:     /resources/languages
 * Version:         0.1.0
 *
 * @package         gc-lists
 */

namespace GCLists;

use Exception;

/**
 * Autoloader
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    throw new Exception('[GC Lists] Autoload does not exist. You probably need to run composer install');
}

/**
 * Basic Constants
 */
define('GC_LISTS_PLUGIN_FILE_PATH', __FILE__);

$plugin = GCLists::getInstance();
$plugin->setup();
