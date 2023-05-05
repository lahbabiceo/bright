<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Adding Meta field in the meta container admin shop_order pages (used for 'Subaccounts Info' section on WC order email too: sfwc_add_subaccounts_info_to_order_email function from Pro)
 */
function sfwc_add_meta_box_content( $order ) { // Leave $order, otherwise getting error on checkout page while placing an order.

	
	global $post; // Keep it there, otherwise getting 'ID was called incorrectly' in emails when order is created from backend.
	
	
	if ( is_admin() ) {
		$order = new WC_Order($post->ID); // Admin side only otherwise emails won't get sent.
	}
	
	$customer_id = $order->get_user_id();
	
	// Retrieve user data for customer related to order.
	$userdata_customer = get_userdata( $customer_id );





	// Get 'Customer Display Name' from Options settings.
	$sfwc_options = (array) get_option('sfwc_options');

					
	// Avoid undefined $sfwc_option_display_name in case related setting has not been saved yet.
	$sfwc_option_display_name = ( isset( $sfwc_options['sfwc_option_display_name'] ) ) ? $sfwc_options['sfwc_option_display_name'] : 'username';





	// Get Account Level Type
	$customer_account_level = $order->get_meta('_sfwc_customer_account_level_type');
	
	
	// Retrieve the ID of the Manager related to the Customer.
	$customer_related_manager_id = $order->get_meta('_sfwc_customer_related_manager');
	
	// Retrieve user data for Manager.
	$userdata_manager = get_userdata( $customer_related_manager_id );



	/*
	var_dump($customer_related_manager_id);	
	echo '<br>';
	var_dump($customer_related_supervisor_id);
	echo '<br>';
	var_dump($order_placed_by);
	*/


	
	
	if ( $customer_id ) {
		// Get roles the user who made the order is part of (as an array).
		$role_user_current_order = $userdata_customer->roles;
	}

 
	/**
	 * Check if order has still a user id associated ($customer_id). 
	 * If not checked, in case a customer is deleted after he made an order they appear wrong values.
	 * 
	 * Also check if the user who made the order has role of Customer or Subscriber. Otherwise weird things might happen.
	 * E.g. If order was made by an Administrator (borderline case, but possible) wrong data is displayed within the meta box.
	 */
	if ( $customer_id && ( in_array( 'customer', $role_user_current_order ) || in_array( 'subscriber', $role_user_current_order ) ) ) {

		echo '<div style="background:#f5f5f5;padding:2px 8px;margin-top:12px;">';

		//Check 'Customer Display Name' in Subaccounts > Settings > Options and display it accordingly
		if ( ( $sfwc_option_display_name == 'full_name' ) && ( $userdata_customer->user_firstname || $userdata_customer->user_lastname ) ) {

			// Echo 'Full Name + Email' (if either First Name or Last Name has been set)
			echo '<p><strong>' . esc_html__( 'Customer', 'subaccounts-for-woocommerce' ) . ':</strong><br>' . esc_html( $userdata_customer->user_firstname ) . ' ' . esc_html( $userdata_customer->user_lastname ) . '<br>[' . esc_html( $userdata_customer->user_email ) . ']</p>';

		} elseif (($sfwc_option_display_name == 'company_name') && ($userdata_customer->billing_company)) {

			// Echo 'Company + Email' (if Company name has been set)
			echo '<p><strong>' . esc_html__( 'Customer', 'subaccounts-for-woocommerce' ) . ':</strong><br>' . esc_html( $userdata_customer->billing_company ) . '<br>[' . esc_html( $userdata_customer->user_email ) . ']</p>';

		} else {

			// Otherwise echo 'Username + Email'
			echo '<p><strong>' . esc_html__( 'Customer', 'subaccounts-for-woocommerce' ) . ':</strong><br>' . esc_html( $userdata_customer->user_login ) . '<br>[' . esc_html( $userdata_customer->user_email ) . ']</p>';
		}




		// Display Account Type
		echo '<p><strong>' . esc_html__( 'Account Type', 'subaccounts-for-woocommerce' ) . ':</strong> ';


		if ( $customer_account_level == 'supervisor' ) {

			echo esc_html__('Supervisor', 'subaccounts-for-woocommerce');

			if ( ! sfwc_is_plugin_active( 'woocommerce.php' ) ) {

				echo '<div style="background:#fff4bd; padding:5px; margin-bottom:.5em;">';
				
				$sup_deactivated_warning = '<strong>' . esc_html__( 'WARNING:', 'subaccounts-for-woocommerce' ) . '</strong>';
				$sup_deactivated_supervisor = '<strong><em>' . esc_html__( 'Supervisor', 'subaccounts-for-woocommerce' ) . '</em></strong>';
				$sup_deactivated_addon = '<strong><em>' . esc_html__( 'Supervisor Add-on', 'subaccounts-for-woocommerce' ) . '</em></strong>';
				
				printf(
					esc_html__( '%1$s This User\'s Account Type is set as %2$s, but the %3$s is either uninstalled or not active. You may want to install and activate the add-on or change the User\'s Account Type.', 'subaccounts-for-woocommerce' ), 
					$sup_deactivated_warning,
					$sup_deactivated_supervisor,
					$sup_deactivated_addon
				);			
			
				echo '</div>';
			}

		} elseif ( $customer_account_level == 'manager' ) {

			echo esc_html__( 'Manager', 'subaccounts-for-woocommerce' );

		} else {

			echo esc_html__( 'Default', 'subaccounts-for-woocommerce' );

		}

		echo '</p>';
		
		
		
		// Display Order Placed By
		echo '<p style="background: #e4e4e4; border-radius: 3px; padding: 8px;"><strong>' . esc_html__( 'Order placed by', 'subaccounts-for-woocommerce' ) . ':</strong><br>';
		
		
		// Check if Pro version is active.
		if ( ! sfwc_is_plugin_active( 'subaccounts-for-woocommerce-pro.php' ) ) {
					
			echo '<a style="font-weight: 600; color: #e67d23; background: #fff; padding: 5px; display: block; margin-top: 5px; border-radius: 3px; width: 100%; box-sizing: border-box; text-align: center;" href="'
					. admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ) . '">'
					. esc_html__( 'Upgrade to Subaccounts Pro', 'subaccounts-for-woocommerce' ) . '</a>';
		}


		do_action('admin_order_page_before_manager_info', $order, $sfwc_option_display_name, $userdata_customer, $userdata_manager );
		

		echo '</p></div>';





		// Display Related Supervisor or Manager Account (if any)
		if ( $customer_account_level == 'supervisor' ) {

			//echo '<p><strong>' . __('Manager Account', 'subaccounts-for-woocommerce') . ':</strong><br> ' . __('None', 'subaccounts-for-woocommerce') . wc_help_tip( esc_attr__('Supervisor account types cannot have Managers above them', 'subaccounts-for-woocommerce') ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Manager Account', 'subaccounts-for-woocommerce' ) . ':</strong><br> ' . esc_html__( 'None', 'subaccounts-for-woocommerce' ) . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Supervisor account types cannot have Managers above them.', 'subaccounts-for-woocommerce' ) . '"></span></p>';

			#if ( is_plugin_active( 'sfwc-supervisor-addon/sfwc-supervisor-addon.php' ) ) {
			if ( sfwc_is_plugin_active( 'sfwc-supervisor-addon.php' ) ) {
				//echo '<p><strong>' . __('Supervisor Account', 'subaccounts-for-woocommerce') . ':</strong><br> ' . __('None', 'subaccounts-for-woocommerce') . wc_help_tip( esc_attr__('Supervisor account types cannot have Supervisors above them', 'subaccounts-for-woocommerce') ) . '</p>';
				echo '<p><strong>' . esc_html__( 'Supervisor Account', 'subaccounts-for-woocommerce' ) . ':</strong><br> ' . esc_html__( 'None', 'subaccounts-for-woocommerce' ) . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Supervisor account types cannot have Supervisors above them.', 'subaccounts-for-woocommerce' ) . '"></span></p>';
			}
		} elseif ( $customer_account_level == 'manager' ) {


			//echo '<p><strong>' . __('Manager Account', 'subaccounts-for-woocommerce') . ':</strong><br> ' . __('None', 'subaccounts-for-woocommerce') . wc_help_tip( esc_attr__('Manager account types cannot have Managers above them', 'subaccounts-for-woocommerce') ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Manager Account', 'subaccounts-for-woocommerce' ) . ':</strong><br> ' . esc_html__( 'None', 'subaccounts-for-woocommerce' ) . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Manager account types cannot have Managers above them.', 'subaccounts-for-woocommerce' ) . '"></span></p>';


			do_action('order_page_after_manager_info_when_customer_is_manager', $order, $customer_account_level, $sfwc_option_display_name );


		} elseif ( ( $customer_account_level !== 'supervisor' ) && ( $customer_account_level !== 'manager' ) ) {

			echo '<p style="padding-left:20px;"><span style="-moz-transform: scale(-1, 1); -o-transform: scale(-1, 1); -webkit-transform: scale(-1, 1); transform: scale(-1, 1);" class="dashicons dashicons-editor-break"></span><strong>' . esc_html__('Manager Account', 'subaccounts-for-woocommerce') . ':</strong><br>';

			

			if ( $customer_related_manager_id && ($customer_related_manager_id !== 'not_set') ) {
				
				//$userdata_manager = get_userdata( $customer_related_manager_id );

				// foreach ( $user_query_manager->get_results() as $user ) {

					//Check 'Customer Display Name' in Subaccounts > Settings > Options and display it accordingly
					if ( ( $sfwc_option_display_name == 'full_name' ) && ( get_userdata($userdata_manager->ID)->user_firstname || get_userdata($userdata_manager->ID)->user_lastname ) ) {

						// Echo 'Full Name + Email' (if either First Name or Last Name has been set)
						printf( 'ID: %1$s - %2$s %3$s <br>[%4$s]</p>', esc_html( $userdata_manager->ID ), esc_html( $userdata_manager->user_firstname ), esc_html( $userdata_manager->user_lastname ), esc_html( $userdata_manager->user_email ) );
						

					} elseif ( ( $sfwc_option_display_name == 'company_name' ) && ( get_userdata($userdata_manager->ID)->billing_company ) ) {

						// Echo 'Company + Email' (if Company name has been set)
						printf( 'ID: %1$s - %2$s <br>[%3$s]</p>', esc_html( $userdata_manager->ID ), esc_html( $userdata_manager->billing_company ), esc_html( $userdata_manager->user_email ) );
						

					} else {

						// Otherwise echo 'Username + Email'
						printf( 'ID: %1$s - %2$s <br>[%3$s]</p>', esc_html( $userdata_manager->ID ), esc_html( $userdata_manager->user_login ), esc_html( $userdata_manager->user_email ) );
						
					}
				// }

			} else {
				//echo __('Not set', 'subaccounts-for-woocommerce') . wc_help_tip( esc_attr__('No Manager has been set yet', 'subaccounts-for-woocommerce') ) . '</p>';
				echo esc_html__( 'Not set', 'subaccounts-for-woocommerce' ) . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'No Manager has been set yet.', 'subaccounts-for-woocommerce' ) . '"></span></p>';
			}


			( isset( $userdata_manager ) ) ? $userdata_manager = $userdata_manager : $userdata_manager = ''; // Prevent: Undefined variable: $userdata_manager (wp_debug enabled) // Check if still needed.
			do_action('order_page_after_manager_info_when_customer_is_default', $order, $customer_account_level, $sfwc_option_display_name );

		}

	} else {
		echo '<p>' . esc_html__( 'No data available for this order.', 'subaccounts-for-woocommerce' ) . '</p>';
	}
}