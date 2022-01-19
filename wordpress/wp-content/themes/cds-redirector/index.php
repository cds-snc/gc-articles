<?php

declare(strict_types=1);

use CDS\Utils;

use function CDS\Redirector\cds_get_theme_option;
use function CDS\Redirector\cds_get_active_language;

$pageName = "/" . $wp->request;
$redirectHost = cds_get_theme_option("redirect_url");
$redirectUrl = Utils::addHttp($redirectHost) . $pageName . "?lang=" . cds_get_active_language();
header("Location: ${redirectUrl}");
