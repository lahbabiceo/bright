<?php

/**
 * Plugin Name: WP Mail Log
 * Description: WP Mail Log helps you to Log and view all emails from WordPress.
 * Plugin URI: https://wpvibes.com/
 * Author: WPVibes
 * Version: 1.0.1
 * Author URI: https://wpvibes.com/
 * License:      GNU General Public License v2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wml-wts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


define( 'WML_URL', plugins_url( '/', __FILE__ ) );
define( 'WML_PATH', plugin_dir_path( __FILE__ ) );
define( 'WML_BASE', plugin_basename( __FILE__ ) );
define( 'WML_FILE', __FILE__ );
define( 'WML_VERSION', '1.0.1' );

if ( ! function_exists( 'wml_fs' ) ) {
	// Create a helper function for easy SDK access.
	function wml_fs() {
		global $wml_fs;
		if ( ! isset( $wml_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';
			$wml_fs = fs_dynamic_init(
				[
					'id'             => '10460',
					'slug'           => 'wp-mail-log',
					'type'           => 'plugin',
					'public_key'     => 'pk_af9097a9b1f4b708e6e16fc7f0eec',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'menu'           => [
						'slug'    => 'wp-mail-log',
						'account' => false,
						'contact' => false,
					],
				]
			);
		}
		return $wml_fs;
	}
	// Init Freemius.
	wml_fs();
	// Signal that SDK was initiated.
	do_action( 'wml_fs_loaded' );
}

require WML_PATH . 'includes/bootstrap.php';
