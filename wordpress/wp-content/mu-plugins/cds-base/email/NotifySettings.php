<?php

declare(strict_types=1);

class NotifySettings extends NotifyTemplateSender
{
    public static $admin_page = 'cds_notify_send';

    public function __construct()
    {
        global $status, $page;
    }

    public static function add_menu(): void
    {
        add_submenu_page(
            parent::$admin_page,
            __('Settings'),
            __('Settings'),
            'activate_plugins',
            self::$admin_page . '_settings',
            ['NotifySettings', 'render_settings'],
        );

        add_action('admin_init', ['NotifySettings', 'register_settings']);
    }

    public static function register_settings(): void
    {
        register_setting('cds-settings-group', 'sender_type');
        register_setting('cds-settings-group', 'list_values');
    }

    public static function render_select_option($data, $current_val): string
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

    public static function render_settings(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action='options.php' method='post'>
                <?php settings_fields('cds-settings-group'); ?>
                <?php do_settings_sections('cds-settings-group'); ?>
                <table class="form-table">
                    <!-- Sender Type -->
                    <tr valign="top">
                        <th scope="row"><?php _e(
                            'Sender Type',
                            'cds-snc',
                        ); ?></th>
                        <td>
                            <?php $current_val = esc_attr(
                                get_option('sender_type'),
                            ); ?>
                            <select name="sender_type">
                                <?php
                                $label = __('List Manager', 'cds-snc');
                                $data = [
                                    'value' => 'list_manager',
                                    'label' => $label,
                                ];
                                echo self::render_select_option(
                                    $data,
                                    $current_val,
                                );

                                $label = __('WPForms', 'cds-snc');
                                $data = [
                                    'value' => 'wp_forms',
                                    'label' => $label,
                                ];
                                echo self::render_select_option(
                                    $data,
                                    $current_val,
                                );
                                ?>
                            </select>
                        </td>
                    </tr>

                    <!-- Sender Type -->
                    <tr valign="top">
                        <th scope="row"><?php _e(
                            'List Values JSON',
                            'cds-snc',
                        ); ?></th>
                        <td>
                            <?php $val = esc_attr(get_option('list_values')); ?>
                            <textarea name="list_values" rows="4" cols="50"><?php echo $val; ?></textarea>

                            <p class="description" id="new-admin-email-description"><?php _e(
                                'Format',
                                'cds-snc',
                            ); ?>:
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
}
