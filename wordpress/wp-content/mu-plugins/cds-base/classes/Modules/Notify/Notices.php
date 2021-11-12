<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

class Notices
{
    public static function handleNotice($status): void
    {

        switch ($_GET['status']) {
            case 200:
                add_action('admin_notices', [
                    self::class,
                    'noticeSuccess',
                ]);
                break;
            case 400:
                // no template ID
                add_action('admin_notices', [
                    self::class,
                    'noticeTemplateFail',
                ]);
                break;
            case 418:
                // invalid list Ids
                add_action('admin_notices', [
                    self::class,
                    'noticeListIdFail',
                ]);
                break;
            case 500:
                add_action('admin_notices', [self::class, 'noticeFail']);
                break;
            default:
                echo '';
        }

        do_action('admin_notices');
    }

    public static function noticeSuccess(): void
    {
        ?>  
        <?php
        $viewLink = "";
        if (isset($_GET['serviceId'])) {
            $sId = sanitize_text_field($_GET['serviceId']);
            $link = sprintf("https://notification.canada.ca/services/%s/notifications/email", $sId);
            $linkText = __("<a href='%s'>View</a> sending logs in GG Notify", "cds-snc");
            $viewLink = sprintf($linkText, $link);
        }
        ?>
      <div class="notice notice-success is-dismissible">
        <p class="notice-sent"><?php _e('Sent.', 'cds-snc');?><?php echo '&nbsp;&nbsp;' . $viewLink; ?></p>
      </div>
        <?php
    }

    public static function noticeTemplateFail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p class="notice-template-id"><?php _e('Template ID is required', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function noticeListIdFail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p class="notice-list-parse-failed"><?php _e('List ID failed to parse', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function noticeFail(): void
    {
        $message = get_transient('api_response');
        delete_transient('api_response');
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('Failed to send', 'cds-snc'); ?></p>
        <p class="notice-error"><?php _e($message, 'cds-snc'); ?></p>
      </div>
        <?php
    }
}