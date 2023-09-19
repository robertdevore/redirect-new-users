<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.robertdevore.com
 * @since             1.0.0
 * @package           Redirect_New_Users
 *
 * @wordpress-plugin
 *
 * Plugin Name: Redirect New Users
 * Description: Redirect new users to a specific page after signup.
 * Plugin URI:  https://www.robertdevore.com/redirect-new-users-wordpress-plugin/
 * Version:     1.0.0
 * Author:      Robert DeVore
 * Author URI:  https://www.robertdevore.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: redirect-new-users
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'REDIRECT_NEW_USERS_VERSION', '1.0.0' );

/**
 * Adds a menu item in the WordPress admin dashboard.
 * 
 * @since  1.0.0
 * @return void
 */
function rnus_create_menu() {
    add_submenu_page( 'options-general.php', esc_html__( 'Redirect New Users Settings', 'redirect-new-users' ), esc_html__( 'Redirect New Users', 'redirect-new-users' ), 'manage_options', 'rnus-settings', 'rnus_settings_page' );

    // Register the plugin settings.
    add_action( 'admin_init', 'rnus_register_settings' );
}
add_action( 'admin_menu', 'rnus_create_menu' );

/**
 * Registers the plugin settings.
 * 
 * @since  1.0.0
 * @return void
 */
function rnus_register_settings() {
    register_setting( 'rnus-settings-group', 'rnus_redirect_url' );
}

/**
 * Displays the settings page for redirecting new users.
 * 
 * @since  1.0.0
 * @return string
 */
function rnus_settings_page() {
    ?>
    <div class="wrap">
        <h2><?php esc_html_e( 'Redirect New Users Settings', 'redirect-new-users' ); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'rnus-settings-group' ); ?>
            <?php $current_redirect_url = get_option( 'rnus_redirect_url', '' ); ?>
            <label for="rnus_redirect_url"><?php esc_html_e( 'Enter the URL to redirect new users to:', 'redirect-new-users' ); ?><br /></label>
            <input type="text" id="rnus_redirect_url" name="rnus_redirect_url" value="<?php echo esc_url( $current_redirect_url ); ?>" style="width: 420px; max-width: 100%; height: 40px;" />
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Registration redirect filter
 * 
 * @since  1.0.0
 * @return string
 */
function rnus_registration_redirect_filter( $url ) {
    // Get the redirect page, if any.
    $redirect_url = get_option( 'rnus_redirect_url' );

    // Verify there's a page selected.
    if ( $redirect_url ) {
        // Set the new redirect URL.
        $url = esc_url( $redirect_url );
    }

    return $url;
}
add_filter( 'registration_redirect', 'rnus_registration_redirect_filter' );
