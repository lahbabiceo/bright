<?php
/**
 * Plugin Name: Subaccounts for WooCommerce
 * Plugin URI: https://subaccounts.pro/
 * Description: Subaccounts for WooCommerce adds the possibility to create subaccounts for customers and subscribers within WooCommerce.
 * Version: 1.0.0
 * Author: Mediaticus
 * Update URI: https://wordpress.org/plugins/subaccounts-for-woocommerce/
 *
 * Text Domain: subaccounts-for-woocommerce
 * Domain Path: /languages/
 *
 * Requires at least: 5.7
 * Tested up to: 6.0
 *
 * WC tested up to: 6.6.1
 * Requires PHP: 5.7
 *
 * Copyright 2021 Mediaticus
 *
 * License: GPL3
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}




if ( ! function_exists( 'sfwc_free' ) ) {
    // Create a helper function for easy SDK access.
    function sfwc_free() {
        global $sfwc_free;

        if ( ! isset( $sfwc_free ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $sfwc_free = fs_dynamic_init( array(
                'id'                  => '10450',
                'slug'                => 'subaccounts-for-woocommerce',
                'type'                => 'plugin',
                'public_key'          => 'pk_5e73c22e9eb9062ca988afae26a46',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'subaccounts',
                    'account'        => false,
                    'support'        => false,
                    'parent'         => array(
                        'slug' => 'woocommerce',
                    ),
                ),
            ) );
        }

        return $sfwc_free;
    }

    // Init Freemius.
    sfwc_free();
    // Signal that SDK was initiated.
    do_action( 'sfwc_free_loaded' );
}



/**
 * Check Plugin Requirements.
 *
 * Custom function to get list of active plugins. Unlike is_plugin_active WordPress function,
 * this one is available also on frontend.
 */
if ( ! function_exists( 'sfwc_is_plugin_active' ) ) {  // Check if function already exists from Pro plugin to avoid issues in case the Pro is activated first and then Free plugin.
	
	function sfwc_is_plugin_active( $plugin_name ) {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		$plugin_filenames = array();

		foreach ( $active_plugins as $plugin ) {

			if ( false !== strpos( $plugin, '/' ) ) {

				// Normal plugin name (plugin-dir/plugin-filename.php).
				list( , $filename ) = explode( '/', $plugin );

			} else {

				// No directory, just plugin file.
				$filename = $plugin;
			}

			$plugin_filenames[] = $filename;
		}

		return in_array( $plugin_name, $plugin_filenames );
	}
}


/**
 * Check Plugin Requirements.
 *
 * Add admin notice in case WooCommerce is not active.
 */
if ( ! sfwc_is_plugin_active( 'woocommerce.php' ) ) {

	add_action('admin_notices', 'sfwc_child_plugin_notice_woocommerce_not_active');

	return;
}




/**
 * Check Plugin Requirements.
 *
 * Echo admin notice in case WooCommerce is not active.
 */
function sfwc_child_plugin_notice_woocommerce_not_active() {

    echo '<div class="error"><p>';

	printf( esc_html__( '%1$s must be installed and activated in order to use %2$s.', 'subaccounts-for-woocommerce' ), '<strong>WooCommerce</strong>', '<strong>Subaccounts for WooCommerce</strong>' );

    echo '</p></div>';
}






/**
 * Load plugin textdomain for translations
 */
function sfwc_load_textdomain() {
    load_plugin_textdomain( 'subaccounts-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action('init', 'sfwc_load_textdomain');




/**
 * Load files
 */
if ( is_admin() ) {
		
	// Admin area
	require_once( plugin_dir_path( __FILE__ ) . 'admin/admin.php' );
	
} else {
	
	// Public area
	require_once( plugin_dir_path( __FILE__ ) . 'public/my-account.php' );

}

require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );