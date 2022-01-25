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

// don't redirect for ajax requests
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
    $redirectUrl = Utils::addHttp($redirectHost) . $pageName;
    header("Location: ${redirectUrl}");
}