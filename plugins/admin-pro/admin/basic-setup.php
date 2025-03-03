<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * @function	prefix_activate_plugin()		Plugin activatation todo list
 * @function	prefix_load_plugin_textdomain()	Load plugin text domain
 * @function	prefix_settings_link()			Print direct link to plugin settings in plugins list in admin
 * @function	prefix_plugin_row_meta()		Add donate and other links to plugins list
 * @function	prefix_footer_text()			Admin footer text
 * @function	prefix_footer_version()			Admin footer version
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Plugin activatation todo list
 *
 * This function runs when user activates the plugin. Used in register_activation_hook in the main plugin file. 
 * @since 1.0
 */
function admin_pro_activate_plugin() {
	
}

/**
 * Load plugin text domain
 *
 * @since 1.0
 */
function admin_pro_load_plugin_textdomain() {
    load_plugin_textdomain( 'admin-pro', false, '/admin-pro/languages/' );
}
add_action( 'plugins_loaded', 'admin_pro_load_plugin_textdomain' );

/**
 * Print direct link to plugin settings in plugins list in admin
 *
 * @since 1.0
 */
function admin_pro_settings_link( $links ) {
	return array_merge(
		array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=admin-pro' ) . '">' . __( 'Settings', 'admin-pro' ) . '</a>'
		),
		$links
	);
}
add_filter( 'plugin_action_links_' . AP_ADMIN_PRO . '/admin-pro.php', 'admin_pro_settings_link' );

/**
 * Add donate and other links to plugins list
 *
 * @since 1.0
 */
function admin_pro_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'admin-pro.php' ) !== false ) {
		$new_links = array(
				'hireme' 	=> '<a href="http://iqltech.com/" target="_blank">Hire IQL Technologies For A Project</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'admin_pro_plugin_row_meta', 10, 2 );

/**
 * Admin footer text
 *
 * A function to add footer text to the settings page of the plugin. Footer text contains plugin rating and donation links.
 * Note: Remove the rating link if the plugin doesn't have a WordPress.org directory listing yet. (i.e. before initial approval)
 *
 * @since 1.0
 * @refer https://codex.wordpress.org/Function_Reference/get_current_screen
 */
function admin_pro_footer_text($default) {
    
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== "settings_page_admin-pro" ) {
		return $default;
	}
	
    $admin_pro_footer_text = sprintf( __( 'If you like this plugin, please <a href="%s" target="_blank">make a donation</a> or leave  a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to support continued development. ', 'admin-pro' ), 
								'http://iqltech.com/',
								'https://wordpress.org/support/plugin/admin-pro/reviews/?rate=5#new-post' 
						);
	
	return $admin_pro_footer_text;
}
add_filter('admin_footer_text', 'admin_pro_footer_text');

/**
 * Admin footer version
 *
 * @since 1.0
 */
function admin_pro_footer_version($default) {
	
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== 'settings_page_admin-pro' ) {
		return $default;
	}
	
	return 'Plugin version ' . AP_VERSION_NUM;
}
add_filter( 'update_footer', 'admin_pro_footer_version', 11 );