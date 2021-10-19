<?php

use CDS\Modules\FlashMessage\FlashMessage;
use CDS\Modules\Notify\NotifyClient;

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        $templateId = get_option('NOTIFY_GENERIC_TEMPLATE_ID') ?: '40454604-8702-4eeb-9b38-1ed3104fb960';
        $notifyClient = new NotifyClient();

        try {
            $notifyClient->sendMail(
                $to,
                $templateId,
                [
                    "subject" => $subject,
                    "message" => $message
                ],
            );
        } catch (\Alphagov\Notifications\Exception\NotifyException $e) {
            error_log("[Notify] " . $e->getMessage());

            FlashMessage::queueFlashMessage(
                "There was an error sending the email :" . $e->getMessage(),
                'error'
            );

            return false;
        }
        return true;
    }
}
