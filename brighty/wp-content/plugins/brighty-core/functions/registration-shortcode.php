<?php

/**
 * @package Brighty
 * @abstract Creates shortcode for Registration Form and save the fields on submission
 * @version 1.0.0
 * @author Tariq Abdullah <tariq@iqltech.com>
 */


add_shortcode('brighty_register_form', 'brighty_user_registration_form'); 

function brighty_user_registration_form(){
    
    include BRIGHTY_CORE_PLUGIN_DIR . '/shortcodes/register-form.php';

}


// on submit check if the data is submitted


/**
 * To validate WooCommerce registration form custom fields.
 */
function brighty_validate_reg_form_fields($validation_errors, $username, $password) {

    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $validation_errors->add('billing_first_name_error', __('First name is required!', 'brighty-core'));
    }

    if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
        $validation_errors->add('billing_last_name_error', __('Last name is required!.', 'brighty-core'));
    }


    if (isset($_POST['billing_address_1']) && empty($_POST['billing_address_1'])) {
        $validation_errors->add('billing_address_1_error', __('Please Enter Address', 'brighty-core'));
    }


    if (isset($_POST['billing_city']) && empty($_POST['billing_city'])) {
        $validation_errors->add('billing_city_error', __('Please Enter City', 'brighty-core'));
    }

    if (isset($_POST['billing_state']) && empty($_POST['billing_state'])) {
        $validation_errors->add('billing_state_error', __('Please Enter State', 'brighty-core'));
    }



    if (isset($_POST['billing_country']) && empty($_POST['billing_country'])) {
        $validation_errors->add('billing_country_error', __('Please Enter Country', 'brighty-core'));
    }

    if (isset($_POST['billing_pincode']) && empty($_POST['billing_pincode'])) {
        $validation_errors->add('billing_pincode_error', __('Please enter Pincode', 'brighty-core'));
    }

    
    return $validation_errors;

}

add_action('woocommerce_process_registration_errors', 'brighty_validate_reg_form_fields', 10, 3);

/**
 * Save the extra register fields.
 *
 * @param  int  $customer_id Current customer ID.
 *
 * @return void
 */
function brighty_save_registration_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		// WordPress default first name field.
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		// WooCommerce billing first name.
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

		update_user_meta( $customer_id, 'shipping_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}

	if ( isset( $_POST['billing_last_name'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		// WooCommerce billing last name.
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		update_user_meta( $customer_id, 'shipping_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        
    }

    if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
		update_user_meta( $customer_id, 'shipping_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }


    if ( isset( $_POST['billing_company'] ) ) {
		update_user_meta( $customer_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
		update_user_meta( $customer_id, 'shipping_company', sanitize_text_field( $_POST['billing_company'] ) );
    }

    if ( isset( $_POST['billing_address_1'] ) ) {
		update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
		update_user_meta( $customer_id, 'shipping_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
    }

    if ( isset( $_POST['billing_city'] ) ) {
		update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['billing_city'] ) );
		update_user_meta( $customer_id, 'shipping_city', sanitize_text_field( $_POST['billing_city'] ) );
    }

    if ( isset( $_POST['billing_state'] ) ) {
		update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['billing_state'] ) );
		update_user_meta( $customer_id, 'shipping_state', sanitize_text_field( $_POST['billing_state'] ) );
    }

    if ( isset( $_POST['billing_country'] ) ) {
		update_user_meta( $customer_id, 'billing_country', sanitize_text_field( $_POST['billing_country'] ) );
		update_user_meta( $customer_id, 'shipping_country', sanitize_text_field( $_POST['billing_country'] ) );
    }

    if ( isset( $_POST['billing_postcode'] ) ) {
		update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
		update_user_meta( $customer_id, 'shipping_postcode', sanitize_text_field( $_POST['billing_postcode'] ) );
    }

}

add_action( 'woocommerce_created_customer', 'brighty_save_registration_fields' );