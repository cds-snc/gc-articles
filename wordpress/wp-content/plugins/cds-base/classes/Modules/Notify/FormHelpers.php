<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\Modules\Notify\NotifyTemplateSender;
use CDS\Modules\Notify\Utils;
use Exception;

class FormHelpers
{
    public static function noApiKey()
    {
        ?>
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p>
              <?php
                echo sprintf(
                    __('You must configure your <a href="%s">Notify API Key</a>', 'cds-snc'),
                    admin_url("options-general.php?page=notify-settings")
                );
                ?>
            </p>
        <?php
    }

    public static function render($data)
    {
        $action = site_url() . '/wp-json/wp-notify/v1/bulk'; ?>
      <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form id="notify_template_sender_form" name="notify_template_sender_form" method="post" action="<?php echo $action; ?>">
            <?php wp_nonce_field('wp_rest', '_wpnonce'); ?>
          <input type="hidden" name="page" value="<?php echo $_REQUEST[
              'page'
            ]; ?>" />
          <input type="hidden" name="service_id" value="<?php echo Utils::extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY')); ?>" />
          <table class="form-table" role="presentation">
            <tbody>
            <!-- Template ID -->
            <tr>
              <th scope="row">
                <label for="template_id"><?php _e('Template ID', 'cds-snc'); ?></label>
              </th>
              <td>
                <input type="text" class="regular-text" name="template_id" value="" />
                <div class="role-desc description">
                  <details>
                  <?php
                    printf('<summary>%s</summary><code>ex4mp1e0-d248-4661-a3d6-0647167e3720</code>', __('See example template ID format.', 'cds-snc')); ?>
                  </details>
                </div>
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
                        self::renderListOptions($data['list_values']);
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
            $service_id = Utils::extractServiceIdFromApiKey(
                get_option('NOTIFY_API_KEY'),
            );
          $data =
              'CDS.Notify.renderPanel({ "sendTemplateLink" :false , serviceId: "' .
              $service_id .
              '"});';
          wp_add_inline_script('cds-snc-admin-js', $data, 'after');?>
      </div>
        <?php
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
