<?php

/**
 * Plugin Name: Must-use plugins
 * Description: Include must-use plugins
 *
 * @package cds-snc-base
 */

use CDS\Utils;

require_once(__DIR__ . '/../../vendor/autoload.php');

require WPMU_PLUGIN_DIR . '/wp-native-php-sessions/pantheon-sessions.php';
require WPMU_PLUGIN_DIR . '/cds-base/index.php';
require WPMU_PLUGIN_DIR . '/two-factor/two-factor.php';
require WPMU_PLUGIN_DIR . '/wps-hide-login/wps-hide-login.php';
require WPMU_PLUGIN_DIR . '/wp-bootstrap-blocks/wp-bootstrap-blocks.php';
require WPMU_PLUGIN_DIR . '/s3-uploads/s3-uploads.php';

if (!Utils::isWpEnv()) {
    require WPMU_PLUGIN_DIR . '/login-lockdown/loginlockdown.php';
}
