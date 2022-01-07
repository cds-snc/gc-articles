<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * This has been slightly modified (to read environment variables) for use in Docker.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
    function getenv_docker($env, $default)
    {
        if ($fileEnv = getenv($env . '_FILE')) {
            return rtrim(file_get_contents($fileEnv), "\r\n");
        } else {
            if (($val = getenv($env)) !== false) {
                return $val;
            } else {
                return $default;
            }
        }
    }

}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress'));
/** MySQL database username */
define('DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username'));
/** MySQL database password */
define('DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password'));
/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/f9cc35ebad82753e9c86de322ea5c76a9001c7e2/wp-admin/setup-config.php#L216-L230
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

/** MySQL hostname */
define('DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql'));
/** Database charset to use in creating database tables. */
define('DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8'));
/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', ''));
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', getenv_docker('WORDPRESS_AUTH_KEY', 'cfa5e9ad06fa2317fdd11d907fff48fb96e7affc'));
define('SECURE_AUTH_KEY', getenv_docker('WORDPRESS_SECURE_AUTH_KEY', 'efadecf1d6a5203d5bef993eb926cb3853261903'));
define('LOGGED_IN_KEY', getenv_docker('WORDPRESS_LOGGED_IN_KEY', '717ccee463621997a4b5abf21cab7cb6ee7ec6ce'));
define('NONCE_KEY', getenv_docker('WORDPRESS_NONCE_KEY', '7dce2fba7da21ef196096b90bf6ea822f542fe3a'));
define('AUTH_SALT', getenv_docker('WORDPRESS_AUTH_SALT', '2831f11e60007f1de35f9d7c152980bec807abfc'));
define('SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', '66e443484622346a80a8a0ed1c059a219cb12ce6'));
define('LOGGED_IN_SALT', getenv_docker('WORDPRESS_LOGGED_IN_SALT', 'ddd5ad2315d942b3ba53e9cd7203ef580741be49'));
define('NONCE_SALT', getenv_docker('WORDPRESS_NONCE_SALT', '37e2ee5dc8c485fb67f05c5c50e266dc5ae8a985'));
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */

define('WP_DEFAULT_THEME', getenv_docker('WP_DEFAULT_THEME', 'cds-default'));

define('WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', ''));
define('WP_DEBUG_DISPLAY', !!getenv_docker('WORDPRESS_DEBUG_DISPLAY', 0));
@ini_set('display_errors', WP_DEBUG_DISPLAY);
/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
    eval($configExtra);
}

/* Multisite */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', getenv_docker('DEFAULT_DOMAIN', 'localhost'));
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* Custom */
define('SCRIPT_DEBUG', getenv_docker('SCRIPT_DEBUG', false));

/* Config for S3 Uploads plugin */
define('S3_UPLOADS_BUCKET', getenv_docker('S3_UPLOADS_BUCKET', ''));
define('S3_UPLOADS_REGION', getenv_docker('S3_UPLOADS_REGION', 'ca-central-1'));
define('S3_UPLOADS_KEY', getenv_docker('S3_UPLOADS_KEY', ''));
define('S3_UPLOADS_SECRET', getenv_docker('S3_UPLOADS_SECRET', ''));
define('S3_UPLOADS_OBJECT_ACL', 'private');
define('S3_UPLOADS_BUCKET_URL', getenv_docker('S3_UPLOADS_BUCKET_URL', 'https://articles.cdssandbox.xyz'));

/* Config for C3 Cloudfront Clear Cache plugin */
define('AWS_ACCESS_KEY_ID', getenv_docker('C3_AWS_ACCESS_KEY_ID', ''));
define('AWS_SECRET_ACCESS_KEY', getenv_docker('C3_AWS_SECRET_ACCESS_KEY', ''));
define('C3_DISTRIBUTION_ID', getenv_docker('C3_DISTRIBUTION_ID', ''));

/* This is for WPML auto updates */
define('OTGS_DISABLE_AUTO_UPDATES', true);

/* Disable core updates */
define('WP_AUTO_UPDATE_CORE', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
