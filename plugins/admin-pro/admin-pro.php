<?php
/**
 * Plugin Name: Admin Pro
 * Plugin URI: https://github.com/tariq-abdullah/admin-pro
 * Description: A  plugin to redesign WordPress Backend
 * Author: Tariq Abdullah
 * Author URI: https://iqltech.com
 * Version: 1.0
 * Text Domain: admin-pro
 * Domain Path: /languages
 * License: MIT License
 */

/**
 * This plugin was developed using 
 * @GitHub https://github.com/arunbasillal/WordPress-Starter-Plugin
 */
 
/**
 * ~ Directory Structure ~
 *
 * /admin/ 					- Plugin backend stuff.
 * /functions/					- Functions and plugin operations.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/					- Front end files and functions that matter on the front end go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * starter-plugin.php				- Main plugin file containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2018/01/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 *
 * - Note: (S&R) = Search and Replace by matching case.
 *
 * - Plugin name: Starter Plugin (S&R)
 * - Plugin folder slug: starter-plugin (S&R)
 * - Decide on a prefix for the plugin (S&R)
 * - Plugin description
 * - Text domain. Text domain for plugins has to be the folder name of the plugin. For eg. if your plugin is in /wp-content/plugins/abc-def/ folder text domain should be abc-def (S&R)
 * - Update prefix_settings_link() 		in \admin\basic-setup.php
 * - Update prefix_footer_text()		in \admin\basic-setup.php
 * - Update prefix_add_menu_links() 		in \admin\admin-ui-setup.php
 * - Update prefix_register_settings() 		in \admin\admin-ui-setup.php
 * - Update UI format and settings		in \admin\admin-ui-render.php
 * - Update uninstall.php
 * - Update readme.txt
 * - Update PREFIX_VERSION_NUM 			in starter-plugin.php (keep this line for future updates)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 1.0
 */
if ( ! defined( 'AP_VERSION_NUM' ) ) 		define( 'AP_VERSION_NUM'		, '2.0' ); // Plugin version constant
if ( ! defined( 'AP_ADMIN_PRO' ) )		define( 'AP_ADMIN_PRO'		, trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'starter-plugin'
if ( ! defined( 'AP_ADMIN_PRO_DIR' ) )	define( 'AP_ADMIN_PRO_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/starter-plugin/
if ( ! defined( 'AP_ADMIN_PRO_URL' ) )	define( 'AP_ADMIN_PRO_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/starter-plugin/

/**
 * Database upgrade todo
 *
 * @since 1.0
 */
function prefix_upgrader() {
	
	// Get the current version of the plugin stored in the database.
	$current_ver = get_option( 'AP_ADMIN_PRO_VERSION', '0.0' );
	
	// Return if we are already on updated version. 
	if ( version_compare( $current_ver, AP_VERSION_NUM, '==' ) ) {
		return;
	}
	
	// This part will only be excuted once when a user upgrades from an older version to a newer version.
	
	// Finally add the current version to the database. Upgrade todo complete. 
	update_option( 'AP_ADMIN_PRO_VERSION', AP_VERSION_NUM );
}
add_action( 'admin_init', 'prefix_upgrader' );

// Load everything
require_once( AP_ADMIN_PRO_DIR . 'loader.php' );

// Register activation hook (this has to be in the main plugin file or refer bit.ly/2qMbn2O)
register_activation_hook( __FILE__, 'prefix_activate_plugin' );