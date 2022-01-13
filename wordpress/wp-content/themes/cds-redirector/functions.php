<?php

namespace CDS\Redirector;

/**
 * Theme Options Panel
 *
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Start Class
if (! class_exists('Redirector')) {

    class Redirector
    {

        /**
         * @since 1.0.0
         */
        public function __construct()
        {
        }

        public static function register()
        {
            $instance = new self();
            $instance->addActions();
        }

        public function addActions()
        {
             // Register the admin panel on the back-end
            if (is_admin()) {
                add_action('admin_menu', array( $this, 'addAdminMenu' ));
                add_action('admin_init', array(  $this, 'registerSettings' ));
            }
        }

        /**
         * Returns all theme options
         *
         * @since 1.0.0
         */
        public static function getThemeOptions()
        {
            return get_option('theme_options');
        }

        /**
         * Returns single theme option
         *
         * @since 1.0.0
         */
        public static function getThemeOption($id)
        {
            $options = self::getThemeOptions();
            if (isset($options[$id])) {
                return $options[$id];
            }
        }

        /**
         * Add sub menu page
         *
         * @since 1.0.0
         */
        public function addAdminMenu()
        {
            add_menu_page(
                esc_html__('Theme Settings', 'cds-redirect'),
                esc_html__('Theme Settings', 'cds-redirect'),
                'manage_options',
                'theme-settings',
                array( $this, 'createAdminPage' )
            );
        }

        /**
         * @since 1.0.0
         */
        public function registerSettings()
        {
            register_setting('theme_options', 'theme_options', array( $this, 'sanitize' ));
        }

        /**
         * Sanitization callback
         *
         * @since 1.0.0
         */
        public static function sanitize($options)
        {

            // If we have options lets sanitize them
            if ($options) {
                // Input
                if (! empty($options['redirect_url'])) {
                    $options['redirect_url'] = sanitize_text_field($options['redirect_url']);
                } else {
                    unset($options['redirect_url']); // Remove from options if empty
                }
            }

            // Return sanitized options
            return $options;
        }

        /**
         * Settings page output
         *
         * @since 1.0.0
         */
        public static function createAdminPage()
        {
            ?>

            <div class="wrap">

                <h1><?php esc_html_e('Theme Options', 'cds-redirect'); ?></h1>

                <form method="post" action="options.php">

                    <?php settings_fields('theme_options'); ?>

                    <table class="form-table cds-custom-admin-login-table">
                        <?php // Text input example ?>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e('Redirect URL', 'cds-redirect'); ?></th>
                            <td>
                                <?php $value = self::getThemeOption('redirect_url'); ?>
                                <input type="text" name="theme_options[redirect_url]" value="<?php echo esc_attr($value); ?>">
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div><!-- .wrap -->
        <?php }
    }
}

Redirector::register();

// Helper function to use in your theme to return a theme option value
function cds_get_theme_option($id = '')
{
    return Redirector::getThemeOption($id);
}