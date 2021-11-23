<?php

/**
 * Plugin Name: Must-use plugins
 * Description: Include must-use plugins
 *
 * @package cds-snc-base
 */

use CDS\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

require WPMU_PLUGIN_DIR . '/wp-native-php-sessions/pantheon-sessions.php';
require WPMU_PLUGIN_DIR . '/wps-hide-login/wps-hide-login.php';
require WPMU_PLUGIN_DIR . '/wp-bootstrap-blocks/wp-bootstrap-blocks.php';
require WPMU_PLUGIN_DIR . '/s3-uploads/s3-uploads.php';
require WPMU_PLUGIN_DIR . '/disable-user-login/disable-user-login.php';


/* @TODO: need to not enable in test env
if (!Utils::isWpEnv()) {
    require WPMU_PLUGIN_DIR . '/login-lockdown/loginlockdown.php';
}
*/
