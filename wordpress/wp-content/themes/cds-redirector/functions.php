<?php

/**
 * Theme Options Panel
 *
 */

namespace CDS\Redirector;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit();
}

require_once __DIR__ . '/filter-core-buttons.php';
require_once __DIR__ . '/admin-css.php';

use CDS\Utils;

// Start Class
if (!class_exists('Redirector')) {
    class Redirector
    {
        /**
         * @since 1.1.0
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
            add_action('after_setup_theme', [$this, 'registerNavMenus']);

            // Register the admin panel on the back-end
            if (is_admin()) {
                add_action('admin_menu', [$this, 'addAdminMenu']);
                add_action('admin_init', [$this, 'registerSettings']);

                add_action('admin_enqueue_scripts', [$this, 'redirectorStyles']);
            }
        }

        /**
         * Enqueue styles.
         */
        public function redirectorStyles(): void
        {
            wp_enqueue_style('redirector-style', get_stylesheet_uri(), []);
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
                [$this, 'createAdminPage'],
            );
        }

        /**
         * @since 1.0.0
         */
        public function registerSettings()
        {
            register_setting('theme_options', 'theme_options', [
                $this,
                'sanitize',
            ]);
        }

        /**
         * @since 1.1.0
         */
        public function registerNavMenus()
        {
            // This theme uses wp_nav_menu() in two locations.
            register_nav_menus([
                'header' => esc_html__('Primary', 'cds-snc'),
                'footer' => esc_html__('Footer', 'cds-snc'),
            ]);
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
                if (!empty($options['redirect_url'])) {
                    $options['redirect_url'] = sanitize_text_field(
                        $options['redirect_url'],
                    );
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
            <?php
        }

        public static function getActiveLanguage(): string
        {
            if (function_exists('icl_get_languages')) {
                if (ICL_LANGUAGE_CODE !== null) {
                    return ICL_LANGUAGE_CODE;
                }
            }

            try {
                $locale = get_locale();
                $pieces = explode('_', $locale);
                return $pieces[0];
            } catch (Exception $e) {
                return 'en';
            }
        }
    }
}

Redirector::register();

// Helper function to use in your theme to return a theme option value
function cds_get_theme_option($id = ''): ?string
{
    return Redirector::getThemeOption($id);
}

function cds_get_active_language(): ?string
{
    return Redirector::getActiveLanguage();
}

add_filter('gutenberg_can_edit_post', '__return_true', 5);
add_filter('use_block_editor_for_post', '__return_true', 5);
add_filter('user_can_richedit', '__return_true', 50);


// Allow SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

    global $wp_version;
    if ($wp_version !== '4.7.1') {
        return $data;
    }

    $filetype = wp_check_filetype($filename, $mimes);

    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
}, 10, 4);

function cc_mime_types($mimes)
{
    $mimes['json'] = 'application/json';
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg()
{
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
}
add_action('admin_head', 'fix_svg');