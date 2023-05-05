<?php
/**
 * Admin setup for the plugin
 *
 * @since 1.0
 * @function	BRIGHTY_CORE_add_menu_links()		Add admin menu pages
 * @function	BRIGHTY_CORE_register_settings	Register Settings
 * @function	BRIGHTY_CORE_validater_and_sanitizer()	Validate And Sanitize User Input Before Its Saved To Database
 * @function	BRIGHTY_CORE_get_settings()		Get settings from database
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit; 
 
/**
 * Add admin menu pages
 *
 * @since 1.0
 * @refer https://developer.wordpress.org/plugins/administration-menus/
 */
function BRIGHTY_CORE_add_menu_links() {
	add_options_page ( __('Brighty Core','brighty-core'), __('Brighty Core','brighty-core'), 'update_core', 'brighty-core','BRIGHTY_CORE_admin_interface_render'  );
}
add_action( 'admin_menu', 'BRIGHTY_CORE_add_menu_links' );

/**
 * Register Settings
 *
 * @since 1.0
 */
function BRIGHTY_CORE_register_settings() {

	// Register Setting
	register_setting( 
		'BRIGHTY_CORE_settings_group', 			// Group name
		'BRIGHTY_CORE_settings', 					// Setting name = html form <input> name on settings form
		'BRIGHTY_CORE_validater_and_sanitizer'	// Input sanitizer
	);
	
	// Register A New Section
    add_settings_section(
        'BRIGHTY_CORE_general_settings_section',							// ID
        __('Brighty Core General Settings', 'brighty-core'),		// Title
        'BRIGHTY_CORE_general_settings_section_callback',					// Callback Function
        'brighty-core'											// Page slug
    );
	
	// General Settings
    add_settings_field(
        'BRIGHTY_CORE_general_settings_field',							// ID
        __('General Settings', 'brighty-core'),					// Title
        'BRIGHTY_CORE_general_settings_field_callback',					// Callback function
        'brighty-core',											// Page slug
        'BRIGHTY_CORE_general_settings_section'							// Settings Section ID
    );
	
}
add_action( 'admin_init', 'BRIGHTY_CORE_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @since 1.0
 */
function BRIGHTY_CORE_validater_and_sanitizer ( $settings ) {
	
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
function BRIGHTY_CORE_get_settings() {

	$defaults = array(
				'setting_one' 	=> '1',
				'setting_two' 	=> '1',
			);

	$settings = get_option('BRIGHTY_CORE_settings', $defaults);
	
	return $settings;
}

/**
 * Enqueue Admin CSS and JS
 *
 * @since 1.0
 */
function BRIGHTY_CORE_enqueue_css_js( $hook ) {
	
    // Load only on Starer Plugin plugin pages
	if ( $hook != "settings_page_brighty-core" ) {
		return;
	}
	
	// Main CSS
	// wp_enqueue_style( 'prefix-admin-main-css', BRIGHTY_CORE_STARTER_PLUGIN_URL . 'admin/css/main.css', '', BRIGHTY_CORE_VERSION_NUM );
	
	// Main JS
    // wp_enqueue_script( 'prefix-admin-main-js', BRIGHTY_CORE_STARTER_PLUGIN_URL . 'admin/js/main.js', array( 'jquery' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'BRIGHTY_CORE_enqueue_css_js' );