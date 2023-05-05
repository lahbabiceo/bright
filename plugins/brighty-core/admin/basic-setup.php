<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * @function	BRIGHTY_CORE_activate_plugin()		Plugin activatation todo list
 * @function	BRIGHTY_CORE_load_plugin_textdomain()	Load plugin text domain
 * @function	BRIGHTY_CORE_settings_link()			Print direct link to plugin settings in plugins list in admin
 * @function	BRIGHTY_CORE_plugin_row_meta()		Add donate and other links to plugins list
 * @function	BRIGHTY_CORE_footer_text()			Admin footer text
 * @function	BRIGHTY_CORE_footer_version()			Admin footer version
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
/**
 * Plugin activatation todo list
 *
 * This function runs when user activates the plugin. Used in register_activation_hook in the main plugin file. 
 * @since 1.0
 */
function BRIGHTY_CORE_activate_plugin() {
	
}

/**
 * Load plugin text domain
 *
 * @since 1.0
 */
function BRIGHTY_CORE_load_plugin_textdomain() {
    load_plugin_textdomain( 'brighty-core', false, '/brighty-core/languages/' );
}
add_action( 'plugins_loaded', 'BRIGHTY_CORE_load_plugin_textdomain' );

/**
 * Print direct link to plugin settings in plugins list in admin
 *
 * @since 1.0
 */
function BRIGHTY_CORE_settings_link( $links ) {
	return array_merge(
		array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=brighty-core' ) . '">' . __( 'Settings', 'brighty-core' ) . '</a>'
		),
		$links
	);
}
add_filter( 'plugin_action_links_' . BRIGHTY_CORE_PLUGIN . '/brighty-core.php', 'BRIGHTY_CORE_settings_link' );

/**
 * Add donate and other links to plugins list
 *
 * @since 1.0
 */
function BRIGHTY_CORE_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'brighty-core.php' ) !== false ) {
		$new_links = array(
				'donate' 	=> '<a href="http://millionclues.com/donate/" target="_blank">Donate</a>',
				'kuttappi' 	=> '<a href="http://kuttappi.com/" target="_blank">My Travelogue</a>',
				'hireme' 	=> '<a href="http://millionclues.com/portfolio/" target="_blank">Hire Me For A Project</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'BRIGHTY_CORE_plugin_row_meta', 10, 2 );

/**
 * Admin footer text
 *
 * A function to add footer text to the settings page of the plugin. Footer text contains plugin rating and donation links.
 * Note: Remove the rating link if the plugin doesn't have a WordPress.org directory listing yet. (i.e. before initial approval)
 *
 * @since 1.0
 * @refer https://codex.wordpress.org/Function_Reference/get_current_screen
 */
function BRIGHTY_CORE_footer_text($default) {
    
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== "settings_page_brighty-core" ) {
		return $default;
	}
	
    $BRIGHTY_CORE_footer_text = sprintf( __( 'If you like this plugin, please <a href="%s" target="_blank">make a donation</a> or leave me a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to support continued development. Thanks a bunch!', 'brighty-core' ), 
								'http://millionclues.com/donate/',
								'https://wordpress.org/support/plugin/brighty-core/reviews/?rate=5#new-post' 
						);
	
	return $BRIGHTY_CORE_footer_text;
}
add_filter('admin_footer_text', 'BRIGHTY_CORE_footer_text');

/**
 * Admin footer version
 *
 * @since 1.0
 */
function BRIGHTY_CORE_footer_version($default) {
	
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== 'settings_page_brighty-core' ) {
		return $default;
	}
	
	return 'Plugin version ' . BRIGHTY_CORE_VERSION_NUM;
}
add_filter( 'update_footer', 'BRIGHTY_CORE_footer_version', 11 );