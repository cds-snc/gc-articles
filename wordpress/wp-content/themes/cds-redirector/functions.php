<?php
/**
 * Theme Options Panel
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'CDS_Theme_Options' ) ) {

	class CDS_Theme_Options {

		/**
		 * @since 1.0.0
		 */
		public function __construct() {

			// Register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'CDS_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'CDS_Theme_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_options() {
			return get_option( 'theme_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'Theme Settings', 'cds-redirect' ),
				esc_html__( 'Theme Settings', 'cds-redirect' ),
				'manage_options',
				'theme-settings',
				array( 'CDS_Theme_Options', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'theme_options', 'theme_options', array( 'CDS_Theme_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {
				// Input
				if ( ! empty( $options['redirect_url'] ) ) {
					$options['redirect_url'] = sanitize_text_field( $options['redirect_url'] );
				} else {
					unset( $options['redirect_url'] ); // Remove from options if empty
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
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h1><?php esc_html_e( 'Theme Options', 'cds-redirect' ); ?></h1>

				<form method="post" action="options.php">

					<?php settings_fields( 'theme_options' ); ?>

					<table class="form-table cds-custom-admin-login-table">
						<?php // Text input example ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Redirect URL', 'cds-redirect' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( 'redirect_url' ); ?>
								<input type="text" name="theme_options[redirect_url]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div><!-- .wrap -->
		<?php }
	}
}
new CDS_Theme_Options();

// Helper function to use in your theme to return a theme option value
function cds_get_theme_option( $id = '' ) {
	return CDS_Theme_Options::get_theme_option( $id );
}