<?php

declare(strict_types=1);

use CDS\Utils;

use function CDS\Redirector\cds_get_theme_option;
use function CDS\Redirector\cds_get_active_language;

$lang = cds_get_active_language();
$redirectHost = cds_get_theme_option("redirect_url");
$pageName = sprintf("/%s?lang=%s", $wp->request, $lang);

if (isset($_GET['page_id']) && isset($_GET['preview'])) :
    // handles incoming "draft" links from wp table (list view)
    $pageName = sprintf('/preview?id=%s&lang=%s', intval($_GET['page_id']), $lang);
endif;

$isSelf = false;

if (
    $redirectHost && str_contains(
        $_SERVER['REQUEST_URI'],
        $redirectHost
    ) || isset($_GET['action']) == "edit" || isset($_GET['_wp-find-template'])
) {
    $isSelf = true;
}

$isAjaxRequest = false;

//IF HTTP_X_REQUESTED_WITH is equal to xmlhttprequest
if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0
) :
    //Set our $isAjaxRequest to true.
    $isAjaxRequest = true;
endif;

// don't redirect for ajax requests
if (!$isAjaxRequest && !$isSelf) :
    $redirectUrl = Utils::addHttp($redirectHost) . $pageName;
    header("Location: ${redirectUrl}");
endif;
