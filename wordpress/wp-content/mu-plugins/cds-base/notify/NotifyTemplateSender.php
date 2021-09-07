<?php

declare(strict_types=1);

use GuzzleHttp\Client;

require_once __DIR__ . '/NotifySettings.php';

add_action('admin_menu', ['NotifyTemplateSender', 'add_menu']);

add_action('rest_api_init', ['NotifyTemplateSender', 'setup_endpoints']);

const MINUTE_IN_SECONDS = 60;

class NotifyTemplateSender
{
    public static $admin_page = 'cds_notify_send';

    public function __construct()
    {
        global $status, $page;
    }

    public static function add_menu(): void
    {
        add_menu_page(
            __('Send Notify Template', "cds-snc"),
            __('Notify', "cds-snc"),
            'level_0',
            self::$admin_page,
            ['NotifyTemplateSender', 'render_form'],
            'dashicons-email'
        );

        NotifySettings::add_menu();
    }

    public static function notice_success(): void
    {
        ?>
      <div class="notice notice-success is-dismissible">
        <p><?php _e('Sent', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function notice_template_fail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('Template ID is required', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function notice_list_id_fail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('List ID failed to parse', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function notice_fail(): void
    {
        ?>
      <div class="notice notice-error is-dismissible">
        <p><?php _e('Failed to send', 'cds-snc'); ?></p>
      </div>
        <?php
    }

    public static function render_form(): void
    {
        $action = get_home_url() . '/wp-json/wp-notify/v1/bulk';

        if (isset($_GET['status'])) {
            switch ($_GET['status']) {
                case 200:
                    add_action('admin_notices', [
                        self::class,
                        'notice_success',
                    ]);
                    break;
                case 400:
                    // no template ID
                    add_action('admin_notices', [
                        self::class,
                        'notice_template_fail',
                    ]);
                    break;
                case 418:
                    // invalid list Ids
                    add_action('admin_notices', [
                        self::class,
                        'notice_list_id_fail',
                    ]);
                    break;
                case 500:
                    add_action('admin_notices', [self::class, 'notice_fail']);
                    break;
                default:
                    echo '';
            }

            do_action('admin_notices');
        }
        ?>

      <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form id="email_sender" name="email_sender" method="post" action="<?php echo $action; ?>">
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
          <table class="form-table" role="presentation">
            <tbody>
            <!-- Template ID -->
            <tr>
              <th scope="row"><label for="template_id"><?php _e(
                          'Template ID',
                      ); ?></label></th>
              <td><input type="text" class="regular-text" name="template_id" value="" /></td>
            </tr>
            <!-- List ID -->
            <tr>
              <th scope="row"><label for="list_id"><?php _e(
                          'List ID',
                      ); ?></label></th>
              <td>
                <select name="list_id" id="list_id">
                    <?php try {
                        $data = get_option('list_values');
                        $data = preg_replace(
                            '/[ \t]+/',
                            ' ',
                            preg_replace('/[\r\n]+/', "\n", $data),
                        );
                        $data = json_decode($data, true);

                        if (empty($data)) {
                            throw new ErrorException(
                                'unable to parse data',
                            );
                        }

                        echo '<option value="">' .
                            __('Select a list') .
                            '</option>';

                        foreach ($data as &$value) {
                            echo '<option value="' .
                                $value['id'] .
                                '-' .
                                $value['type'] .
                                '">' .
                                $value['label'] .
                                '</option>';
                        }
                    } catch (Exception $e) {
                        echo '<option value="">' .
                            __('No lists found', 'cds-snc') .
                            '</option>';
                    } ?>

                </select>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Submit -->
            <?php
            submit_button(__('Send template to list', 'cds-snc'), "primary", "submit", true, ['id' => "cds-send-notify-template"]); ?>
        </form>
        </table>
      </div>
        <?php
    }

    public static function process_send($data): void
    {
       $base_redirect =
            get_admin_url() . 'admin.php?page=' . self::$admin_page;

        try {
            $template_id = $data['template_id'];

            if (empty($template_id)) {
                wp_redirect($base_redirect . '&status=400');
                exit();
            }

            $parts = explode('-', $data['list_id']);

            if (!is_array($parts) || count($parts) !== 2) {
                wp_redirect($base_redirect . '&status=418');
                exit();
            }

            $list_id = $parts[0];
            $list_type = $parts[1];

            $sender_type = get_option('sender_type');

            switch ($sender_type) {
                case 'list_manager':
                    $result = self::send(
                        $template_id,
                        $list_id,
                        $list_type,
                        'WP Bulk send',
                    );

            }

            // @todo ensure the status we're retuning is correct
            wp_redirect($base_redirect . '&status=200');
            exit();
        } catch (Exception $e) {
            //
            $datetime = new DateTime();

            $datetime->setTimezone(new DateTimeZone('UTC'));

            $logEntry = $datetime->format('Y/m/d H:i:s') . ' ' . $e;

            // log to default error_log destination
            error_log($logEntry);

            wp_redirect($base_redirect . '&status=500');
            exit();
        }
    }

    public static function send($template_id, $list_id, $template_type, $ref)
    {
        $client = new Client([]);
        $endpoint = $_ENV['LIST_MANAGER_ENDPOINT'];

        return $client->request('POST', $endpoint . '/send', [
            'json' => [
                'template_id' => $template_id,
                'list_id' => $list_id,
                'template_type' => $template_type,
                'job_name' => $ref,
            ],
        ]);
    }

    public static function setup_endpoints(): void
    {
        register_rest_route('wp-notify/v1', '/bulk', [
            'methods' => 'POST',
            'callback' => [self::class, 'process_send'],
        ]);
    }
}
