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
            "https://www.canada.ca",
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
            "'sha256-ABrhY42pNp3SuJ409k660Qtar8nZFnJwBQcaSLLUjIU='",
            "'sha256-zwGmIUR+Z6gWKbwoJ2Z3yGxI/XLETLqDqCRIV0qt/WA='",
            "'sha256-6WL6Jxg2/EipusFpkd5k1ol5j3rpBwfbvBDKZIH7QD8='",
            "'sha256-BUMkJtRvJVotCsx075P4LSHzPixjll9ZDEFyTTfoj68='",
            "'sha256-hFDl3ajsROIuKTvuDF/uwvsGKvFY9HVIeBVHlJVR+wk='",
            "'sha256-Rf/qXfv+sUnbqOUcCkGKI4IusL57qJhlDliA10xgPf0='",
            "'sha256-j+ODwJ5nIx+5rP22hawfyKaS3s4+pqBxtAykBO8TMxY='",
            "'sha256-2ZxOnwq8l3yWDkQCRWr9uuVgLhPObXI7Bk+DPOtSFtQ='",
            "'sha256-KJCGOHRsVij6C6PgmbzvB9N576LT8dcX/1cOVUOkYCY='",
            "'sha256-ZPHm47rv3mCrMjFvsuJhn0u2fe+tFutdZKRxxDlkeXY='",
            "'sha256-cYkhsrU0jLWGd/MEswBsLeIZbUi9Vqqd1FQ8udFT8TA='",
            "'sha256-xCQw7jgqf9C199SYgDR9N+w0HzrxBbWZViu/LcycsQ4='",
            "'sha256-lv4FHujZYNOnPJSkL5xmARyAiD7zMnuMrB0mU2kNJRM='",
            "'sha256-7HIGaQcQMcMjdkS65CxkOQHHUayIG6iukms15vdtNms='",
            "'sha256-67eyUTlddoTZciPS7I334rf/verOaeaIMcY8QUwqV8I='",
            "'sha256-xaR/y3UVa5H2DSzsDCewTCwStKccNcghTcH3caE7/ok='",
            "'sha256-zR0p2KmH+27/29a/Au4gPdgseccjj6bQ1s//IxzxJW4='",
            "'sha256-2SmtX8FleooXSbJX4JL6SeaaNY70u0nxCQvR/Gg2EHA='",
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
