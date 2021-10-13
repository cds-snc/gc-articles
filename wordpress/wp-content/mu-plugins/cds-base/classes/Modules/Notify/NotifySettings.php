<?php

declare(strict_types=1);

namespace CDS\Modules\Notify;

use CDS\EncryptedOption;

class NotifySettings
{
    protected string $admin_page = 'cds_notify_send';
    protected EncryptedOption $encryptedOption;

    public static function register(EncryptedOption $encryptedOption)
    {
        $instance = new self($encryptedOption);

        add_action('admin_menu', [$instance, 'registerSubmenuPage']);
        add_action('admin_init', [$instance, 'registerSettings']);

        $encryptedOptions = [
            'NOTIFY_API_KEY',
            'LIST_MANAGER_NOTIFY_SERVICES'
        ];

        foreach ($encryptedOptions as $option) {
            add_filter("pre_update_option_{$option}", [$instance, 'encryptOption']);
            add_filter("option_{$option}", [$instance, 'decryptOption']);
        }
    }

    public function __construct(EncryptedOption $encryptedOption)
    {
        $this->encryptedOption = $encryptedOption;
    }

    public function encryptOption($value): string
    {
        return $this->encryptedOption->encryptString($value);
    }

    public function decryptOption($value): string
    {
        return $this->encryptedOption->decryptString($value);
    }

    public function registerSubmenuPage()
    {
        add_submenu_page(
            $this->admin_page,
            __('Settings'),
            __('Settings'),
            'activate_plugins',
            $this->admin_page . '_settings',
            [$this, 'renderSettings'],
        );
    }

    public function registerSettings(): void
    {
        register_setting('cds-settings-group', 'sender_type');
        register_setting('cds-settings-group', 'list_values');
    }

    public function renderSettings(): void
    {
        ?>

      <div class="wrap">
        <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
          <?php settings_errors(); ?>


        <form action='options.php' method='post'>
            <?php settings_fields('cds-settings-group'); ?>
            <?php do_settings_sections('cds-settings-group'); ?>
          <table class="form-table">
            <!-- Sender Type -->
            <tr valign="top">
              <th scope="row">
                  <?php _e('Sender Type', 'cds-snc'); ?>
              </th>
              <td>
                  <?php $current_val = esc_attr(
                      get_option('sender_type'),
                  ); ?>
                <select name="sender_type">
                    <?php
                    $label = __('List Manager', 'cds-snc');
                    $data  = [
                        'value' => 'list_manager',
                        'label' => $label,
                    ];
                    echo $this->renderSelectOption(
                        $data,
                        $current_val,
                    );

                    $label = __('WPForms', 'cds-snc');
                    $data  = [
                        'value' => 'wp_forms',
                        'label' => $label,
                    ];
                    echo $this->renderSelectOption(
                        $data,
                        $current_val,
                    );
                    ?>
                </select>
              </td>
            </tr>

            <!-- Sender Type -->
            <tr valign="top">
              <th scope="row">
                  <?php _e('List Values JSON', 'cds-snc'); ?>
              </th>
              <td>
                  <?php $val = esc_attr(get_option('list_values')); ?>
                <textarea name="list_values" rows="4" cols="50"><?php echo $val; ?></textarea>

                <p class="description" id="new-admin-email-description">
                    <?php _e('Format', 'cds-snc'); ?>:
                    <pre>[{"id":"123", "type":"email", "label":"my-list"}]</pre>
                </p>
              </td>
            </tr>
          </table>
            <?php submit_button(); ?>
        </form>
      </div>
        <?php
    }

    public function renderSelectOption($data, $current_val): string
    {
        $str = '<option ';
        $str .= 'value="' . $data['value'] . '"';

        if ($data['value'] == $current_val) {
            $str .= 'selected="selected"';
        }
        $str .= '>';
        $str .= $data['label'];
        $str .= '</option>';

        return $str;
    }
}