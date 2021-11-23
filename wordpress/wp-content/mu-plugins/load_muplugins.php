<?php

/**
 * Plugin Name: Must-use plugins
 * Description: Include must-use plugins
 *
 * @package cds-snc-base
 */

use CDS\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';


/* @TODO: need to not enable in test env
if (!Utils::isWpEnv()) {
    require WPMU_PLUGIN_DIR . '/login-lockdown/loginlockdown.php';
}
*/
