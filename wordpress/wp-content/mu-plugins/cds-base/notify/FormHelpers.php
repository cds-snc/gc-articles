<?php

class FormHelpers
{
    public function __construct()
    {
    }

    public static function render($data)
    {
      $action = get_home_url() . '/wp-json/wp-notify/v1/bulk'; ?>
      <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form id="email_sender" name="email_sender" method="post" action="<?php echo $action; ?>">
            <?php wp_nonce_field('wp_rest', '_wpnonce'); ?>
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
          <table class="form-table" role="presentation">
            <tbody>
            <!-- Service ID -->
            <tr>
              <th scope="row">
                <label for="list_id"><?php _e('Service ID',); ?></label>
              </th>
              <td>
                <select name="service_id" id="service_id">
                    <?php try {
                        self::render_service_id_options($data["service_ids"]);
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
                <label for="template_id"><?php _e('Template ID',); ?></label>
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
                        self::render_list_options($data["list_values"]);
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
                'submit',
                true,
                ['id' => 'cds-send-notify-template'],
            ); ?>
        </form>

        <div id="notify-panel"></div>
        <script>CDS.Notify.renderPanel({ 'sendTemplateLink': false });</script>
      </div>
        <?php
    }

    public static function render_service_id_options($data)
    {
        echo '<option value="">' . __('Select a service name') . '</option>';

        foreach ($data as $key => $value) {
            echo '<option value="' .
                trim($key) .
                '">' .
                trim($key) .
                '</option>';
        }
    }

    public static function render_list_options($data)
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
