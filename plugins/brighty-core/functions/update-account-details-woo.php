<?php
/**
 * @TODO Send email about old vs new detail to the user on email 
 */

add_action( 'woocommerce_save_account_details', 'brighty_save_account_details' );

function brighty_save_account_details( $user_id ) {
	if ( isset( $_POST['dob'] ) ) {
		update_user_meta( $user_id, 'dob', sanitize_text_field( $_POST['dob'] ) );
	}

    if ( isset( $_POST['billing_company'] ) ) {
		update_user_meta( $user_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
		update_user_meta( $user_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
	}


    if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}

    if ( isset( $_POST['gender'] ) ) {
		update_user_meta( $user_id, 'gender', sanitize_text_field( $_POST['gender'] ) );
	}

    wp_safe_redirect( wc_get_endpoint_url( 'profile' ) );


}

