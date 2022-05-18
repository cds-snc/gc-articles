<?php

declare(strict_types=1);

use CDS\Utils;

use function CDS\Redirector\cds_get_theme_option;
use function CDS\Redirector\cds_get_active_language;

$lang = cds_get_active_language();
$redirectHost = cds_get_theme_option("redirect_url");
$pageName = sprintf("/%s?lang=%s", $wp->request, $lang);

if (isset($_GET['preview'])) {
    $previewID = 0;

    if (isset($_GET['preview_id'])) {
        // for pages/articles that have already been published
        $previewID = intval($_GET['preview_id']);
    } else if (isset($_GET['p'])) {
        // for pages/articles that are still in draft
        $previewID = intval($_GET['p']);
    }

    // handles incoming "draft" links from wp table (list view)
    $pageName = sprintf('/preview?id=%s&lang=%s', $previewID, $lang);
}

if (
    $redirectHost && str_contains(
        $_SERVER['REQUEST_URI'],
        $redirectHost
    ) || isset($_GET['action']) == "edit" || isset($_GET['_wp-find-template'])
) {
    exit();
}

//IF HTTP_X_REQUESTED_WITH is equal to xmlhttprequest
if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0
) {
    //Set our $isAjaxRequest to true.
    exit();
}

if (!$redirectHost) {
    $link = site_url() . "/wp-admin/admin.php?page=theme-settings";
    wp_die("You have the Redirector theme enabled, but have not <a href='${link}'>configured a redirect.</a>");
}

$redirectUrl = Utils::addHttp($redirectHost) . $pageName;
header("Location: ${redirectUrl}");
