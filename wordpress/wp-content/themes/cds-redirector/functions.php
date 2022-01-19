<?php

/**
 * Theme Options Panel
 *
 */

namespace CDS\Redirector;

use CDS\Utils;

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

                add_action('admin_footer', function () {
                    global $post;

                    if (!$post) {
                        return;
                    }

                    $text = __("Preview", "cds-snc");
                    $url = Utils::addHttp(cds_get_theme_option("redirect_url")) . "/preview?id=" . $post->ID."&lang=".cds_get_active_language();
                    echo "<script>
                    jQuery(document).ready(function( $ ) {
                        setTimeout(function(){
                            $('.block-editor-post-preview__dropdown').replaceWith('<a class=\"components-button is-tertiary\" href=\"$url\">$text</a>');
                        }, 1000);
                    });
                    </script>";
                }, 200, 10);
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

        public static function getActiveLanguage(): string
        {
            if (function_exists('icl_get_languages')) {
                if (ICL_LANGUAGE_CODE !== null) {
                    return ICL_LANGUAGE_CODE;
                }
            }

            try {
                $locale = get_locale();
                $pieces = explode("_", $locale);
                return $pieces[0];
            } catch (Exception $e) {
                return "en";
            }
        }
    }
}

Redirector::register();

// Helper function to use in your theme to return a theme option value
function cds_get_theme_option($id = '')
{
    return Redirector::getThemeOption($id);
}

function cds_get_active_language()
{
    return Redirector::getActiveLanguage();
}

add_filter('gutenberg_can_edit_post', '__return_true', 5);
add_filter('use_block_editor_for_post', '__return_true', 5);
add_filter('user_can_richedit', '__return_true', 50);