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
      <div class="notice notice-success is-dismissible">
        <p><?php _e('Sent', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function noticeTemplateFail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('Template ID is required', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function noticeListIdFail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('List ID failed to parse', 'cds-snc'); ?></p>
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
        <p><?php _e($message, 'cds-snc'); ?></p>
      </div>
        <?php
    }
}