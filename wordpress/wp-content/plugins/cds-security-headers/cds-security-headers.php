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
    $csp_headers = [
        "base-uri" => [
            "'self'",
        ],
        "connect-src" => [
            "'self'",
            "https://www.google-analytics.com",
        ],
        "default-src" => [
            "'self'",
        ],
        "font-src" => [
            "'self'",
            "data:",
            "https://fonts.gstatic.com",
            "https://use.fontawesome.com",
            "https://www.canada.ca",
            "https://digital.canada.ca",
        ],
        "frame-src" => [
            "'self'",
            "https://docs.google.com",
            "https://digital.canada.ca",
            "https://www.youtube.com",
            "https://www.loom.com",
            "https://034gc-my.sharepoint.com",
        ],
        "img-src" => [
            "'self'",
            "data:",
            "https://canada.ca",
            "https://wet-boew.github.io",
            "https://www.canada.ca",
            "https://secure.gravatar.com",
            "2.gravatar.com",
            "0.gravatar.com",
            "https://digital.canada.ca",
        ],
        "manifest-src" => [
            "'self'",
        ],
        "media-src" => [
            "'self'",
        ],
        "object-src" => [
            "'self'",
            "https://digital.canada.ca",
        ],
        "script-src" => [
            "'self'",
            "'sha256-DdN0UNltr41cvBTgBr0owkshPbwM95WknOV9rvTA7pg='",
            "'sha256-MF5ZCDqcQxsjnFVq0T7A8bpEWUJiuO9Qx1MqSYvCwds='",
            "'sha256-8//zSBdstORCAlBMo1/Cig3gKc7QlPCh9QfWbRu0OjU='",
            "'sha256-5/P+Wb5Puz2VZQuyT0B/H3kuum7v7A2XDV17K95mm2Q='",
            "'sha256-Ll9Pj6gzPpETya7YXsYglTFBzjPg0sc23VG6sms7FKE='",
            "'sha256-9vpql/NLyCCe3HPEb2b/lcLKPbkRi48w2Lfn0AbTxsQ='",
            "'sha256-+zAcjG07bIcQUdOJ4VdpR6NeUqSj+ijz0iNFSRtHtFU='",
            "'sha256-w/MihaBU9WFQdzQiyd/HoTEHWRaWJyFmDE63TBablMI='",
            "'sha256-mlQdACKZOv0Ge2eMnCyUrDeDg6euHtWjTJoE256s0hM='",
            "https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js",
            "https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js",
            "https://www.canada.ca/etc/designs/canada/wet-boew/js/theme.min.js",
            "https://www.canada.ca/etc/designs/canada/wet-boew/js/i18n/en.min.js",
            "https://www.canada.ca/etc/designs/canada/wet-boew/js/i18n/fr.min.js",
            "https://www.googletagmanager.com/",
            "https://digital.canada.ca",
        ],
        "style-src" => [
            "'self'",
            "'unsafe-inline'",
            "https://use.fontawesome.com",
            "https://www.canada.ca",
            "https://digital.canada.ca",
            "https://fonts.googleapis.com",
        ],
        "worker-src" => [
            "'none'"
        ]
    ];

    $csp = "";

    foreach ($csp_headers as $src => $rules) {
        $csp .= $src . " ";
        foreach ($rules as $index => $rule) {
            $csp .= $rule . ($index + 1 < count($rules) ? " " : "; ");
        }
    }

    if (!is_admin()) {
        $headers['Content-Security-Policy'] = $csp;
    }
    $headers['strict-transport-security'] = 'max-age=31536000; includeSubDomains; preload';
    $headers['X-XSS-Protection'] = '1; mode=block';
    $headers['X-Frame-Options'] = 'SAMEORIGIN';
    $headers['X-Content-Type-Options'] = 'nosniff';

    return $headers;
}

add_filter('wp_headers', 'cds_security_headers');
