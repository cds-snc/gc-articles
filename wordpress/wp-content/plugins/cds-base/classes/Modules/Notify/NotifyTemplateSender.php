<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use WP_REST_Response;
use CDS\Modules\Notify\Utils;
use CDS\Modules\Notify\ListManagerUserProfile;

class NotifyTemplateSender
{
    public static function register()
    {
        $listManagerProfile = new ListManagerUserProfile();
        $listManagerProfile->register();
    }
}
