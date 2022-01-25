<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\Notify\NotifyTemplateSender;
use Exception;

class FormHelpers
{
    public static function render($data)
    {
        $action = site_url() . '/wp-json/wp-notify/v1/bulk'; ?>
      <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form id="notify_template_sender_form" name="notify_template_sender_form" method="post" action="<?php echo $action; ?>">
            <?php wp_nonce_field('wp_rest', '_wpnonce'); ?>
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
          <table class="form-table" role="presentation">
            <tbody>
            <!-- Service ID -->
            <tr>
              <th scope="row">
                <label for="list_id"><?php _e('Service ID', 'cds-snc'); ?></label>
              </th>
              <td>
                <select name="service_id" id="service_id">
                    <?php try {
                        self::renderServiceIdOptions($data["service_ids"]);
                    } catch (Exception $e) {
                        echo '<option value="">' .
                             __('No service ids found', 'cds-snc') .
                             '</option>';
                    } ?>
                </select>
              </td>
            </tr>
            <!-- End Service ID -->
            <!-- Template ID -->
            <tr>
              <th scope="row">
                <label for="template_id"><?php _e('Template ID', 'cds-snc'); ?></label>
              </th>
              <td>
                <input type="text" class="regular-text" name="template_id" value="" />
              </td>
            </tr>
            <!-- End Template ID -->
            <!-- List ID -->
            <tr>
              <th scope="row">
                <label for="list_id"><?php _e('List ID', 'cds-snc'); ?></label>
              </th>
              <td>
                <select name="list_id" id="list_id">
                    <?php try {
                        self::renderListOptions($data["list_values"]);
                    } catch (Exception $e) {
                        echo '<option value="">' .
                             __('No lists found', 'cds-snc') .
                             '</option>';
                    } ?>
                </select>
              </td>
            </tr>
            <!-- End List ID -->
            </tbody>
          </table>
          <!-- Submit -->
            <?php submit_button(
                __('Send template to list', 'cds-snc'),
                'primary',
                'notify-send-template',
                true,
                ['id' => 'notify-send-template'],
            ); ?>
        </form>

        <div id="notify-panel"></div>

          <?php
            $serviceIdData = get_option('LIST_MANAGER_NOTIFY_SERVICES');
            $services = Utils::deserializeServiceIds($serviceIdData);

            $serviceIds = [];
            foreach ($services as $key => $value) {
                array_push($serviceIds, $value['service_id']);
            }

            $defaultServiceId = $serviceIds[0] ?? '';

            $data = 'CDS.Notify.renderPanel({ "sendTemplateLink" :false , serviceId: "' . $defaultServiceId . '"});';
            wp_add_inline_script('cds-snc-admin-js', $data, 'after');
            ?>
      </div>
        <?php
    }

    public static function renderServiceIdOptions($data)
    {
        echo '<option value="">' . __('Select a service name') . '</option>';

        foreach ($data as $key => $value) {
            echo '<option value="' .
                 trim($value['service_id']) .
                 '">' .
                 trim($key) .
                 '</option>';
        }
    }

    public static function renderListOptions($data)
    {
        echo '<option value="">' . __('Select a list') . '</option>';

        foreach ($data as &$value) {
            echo '<option value="' .
                 $value['id'] .
                 '~' .
                 $value['type'] .
                 '">' .
                 $value['label'] .
                 '</option>';
        }
    }
}