<?php

/**
 * Plugin Name:     CDS Security Headers
 * Description:     Manage security headers
 * Author:          Canadian Digital Service
 * Author URI:      https://digital.canada.ca
 * Text Domain:     cds-security-headers
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Cds_Security_Headers
 */

function cds_security_headers($headers)
{
    $csp = "base-uri 'self';";
    $csp .= "connect-src 'self' https://www.google-analytics.com;";
    $csp .= "default-src 'self';";
    $csp .= "font-src 'self' data: https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca;";
    $csp .= "frame-src 'self' https://docs.google.com;";
    $csp .= "img-src 'self' data: https://canada.ca https://wet-boew.github.io https://www.canada.ca https://secure.gravatar.com;";
    $csp .= "manifest-src 'self';";
    $csp .= "media-src 'self';";
    $csp .= "object-src 'none';";
    $csp .= "script-src 'self' 'sha256-DdN0UNltr41cvBTgBr0owkshPbwM95WknOV9rvTA7pg=' 'sha256-MF5ZCDqcQxsjnFVq0T7A8bpEWUJiuO9Qx1MqSYvCwds=' 'sha256-8//zSBdstORCAlBMo1/Cig3gKc7QlPCh9QfWbRu0OjU=' 'sha256-5/P+Wb5Puz2VZQuyT0B/H3kuum7v7A2XDV17K95mm2Q=' 'sha256-Ll9Pj6gzPpETya7YXsYglTFBzjPg0sc23VG6sms7FKE=' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/theme.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/i18n/en.min.js https://www.googletagmanager.com/;";
    $csp .= "style-src 'self' 'unsafe-inline' https://use.fontawesome.com https://www.canada.ca;";
    $csp .= "worker-src 'none';";

    $headers['X-XSS-Protection'] = '1; mode=block';
    $headers['X-Content-Type-Options'] = 'nosniff';
    $headers['X-Content-Security-Policy'] = $csp;

    return $headers;
}

if (! is_admin()) {
    add_filter('wp_headers', 'cds_security_headers');
}
