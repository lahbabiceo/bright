<?php
/**
 * Admin setup for the plugin
 *
 * @since 1.0
 * @function	admin_pro_add_menu_links()		Add admin menu pages
 * @function	admin_pro_register_settings	Register Settings
 * @function	admin_pro_validater_and_sanitizer()	Validate And Sanitize User Input Before Its Saved To Database
 * @function	admin_pro_get_settings()		Get settings from database
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit; 
 
/**
 * Add admin menu pages
 *
 * @since 1.0
 * @refer https://developer.wordpress.org/plugins/administration-menus/
 */
function admin_pro_add_menu_links() {
	add_options_page ( __('Starter Plugin','admin-pro'), __('Starter Plugin','admin-pro'), 'update_core', 'admin-pro','admin_pro_admin_interface_render'  );
}
add_action( 'admin_menu', 'admin_pro_add_menu_links' );

/**
 * Register Settings
 *
 * @since 1.0
 */
function admin_pro_register_settings() {

	// Register Setting
	register_setting( 
		'admin_pro_settings_group', 			// Group name
		'admin_pro_settings', 					// Setting name = html form <input> name on settings form
		'admin_pro_validater_and_sanitizer'	// Input sanitizer
	);
	
	// Register A New Section
    add_settings_section(
        'admin_pro_general_settings_section',							// ID
        __('Starter Plugin General Settings', 'admin-pro'),		// Title
        'admin_pro_general_settings_section_callback',					// Callback Function
        'admin-pro'											// Page slug
    );
	
	// General Settings
    add_settings_field(
        'admin_pro_general_settings_field',							// ID
        __('General Settings', 'admin-pro'),					// Title
        'admin_pro_general_settings_field_callback',					// Callback function
        'admin-pro',											// Page slug
        'admin_pro_general_settings_section'							// Settings Section ID
    );
	
}
add_action( 'admin_init', 'admin_pro_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @since 1.0
 */
function admin_pro_validater_and_sanitizer ( $settings ) {
	
	// Sanitize text field
	$settings['text_input'] = sanitize_text_field($settings['text_input']);
	
	return $settings;
}
			
/**
 * Get settings from database
 *
 * @return	Array	A merged array of default and settings saved in database. 
 *
 * @since 1.0
 */
function admin_pro_get_settings() {

	$defaults = array(
				'setting_one' 	=> '1',
				'setting_two' 	=> '1',
			);

	$settings = get_option('admin_pro_settings', $defaults);
	
	return $settings;
}

/**
 * Enqueue Admin CSS and JS
 *
 * @since 1.0
 */
function admin_pro_enqueue_css_js( $hook ) {
	
    // Load only on Starer Plugin plugin pages
	if ( $hook != "settings_page_admin-pro" ) {
		return;
	}
	
	// Main CSS
	// wp_enqueue_style( 'admin_pro-admin-main-css', admin_pro_STARTER_PLUGIN_URL . 'admin/css/main.css', '', admin_pro_VERSION_NUM );
	
	// Main JS
    // wp_enqueue_script( 'admin_pro-admin-main-js', admin_pro_STARTER_PLUGIN_URL . 'admin/js/main.js', array( 'jquery' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'admin_pro_enqueue_css_js' );