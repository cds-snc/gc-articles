<?php

use CDS\Modules\Notify\NotifyClient;

if (!function_exists('wp_mail')) {
    function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        $templateId = getenv('NOTIFY_GENERIC_TEMPLATE_ID') ?: '40454604-8702-4eeb-9b38-1ed3104fb960';
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
            error_log("Huzzah " . $e->getMessage());

            wp_die("There was an error sending the email");
            add_action('admin_notices', function() {
                return '<div class="notice notice-success is-dismissible">
                                <p class="notice-sent">There was an error</p>
                        </div>';
            });

            return new WP_Error(
                'email_failed',
                __( 'The email could not be sent. Possible reason: your host may have disabled the function.' )
            );
        }
        return true;
    }
}
