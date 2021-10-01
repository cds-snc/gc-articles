<?php

use CDS\Modules\Notify\NotifyClient;

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        $templateId = getenv('NOTIFY_GENERIC_TEMPLATE_ID') ?: '40454604-8702-4eeb-9b38-1ed3104fb960';
        $notifyClient = new NotifyClient();

        $notifyClient->sendMail(
            $to,
            $templateId,
            [
                "subject" => $subject,
                "message" => $message
            ],
        );

        return true;
    }
}
