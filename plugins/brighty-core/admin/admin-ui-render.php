<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * @function	BRIGHTY_CORE_general_settings_section_callback()	Callback function for General Settings section
 * @function	BRIGHTY_CORE_general_settings_field_callback()	Callback function for General Settings field
 * @function	BRIGHTY_CORE_admin_interface_render()				Admin interface renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Callback function for General Settings section
 *
 * @since 1.0
 */
function BRIGHTY_CORE_general_settings_section_callback() {

	echo '<p>' . __('A long description for the settings section goes here.', 'brighty-core') . '</p>';

}

/**
 * Callback function for General Settings field
 *
 * @since 1.0
 */
function BRIGHTY_CORE_general_settings_field_callback() {	

	// Get Settings
	$settings = BRIGHTY_CORE_get_settings();

	// General Settings. Name of form element should be same as the setting name in register_setting(). ?>
	
	<fieldset>
	
		<!-- Setting one -->
		<input type="checkbox" name="BRIGHTY_CORE_settings[setting_one]" id="BRIGHTY_CORE_settings[setting_one]" value="1" 
			<?php if ( isset( $settings['setting_one'] ) ) { checked( '1', $settings['setting_one'] ); } ?>>
			<label for="BRIGHTY_CORE_settings[setting_one]"><?php _e('Setting one', 'brighty-core') ?></label>
			<br>
			
		<!-- Setting two -->
		<input type="checkbox" name="BRIGHTY_CORE_settings[setting_two]" id="BRIGHTY_CORE_settings[setting_two]" value="1" 
			<?php if ( isset( $settings['setting_two'] ) ) { checked( '1', $settings['setting_two'] ); } ?>>
			<label for="BRIGHTY_CORE_settings[setting_two]"><?php _e('Setting two', 'brighty-core') ?></label>
			<br>
		
		<!-- Text Input -->
		<input type="text" name="BRIGHTY_CORE_settings[text_input]" class="regular-text" value="<?php if ( isset( $settings['text_input'] ) && ( ! empty($settings['text_input']) ) ) echo esc_attr($settings['text_input']); ?>"/>
		<p class="description"><?php _e('Description of the text input field', 'brighty-core'); ?></p>
		
	</fieldset>
	<?php
}
 
/**
 * Admin interface renderer
 *
 * @since 1.0
 */ 
function BRIGHTY_CORE_admin_interface_render () {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	/**
	 * If settings are inside WP-Admin > Settings, then WordPress will automatically display Settings Saved. If not used this block
	 * @refer	https://core.trac.wordpress.org/ticket/31000
	 * If the user have submitted the settings, WordPress will add the "settings-updated" $_GET parameter to the url
	 *
	if ( isset( $_GET['settings-updated'] ) ) {
		// Add settings saved message with the class of "updated"
		add_settings_error( 'BRIGHTY_CORE_settings_saved_message', 'BRIGHTY_CORE_settings_saved_message', __( 'Settings are Saved', 'brighty-core' ), 'updated' );
	}
 
	// Show Settings Saved Message
	settings_errors( 'BRIGHTY_CORE_settings_saved_message' ); */?> 
	
	<div class="wrap">	
		<h1>Brighty Core</h1>
		
		<form action="options.php" method="post">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'BRIGHTY_CORE_settings_group' );
			
			// Prints out all settings sections added to a particular settings page. 
			do_settings_sections( 'brighty-core' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'brighty-core') );
			?>
		</form>
	</div>
	<?php
}