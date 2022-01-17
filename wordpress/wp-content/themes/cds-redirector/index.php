<?php

declare(strict_types=1);

use function CDS\Redirector\cds_get_theme_option;

function HTTPValue($stringValue) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $stringValue)) {
       $stringValue = "http://" . $stringValue;
    }
    return $stringValue;
 }

// Redirects all requests to the platform.cdssandbox.xyz.
// Primarily used to redirect preview links from WP Admin
$host = $_SERVER['HTTP_HOST'];

$redirectHost = cds_get_theme_option("redirect_url");
$homeUrl = home_url($wp->request);

$search = [$host, 'http://'];
$replace = [$redirectHost, 'https://'];

$redirectUrl = str_replace($search, $replace, $homeUrl);
echo HTTPValue($redirectUrl);

// header("Location: ${redirectUrl}");
