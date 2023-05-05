<?php
/**
 * Plugin Name: Brighty Core
 * Plugin URI: https://github.com/arunbasillal/WordPress-brighty-core
 * Description: Web Hosting Automation, Billing and Provisioning Platform for the Sane Hosts
 * Author: IQL Technologies
 * Author URI: https://iqltech.com
 * Version: 1.0
 * Text Domain: brighty
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * This plugin was developed using the WordPress Brighty Core;  
 * @GitHub https://github.com/arunbasillal/WordPress-brighty-core
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
 * brighty-core.php				- Main plugin file containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2018/01/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 *
 * - Note: (S&R) = Search and Replace by matching case.
 *
 * - Plugin name: Brighty Core (S&R)
 * - Plugin folder slug: brighty-core (S&R)
 * - Decide on a prefix for the plugin (S&R)
 * - Plugin description
 * - Text domain. Text domain for plugins has to be the folder name of the plugin. For eg. if your plugin is in /wp-content/plugins/abc-def/ folder text domain should be abc-def (S&R)
 * - Update BRIGHTY_CORE_settings_link() 		in \admin\basic-setup.php
 * - Update BRIGHTY_CORE_footer_text()		in \admin\basic-setup.php
 * - Update BRIGHTY_CORE_add_menu_links() 		in \admin\admin-ui-setup.php
 * - Update BRIGHTY_CORE_register_settings() 		in \admin\admin-ui-setup.php
 * - Update UI format and settings		in \admin\admin-ui-render.php
 * - Update uninstall.php
 * - Update readme.txt
 * - Update BRIGHTY_CORE_VERSION_NUM 			in brighty-core.php (keep this line for future updates)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 1.0
 */
if ( ! defined( 'BRIGHTY_CORE_VERSION_NUM' ) ) 		define( 'BRIGHTY_CORE_VERSION_NUM'		, '1.0' ); // Plugin version constant
if ( ! defined( 'BRIGHTY_CORE_PLUGIN' ) )		define( 'BRIGHTY_CORE_PLUGIN'		, trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'brighty-core'
if ( ! defined( 'BRIGHTY_CORE_PLUGIN_DIR' ) )	define( 'BRIGHTY_CORE_PLUGIN_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/brighty-core/
if ( ! defined( 'BRIGHTY_CORE_PLUGIN_URL' ) )	define( 'BRIGHTY_CORE_PLUGIN_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/brighty-core/

/**
 * Database upgrade todo
 *
 * @since 1.0
 */
function BRIGHTY_CORE_upgrader() {
	
	// Get the current version of the plugin stored in the database.
	$current_ver = get_option( 'abl_BRIGHTY_CORE_version', '0.0' );
	
	// Return if we are already on updated version. 
	if ( version_compare( $current_ver, BRIGHTY_CORE_VERSION_NUM, '==' ) ) {
		return;
	}
	
	// This part will only be excuted once when a user upgrades from an older version to a newer version.
	
	// Finally add the current version to the database. Upgrade todo complete. 
	update_option( 'abl_BRIGHTY_CORE_version', BRIGHTY_CORE_VERSION_NUM );
}
add_action( 'admin_init', 'BRIGHTY_CORE_upgrader' );

// Load everything
require_once( BRIGHTY_CORE_PLUGIN_DIR . 'loader.php' );

// Register activation hook (this has to be in the main plugin file or refer bit.ly/2qMbn2O)
register_activation_hook( __FILE__, 'BRIGHTY_CORE_activate_plugin' );