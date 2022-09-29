<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}




/**
 * Frontend style
 */
function sfwc_enqueue_frontend_style() {
	
    // $plugin_url = plugin_dir_url( __FILE__ );
    // wp_enqueue_style( 'sfwc_frontend_style', $plugin_url . 'assets/css/style.css' );
	
	// Check if logged in user has role of customer or subscriber. No need to enqueue it everywhere.
	if ( is_user_logged_in() && ( current_user_can( 'customer' ) || current_user_can( 'subscriber' ) ) ) {
		wp_enqueue_style( 'sfwc_frontend_style', WP_PLUGIN_URL . '/subaccounts-for-woocommerce/assets/css/style.css' );
	}
}
add_action('wp_enqueue_scripts', 'sfwc_enqueue_frontend_style');




/**
 * Set Manager / Supervisor Cookie.
 * 
 * Set 'is_manager' cookie if customer with 'Manager' Account Level Type does login.
 * Set 'is_supervisor' cookie if customer with 'Supervisor' Account Level Type does login.
 */
function sfwc_set_parent_account_cookie() {

	// Check if logged in user has role of customer or subscriber
	if ( is_user_logged_in() && ( current_user_can('customer') || current_user_can('subscriber') ) ) {
		
		
		
		// Get Account Level Type
		$user_id = get_current_user_id();
		$key = 'sfwc_account_level_type';
		$single = true;
		$account_level_type = get_user_meta($user_id, $key, $single);
	
	

		// Check if user is parent (Manager).
		if ( $account_level_type == 'manager' ) {

			// If not already build and set 'is_manager' cookie.
			if ( ! isset( $_COOKIE['is_manager'] ) ) {

				// Assign variable to cookie name: wordpress_logged_in_ + hashed site URL.
				$finalUserCookieName = 'wordpress_logged_in_' . COOKIEHASH;

				if ( isset( $_COOKIE[$finalUserCookieName] ) ) {
					
					// Sanitize cookie value.
					$finalUserCookieNameValue = sanitize_text_field( $_COOKIE[$finalUserCookieName] );
					
					// Generate random code for transient name.
					$generate_random_code = random_bytes( 5 );
					// Convert binary data into hexadecimal.
					$output_random_code = bin2hex( $generate_random_code );
					
					// Set a trasient to check 'is_manager' cookie value against (they must be equal).
					set_transient( 'sfwc_is_or_was_manager_' . $output_random_code, $finalUserCookieNameValue, 3600 );

					// Set 'is_manager' cookie name and its value (same as 'wordpress_logged_in_...' value).
					setcookie('is_manager', $finalUserCookieNameValue, [
						'expires' => '',
						'path' => COOKIEPATH,
						'domain' => COOKIE_DOMAIN,
						'secure' => is_ssl(),
						'httponly' => true,
						'samesite' => 'Strict',
					]);


					// Provide initial value to cookie otherwise frontend switcher will be shown only after page refresh.
					$_COOKIE['is_manager'] = get_transient( 'sfwc_is_or_was_manager_' . $output_random_code );
				}
			}
		}
		
		
		// Check if user is parent (Supervisor).
		if ( $account_level_type == 'supervisor'  ) {

			// If is Supervisor build 'is_supervisor' cookie
			if ( ! isset($_COOKIE['is_supervisor'] ) ) {

				// Assign variable to cookie: wordpress_logged_in_ + hashed site URL
				$finalUserCookieName = 'wordpress_logged_in_' . COOKIEHASH;
				
				if ( isset( $_COOKIE[$finalUserCookieName] ) ) {
					
					// Sanitize cookie value.
					$finalUserCookieNameValue = sanitize_text_field( $_COOKIE[$finalUserCookieName] );


					// Generate random code for transient name
					$generate_random_code = random_bytes( 5 );
					// Convert binary data into hexadecimal
					$output_random_code = bin2hex( $generate_random_code );

					// Set transient
					set_transient('sfwc_is_or_was_supervisor_' . $output_random_code, $finalUserCookieNameValue, 3600);

					// Set 'is_supervisor' cookie name and its value (same as 'wordpress_logged_in_...' value)
					setcookie('is_supervisor', $finalUserCookieNameValue, [
						'expires' => '',
						'path' => COOKIEPATH,
						'domain' => COOKIE_DOMAIN,
						'secure' => is_ssl(),
						'httponly' => true,
						'samesite' => 'Strict',
					]);

					// Provide initial value to cookie otherwise frontend switcher will be shown only after page refresh
					$_COOKIE['is_supervisor'] = get_transient('sfwc_is_or_was_supervisor_' . $output_random_code);
				}
			}
		}
		
		
	}
}
add_action('init', 'sfwc_set_parent_account_cookie');




// Destroy is_manager / is_supervisor cookie and transient on logout
function sfwc_destroy_parent_account_cookie_on_logout() {

	global $wpdb;
	

    if ( isset( $_COOKIE['is_manager'] ) ) {


		$cookie_value_manager = sanitize_text_field( $_COOKIE['is_manager'] );

		$current_transient_manager = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_manager_%' AND option_value LIKE '$cookie_value_manager' " );
		
		$curr_transient_name_manager = str_replace( '_transient_', '', $current_transient_manager );		// Or: $curr_transient_name_manager = substr( $current_transient_manager, 11);

		delete_transient( $curr_transient_name_manager );
		


		// Destroy is_manager cookie
		setcookie('is_manager', null, -1, COOKIEPATH);
    }
	


	
	if ( isset( $_COOKIE['is_supervisor'] ) ) {

		
		$cookie_value_supervisor = sanitize_text_field( $_COOKIE['is_supervisor'] );

		$current_transient_supervisor = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_supervisor_%' AND option_value LIKE '$cookie_value_supervisor' " );
		
		$curr_transient_name_supervisor = str_replace( '_transient_', '', $current_transient_supervisor );		// Or: $curr_transient_name_supervisor = substr( $current_transient_supervisor, 11);

		delete_transient( $curr_transient_name_supervisor );
		

		
		// Destroy is_supervisor cookie
		setcookie('is_supervisor', null, -1, COOKIEPATH);

	}
}
add_action('wp_logout', 'sfwc_destroy_parent_account_cookie_on_logout');




/**
 * Display User Account Switcher.
 * 
 * Echo User Account Switcher on WooCommerce My Account page.
 */
function sfwc_action_woocommerce_account_dashboard() {

    // Retrieve the current user object
    $current_user = wp_get_current_user();
	
	// Get ID of currently logged in user.
	$user_id = get_current_user_id();
	
	// Get children (array) of currently logged in user.																		
	$children_ids = get_user_meta( $user_id, 'sfwc_children', true ); // 3rd must be: true, otherwise will turn it into a two-dimensional array.

	// Get Account Level Type of currently logged in user (Superviore | Manager | Default).
	$account_level_type = get_user_meta( $user_id, 'sfwc_account_level_type', true );


	/**
	 * Get plugin settings
	 *
	 * Check if values have been set first to prevent 'undefined' 
	 */

    // Get 'Appearance' settings.
    $sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');
	
		// Get Pane Background Color.
		$sfwc_switcher_pane_bg_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_bg_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_bg_color'] : '#def6ff';
	
		// Get Pane Headline Color.
		$sfwc_switcher_pane_headline_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_headline_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_headline_color'] : '#0088cc';

		// Get Pane Text Color.
		$sfwc_switcher_pane_text_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_text_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_text_color'] : '#3b3b3b';
	
		// Get Pane Select Button Background Color.
		$sfwc_switcher_pane_select_bg_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_select_bg_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_select_bg_color'] : '#0088cc';

		// Get Pane Select Button Text Color.
		$sfwc_switcher_pane_select_text_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_select_text_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_select_text_color'] : '#ffffff';


    // Get 'Options' settings
    $sfwc_options = (array) get_option('sfwc_options');

		// Get Customer Display Name.
		$sfwc_option_display_name = ( isset( $sfwc_options['sfwc_option_display_name'] ) ) ? $sfwc_options['sfwc_option_display_name'] : 'username';



	// Check if logged in user has role of customer or subscriber
	if ( is_user_logged_in() && ( current_user_can('customer') || current_user_can('subscriber') ) ) {



		/**
		 * In the rare case that frontend switcher is not displayed (cookie or transient has not been properly set or expired) show 'Login Again' button.
		 *
		 * Following paragraph is shown conditionally with jQuery (see below function: 'sfwc_force_switcher_display') in case the switcher is not visible.
		 */
		if ( ! empty( $children_ids ) ) {

			echo '<p id="sfwc-session-expired" class="sfwc-reload-page-text">' . esc_html__('Your session has most likely timed out. You can try reloading the page or logging in again.', 'subaccounts-for-woocommerce');
			echo '<a id="sfwc-login-again" href="' . wp_logout_url( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) . '">' . esc_html__('Login Again', 'subaccounts-for-woocommerce') . '</a></p>';
		}




		// Get all users with user meta 'sfwc_account_level_type' == 'manager' and filter only the one which has 'sfwc_children' user_meta containing the child ID who made the order
		$args_manager = array(
			//'role'    => 'customer',
			'role__in' => ['customer', 'subscriber'],
			'exclude' => $user_id, // Exclude ID of customer who made currently displayed order
			'orderby' => 'ID',
			'order' => 'ASC',
			'meta_key' => 'sfwc_account_level_type',
			'meta_value' => 'manager',
			'meta_query' => array(
				array(
					'key' => 'sfwc_children',
					'value' => '"'.$user_id.'"',
					'compare' => 'LIKE',
				),
			),
		);


		// The User Query
		$user_query_manager = new WP_User_Query( $args_manager );




		
		if ( isset( $_COOKIE['is_supervisor'] ) ) {
			
			
				/**
				 * Get sfwc_is_or_was_supervisor_% (transient name randomly postfixed) transient value from is_supervisor cookie value.
				 *
				 * The implicit logic here is to also check that both a transient and a cookie (both tied to currently logged in customer) exist 
				 * and they match.
				 */
			
				global $wpdb;
				
				$cookie_value_supervisor = sanitize_text_field( $_COOKIE['is_supervisor'] );
				
				// Get transient name (randomly postfixed) stored in DB by transient value.
				$current_transient_supervisor = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_supervisor_%' AND option_value LIKE '$cookie_value_supervisor' " );
				
				// WordPress will automatically prefix the transient name with "_transient_"
				// So it is necessary to strip the prefix out in order to get the "real" transient name.
				$curr_transient_name_supervisor = str_replace( '_transient_', '', $current_transient_supervisor );		// Or: $curr_transient_name_supervisor = substr( $current_transient_supervisor, 11);
				
				// Get transient value by transient name.
				$is_or_was_supervisor = get_transient( $curr_transient_name_supervisor );

		}


		if ( isset( $_COOKIE['is_manager'] ) ) {
			
			
				/**
				 * Get sfwc_is_or_was_manager_% (transient name randomly postfixed) transient value from is_manager cookie value.
				 *
				 * The implicit logic here is to also check that both a transient and a cookie (both tied to currently logged in customer) exist 
				 * and they match.
				 */
			
				global $wpdb;
				
				$cookie_value_manager = sanitize_text_field( $_COOKIE['is_manager'] );

				// Get transient name (randomly postfixed) stored in DB by transient value.
				$current_transient_manager = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_manager_%' AND option_value LIKE '$cookie_value_manager' " );
				
				// WordPress will automatically prefix the transient name with "_transient_"
				// So it is necessary to strip the prefix out in order to get the "real" transient name.
				$curr_transient_name_manager = str_replace( '_transient_', '', $current_transient_manager );		// Or: $curr_transient_name_manager = substr( $current_transient_manager, 11);
				
				// Get transient value by transient name.
				$is_or_was_manager = get_transient( $curr_transient_name_manager );

		}


		/**
		 * User Switcher.
		 */
		if ( isset( $cookie_value_manager ) && ( $cookie_value_manager === $is_or_was_manager ) && ! isset( $cookie_value_supervisor ) ) {
			
			
			/**
			 * Echo Subaccounts Switcher HTML and populate it
			 */

			echo '<div id="sfwc-user-switcher-pane" style="background-color:' . esc_attr( $sfwc_switcher_pane_bg_color ) . ';">';
			echo '<h3 style="color:' . esc_attr( $sfwc_switcher_pane_headline_color ) . ';">' . esc_html__('You are currently logged in as:', 'subaccounts-for-woocommerce') . '</h3>';

			// Check 'Customer Display Name' in Subaccounts > Settings > Options and display it accordingly
			if ( ( $sfwc_option_display_name == 'full_name' ) && ( $current_user->user_firstname || $current_user->user_lastname ) ) {

				// Echo 'Full Name + Email' (if either First Name or Last Name has been set)
				echo '<p style="color:' . esc_attr( $sfwc_switcher_pane_text_color ) . ';"><strong>' . esc_html__( 'Full Name: ', 'subaccounts-for-woocommerce' ) . '</strong>' . esc_html( $current_user->user_firstname ) . ' ' . esc_html( $current_user->user_lastname ) . ' (' . esc_html( $current_user->user_email ) . ')</p>';

			} elseif ( ( $sfwc_option_display_name == 'company_name' ) && ( $current_user->billing_company ) ) {

				// Echo 'Company + Email' (if Company name has been set)
				echo '<p style="color:' . esc_attr( $sfwc_switcher_pane_text_color ) . ';"><strong>' . esc_html__( 'Company: ', 'subaccounts-for-woocommerce' ) . '</strong>' . esc_html( $current_user->billing_company ) . ' (' . esc_html( $current_user->user_email ) . ')</p>';

			} else {

				// Otherwise echo 'Username + Email'
				echo '<p style="color:' . esc_attr( $sfwc_switcher_pane_text_color ) . ';"><strong>' . esc_html__( 'Username: ', 'subaccounts-for-woocommerce' ) . '</strong>' . esc_html( $current_user->user_login ) . ' (' . esc_html( $current_user->user_email ) . ')</p>';
			}
			

			?>

			<form method="post">
				<select id="sfwc_frontend_children" name="sfwc_frontend_children" onchange="this.form.submit();" style="background-color:<?php echo esc_attr( $sfwc_switcher_pane_select_bg_color ); ?>; color:<?php echo esc_attr( $sfwc_switcher_pane_select_text_color ); ?>;">
					<option value="" selected="selected" disabled><?php echo esc_html__( 'Select Account', 'subaccounts-for-woocommerce' ); ?>&nbsp; &#8644;</option>

					<?php
					if ( empty( $children_ids ) ) {

						// User Loop
						if ( ! empty( $user_query_manager->get_results() ) ) {
							foreach ( $user_query_manager->get_results() as $user ) {
							?>
								<option style="font-weight:bold;" value="<?php echo esc_attr($user->ID); ?>">&#129044; <?php echo esc_html__('Back to Manager', 'subaccounts-for-woocommerce'); ?></option>
							<?php
							}
						}
					}
		
					
					
					if ( ! empty( $children_ids ) ) {

						foreach ( $children_ids as $key => $value ) {
							
							// Prevent empty option values within the frontend dropdown user switcher 
							// in case a user has been deleted (but still present within 'sfwc_children' meta of an ex parent account).
							$user_exists = get_userdata( $value );
							if ( $user_exists !== false ) {

								//Check 'Customer Display Name' in Subaccounts > Settings > Options and display it accordingly
								if ( ( $sfwc_option_display_name == 'full_name' ) && ( get_userdata($value)->user_firstname || get_userdata($value)->user_lastname ) ) {

									// Echo 'Full Name + Email' (if either First Name or Last Name has been set)
									echo "<option value=" . esc_attr( $value ) . ">" . esc_html( get_userdata($value)->user_firstname ) . " " . esc_html( get_userdata($value)->user_lastname ) . " - [" . esc_html( get_userdata($value)->user_email ) . "]</option>";

								} elseif ( ( $sfwc_option_display_name == 'company_name' ) && ( get_userdata($value)->billing_company ) ) {

									// Echo 'Company + Email' (if Company name has been set)
									echo "<option value=" . esc_attr( $value ) . ">" . esc_html( get_userdata($value)->billing_company ) . " - [" . esc_html( get_userdata($value)->user_email ) . "]</option>";

								} else {

									// Otherwise echo 'Username + Email'
									echo "<option value=" . esc_attr( $value ) . ">" . esc_html( get_userdata($value)->user_login ) . " - [" . esc_html( get_userdata($value)->user_email ) . "]</option>";
								}
							}
						}
					}
					?>
				</select>
				<input name="setc" value="submit" type="submit" style="display:none;">
			</form>

		<?php
		
			echo '</div>';
		}

		
		/* For debug */
		// var_dump($children_ids);
	}
}
add_action('woocommerce_before_account_navigation', 'sfwc_action_woocommerce_account_dashboard');




/**
 * User Account Switcher: Validation and Authentication Cookies Installation.
 *
 * On WooCommerce My Account page, when selecting a subaccount or a parent account from the dropdwon list, set authentication cookies for the selected user.
 */
function sfwc_set_current_user_cookies() {
	
	
	if ( isset( $_COOKIE['is_supervisor'] ) ) {
		
			global $wpdb;
			
			$cookie_value_supervisor = sanitize_text_field( $_COOKIE['is_supervisor'] );

			$current_transient_supervisor = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_supervisor_%' AND option_value LIKE '$cookie_value_supervisor' " );
			
			$curr_transient_name_supervisor = str_replace( '_transient_', '', $current_transient_supervisor );		// Or: $curr_transient_name_supervisor = substr( $current_transient_supervisor, 11);
			
			$is_or_was_supervisor = get_transient( $curr_transient_name_supervisor );

	}
			

		
	if ( isset( $_COOKIE['is_manager'] ) ) {

			global $wpdb;
			
			$cookie_value_manager = sanitize_text_field( $_COOKIE['is_manager'] );

			$current_transient_manager = $wpdb->get_var( " SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient_sfwc_is_or_was_manager_%' AND option_value LIKE '$cookie_value_manager' " );
			
			$curr_transient_name_manager = str_replace( '_transient_', '', $current_transient_manager );		// Or: $curr_transient_name_manager = substr( $current_transient_manager, 11);
			
			$is_or_was_manager = get_transient( $curr_transient_name_manager );
	}


	// Get ID of currently logged in user.
	$current_user_id = get_current_user_id();
	
	// Get Account Level Type of currently logged in user (Superviore | Manager | Default).
	$account_level_type = get_user_meta( $current_user_id, 'sfwc_account_level_type', true );

	// Get children (array) of currently logged in user.																				## A SCENDERE ↓ ##		OTTIENI LISTA SUBACCOUNT RELATIVI A UTENTE ATTUALMENTE LOGGATO
	// 3rd must be: true, otherwise will turn it into a two-dimensional array and validation won't work															(VALE SIA PER SUPERVISOR CHE MANAGER)
	$children_ids = get_user_meta( $current_user_id, 'sfwc_children', true );







	/**
	 * Create an array of IDs of 'Default' users tied to Managers which are tied to Supervisors											## A SCENDERE ↓ ##
	 * to create list of whitelisted/validated Deafult users a Supervisor can directly switch to (without passing from a Manger first).
	 *
	 * Necessary in order to switch directly from Supervisor to (whitelisted/validated) Default users.
	 */

	$children_of_manager = array();

	// 
	// So basically getting Default users tied to Supervisors
	if ( ! empty( $children_ids ) ) {
		foreach ( $children_ids as $children_id ) {
			
			
			$single_array = get_user_meta( $children_id, 'sfwc_children', true );
			//$children_of_manager[] = $single_value;
			
			if ( ! empty( $single_array ) ) {
				
				foreach ( $single_array as $single_value ) {
					$children_of_manager[] = $single_value;
				}

			}
			
		}
	}
	// var_dump($children_ids);
	// var_dump($children_of_manager);
	
	
	
	
	

	/**
	 * Get the Supervisor of currently logged in Manager.																				 ## A SALIRE ↑ ##		OTTIENI SUPERVISOR RELATIVO A MANAGER ATTUALMENTE LOGGATO
	 *
	 * By querying all users with user meta 'sfwc_account_level_type' == 'supervisor' 
	 * and filtering only the one which has 'children' user_meta containing the child ID of currently logged in Manager.
	 */

	$args_supervisor = array(
		//'role'    => 'customer',
		'role__in' => ['customer', 'subscriber'],
		'orderby' => 'ID',
		'order' => 'ASC',
		'meta_key' => 'sfwc_account_level_type',
		'meta_value' => 'supervisor',
		'meta_query' => array(
			array(
				'key' => 'sfwc_children',
				'value' => '"'.$current_user_id.'"',
				'compare' => 'LIKE',
			),
		),
	);


	// The User Query
	$user_query_supervisor = new WP_User_Query( $args_supervisor );



	// User Loop
	if ( ! empty( $user_query_supervisor->get_results() ) ) {
		foreach ( $user_query_supervisor->get_results() as $user ) {

			$supervisor_id = $user->ID;
		}
	}




	/**
	 * Get the Manager of currently logged in Default user.																					## A SALIRE ↑ ##		OTTIENI MANAGER RELATIVO A DEFAULT USER ATTUALMENTE LOGGATO
	 *
	 * By querying all users with user meta 'sfwc_account_level_type' == 'manager' 
	 * and filtering only the one which has 'children' user_meta containing the child ID of currently logged in Default user.
	 */
	 
	$args_manager = array(
		//'role'    => 'customer',
		'role__in' => ['customer', 'subscriber'],
		'orderby' => 'ID',
		'order' => 'ASC',
		'meta_key' => 'sfwc_account_level_type',
		'meta_value' => 'manager',
		'meta_query' => array(
			array(
				'key' => 'sfwc_children',
				'value' => '"'.$current_user_id.'"',
				'compare' => 'LIKE',
			),
		),
	);


	// The User Query
	$user_query_manager = new WP_User_Query( $args_manager );






	// User Loop
	if ( ! empty( $user_query_manager->get_results() ) ) {
		foreach ( $user_query_manager->get_results() as $user ) {

			$manager_id = $user->ID;
		}
	}





	/**
	 * Validation before account switch.
	 */
	if ( isset( $_POST['sfwc_frontend_children'] ) ) {

		$selected = sanitize_text_field( $_POST['sfwc_frontend_children'] );


		/**
		 * Caveat for the team: "a form input value is always a string", so is_int is not an option here.
		 * See: https://www.php.net/manual/en/function.is-int.php
		 *
		 * Check if $selected (numeric string) is a positive number.
		 *
		 * 3			true
		 * 03			false
		 * 3.5			false
		 * 3,5			false
		 * +3			false
		 * -3			false
		 * 1337e0		false
		 */
		if ( is_numeric( $selected ) && $selected >= 1 && preg_match( '/^[1-9][0-9]*$/', $selected ) ) {


			/**
			 * Check if logged in user is Supervisor.
			 *
			 * In this case this should be enough:
			 * if ( is_user_logged_in() && $account_level_type == 'supervisor' ) {...}
			 *
			 * Anyway...
			 */
			if ( ( is_user_logged_in() && $account_level_type == 'supervisor' ) && ( isset( $cookie_value_supervisor ) && ( $cookie_value_supervisor === $is_or_was_supervisor ) ) ) {

				/**
				 * Check if selected user is a subaccount of currently logged Supervisor.
				 *
				 * Or, in case Supervisor has switched to a Manager, check if selected user is a sub-acount of currently logged Mangaer (tied to the initially logged Supervisor).
				 */
				if ( in_array( $selected, $children_ids, true ) || in_array( $selected, $children_of_manager, true ) ) {

					// Clears the cart session when called.
					wc_empty_cart(); // Necessary in order for the cart to be auto-populated with correct data after the switch.
					
					// Removes all of the cookies associated with authentication.
					wp_clear_auth_cookie();
										
					wp_set_current_user( $selected );
					wp_set_auth_cookie( $selected );
										
					// Fix cart not populating after switch from user with empty cart to user with data in cart.
					wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );

					//wc_setcookie( 'woocommerce_items_in_cart', 1 );
					//do_action( 'woocommerce_set_cart_cookies', true );

				} else {

					wc_add_notice( esc_html__( 'You are not authorized to access the chosen account.', 'subaccounts-for-woocommerce' ), 'error' );
				}

			}
			/**
			 * Check if logged in user is Manager.
			 */
			elseif ( is_user_logged_in() && $account_level_type == 'manager' ) {

				/**
				 * Check if currently logged in 'Manager' has come to its account after switching from 'Supervisor'.
				 *
				 * Checking this by verifying if 'is_supervisor' cookie is set on its browser.
				 */
				if ( isset( $cookie_value_supervisor ) && ( $cookie_value_supervisor === $is_or_was_supervisor ) ) {

					if ( ( ! empty( $children_ids ) && in_array( $selected, $children_ids, true ) ) || $selected == $supervisor_id ) {

						// Clears the cart session when called.
						wc_empty_cart(); // Necessary in order for the cart to be auto-populated with correct data after the switch.
						
						// Removes all of the cookies associated with authentication.
						wp_clear_auth_cookie();
											
						wp_set_current_user( $selected );
						wp_set_auth_cookie( $selected );
											
						// Fix cart not populating after switch from user with empty cart to user with data in cart.
						wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );

						//wc_setcookie( 'woocommerce_items_in_cart', 1 );
						//do_action( 'woocommerce_set_cart_cookies', true );

					} else {

						wc_add_notice( esc_html__( 'You are not authorized to access the chosen account.', 'subaccounts-for-woocommerce' ), 'error' );
					}
				}
				/**
				 * Otherwise it means that 'Manager' has logged in from its own account (without switching from Supervisor).
				 *
				 * Therefore do not provide him access to its 'Supervisor' by removing: $selected == $supervisor_id.
				 */				
				elseif ( ! isset( $cookie_value_supervisor ) || ( $cookie_value_supervisor !== $is_or_was_supervisor ) ) {
					
					// Make sure 'is_manager' cookie is set and its value is equal to transient stored in DB
					if ( isset( $cookie_value_manager ) && ( $cookie_value_manager === $is_or_was_manager ) ) {

						if ( ! empty( $children_ids ) && in_array( $selected, $children_ids, true ) ) {

							// Clears the cart session when called.
							wc_empty_cart(); // Necessary in order for the cart to be auto-populated with correct data after the switch.
							
							// Removes all of the cookies associated with authentication.
							wp_clear_auth_cookie();
												
							wp_set_current_user( $selected );
							wp_set_auth_cookie( $selected );
												
							// Fix cart not populating after switch from user with empty cart to user with data in cart.
							wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );

							//wc_setcookie( 'woocommerce_items_in_cart', 1 );
							//do_action( 'woocommerce_set_cart_cookies', true );

						} else {

							wc_add_notice( esc_html__( 'You are not authorized to access the chosen account.', 'subaccounts-for-woocommerce' ), 'error' );
						}

					}
				}
			}
			/**
			 * Check if currently logged in 'Default' user has come to its account after switching from 'Supervisor' or 'Manager'.
			 *
			 * Checking this by verifying if either 'is_supervisor' or 'is_manager' cookie is set on its browser.
			 */
			elseif (
						( is_user_logged_in() && $account_level_type !== 'supervisor' || $account_level_type !== 'manager' ) 
						&& ( ( isset( $cookie_value_supervisor ) && ($cookie_value_supervisor === $is_or_was_supervisor ) ) || ( isset( $cookie_value_manager ) && ( $cookie_value_manager === $is_or_was_manager ) ) ) 
					) {


				// Get Supervisor's ID from Default user's Manager ID
				$args_supervisor = array(
					//'role'    => 'customer',
					'role__in' => ['customer', 'subscriber'],
					//'exclude'  => $user_id,	// Exclude ID of customer who made currently displayed order
					'orderby' => 'ID',
					'order' => 'ASC',
					'meta_key' => 'sfwc_account_level_type',
					'meta_value' => 'supervisor',
					'meta_query' => array(
						array(
							'key' => 'sfwc_children',
							'value' => '"'.$manager_id.'"',
							'compare' => 'LIKE',
						),
					),
				);


				// The User Query
				$user_query_supervisor = new WP_User_Query( $args_supervisor );



				// User Loop
				if ( ! empty( $user_query_supervisor->get_results() ) ) {
					foreach ( $user_query_supervisor->get_results() as $user ) {

						$supervisor_id = $user->ID;
					}
				}


				if ( ( ! empty( $children_ids ) && in_array( $selected, $children_ids, true ) ) || isset( $supervisor_id ) && $supervisor_id == $selected || isset( $manager_id ) && $manager_id == $selected ) {

					// Clears the cart session when called.
					wc_empty_cart(); // Necessary in order for the cart to be auto-populated with correct data after the switch.
					
					// Removes all of the cookies associated with authentication.
					wp_clear_auth_cookie();
										
					wp_set_current_user( $selected );
					wp_set_auth_cookie( $selected );
										
					// Fix cart not populating after switch from user with empty cart to user with data in cart.
					wc_setcookie( 'woocommerce_cart_hash', md5( json_encode( WC()->cart->get_cart() ) ) );

					//wc_setcookie( 'woocommerce_items_in_cart', 1 );
					//do_action( 'woocommerce_set_cart_cookies', true );

				} else {
					
					wc_add_notice( esc_html__( 'You are not authorized to access the chosen account.', 'subaccounts-for-woocommerce' ), 'error' );

				}
			} else {

				wc_add_notice( esc_html__( 'You are not authorized to access the chosen account.', 'subaccounts-for-woocommerce' ), 'error' );

			}
		}

		// If $selected is not a positive integer.
		else {
			
			wc_add_notice( esc_html__( 'Incorrect data sent.', 'subaccounts-for-woocommerce' ), 'error' );
			
		}
	}
}
add_action('wp', 'sfwc_set_current_user_cookies');




/**
 * Show 'Login Again' button.
 *
 * Shown in case the frontend switcher is not displayed - due to cookie or transient not been properly set or expired.
 */
function sfwc_force_switcher_display() {
	
	// Get ID of currently logged in user.
	$current_user_id = get_current_user_id();
	
	// Get Account Level Type of currently logged in user (Superviore | Manager | Default).
	$account_level_type = get_user_meta( $current_user_id, 'sfwc_account_level_type', true );
	

	
	// Check if logged in user has role of customer or subscriber
	if ( is_user_logged_in() && ( current_user_can('customer') || current_user_can('subscriber') ) ) {
		
		// Prevent showing "session expired" notice in case logged in customer is a Supervisor,
		// but Supervisor add-on has been deactivated or uninstalled.
		if ( $account_level_type == 'supervisor' && ! sfwc_is_plugin_active( 'sfwc-supervisor-addon.php' ) ) {
			return;
		}

		echo '<script>
			if (jQuery(\'#sfwc_frontend_children\').length == 0) {
			jQuery(\'#sfwc-session-expired\').show();
			}
			</script>';
	}
}
add_action('wp_footer', 'sfwc_force_switcher_display');









/**
 * Add "Add Subaccount" menu item to My Acount page (Pt.1).
 *
 * Add new query var.
 *
 * New endpoint already registered in: subaccounts-for-woocommerce > admin > admin.php (leave it there).
 */
function sfwc_add_subaccount_query_vars( $vars ) {
    $vars[] = 'add-subaccount';
    return $vars;
}
add_filter( 'query_vars', 'sfwc_add_subaccount_query_vars', 0 );
 
 


/**
 * Add "Add Subaccount" menu item to My Acount page (Pt.2).
 *
 * Insert the new endpoint into the My Account menu.
 */
function sfwc_add_subaccount_link_my_account( $items ) {
	
	// Get 'Choose who can create and add new subaccounts' from Options settings
	$sfwc_options = (array) get_option('sfwc_options');
						
	// Avoid undefined $sfwc_options_subaccount_creation in case related setting has not been saved yet.
	$sfwc_options_subaccount_creation = ( isset( $sfwc_options['sfwc_options_subaccount_creation'] ) ) ? $sfwc_options['sfwc_options_subaccount_creation'] : 'customer';
	
	// Check if option is enabled.
	if ( $sfwc_options_subaccount_creation == 'customer' || $sfwc_options_subaccount_creation == 'admin_customer' ) {

		// Get ID of currently logged-in user
		$current_user_id = get_current_user_id();


		/* Query of all Managers */
		$args_are_managers = array(
			//'role'    => 'customer',
			'role__in' => ['customer', 'subscriber'],
			'orderby' => 'ID',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'sfwc_account_level_type',
					'value' => 'manager',
					'compare' => '=',
				),
			),
		);
		// The User Query
		$customers_are_managers = new WP_User_Query( $args_are_managers );
					
					
					
		// User Loop
		if ( ! empty( $customers_are_managers->get_results() ) ) {

			foreach ( $customers_are_managers->get_results() as $user ) {

				$list_of_children_for_single_user = get_user_meta( $user->ID, 'sfwc_children', true );

				if ( ! empty( $list_of_children_for_single_user ) ) {
					
					foreach ( $list_of_children_for_single_user as $single_id ) {

						$list_of_children_for_all_managers[] = $single_id;
					}
				}
			}
		}

			
		// Get account type of currently logged-in user
		$user_parent_account_type = get_user_meta( $current_user_id, 'sfwc_account_level_type', true );


		if ( is_user_logged_in() && ( current_user_can( 'customer' ) || current_user_can( 'subscriber' ) ) ) {
			/**
			 * Check to see if we are in one of these situations:
			 *
			 *	- User logged in as manager
			 *	- 2nd condition is for those cases where the plugin has been recently installed (first-time installations), thus no manager (or children of managers) have been set yet
			 *	- User logged in as default user that has not got a manager above him (in this case the user should be turned into a manager itself while adding a subaccount)
			 *
			 * If one of these conditions is verified it is possible to show 'Add Subaccount' menu item on My Account page.
			 */
			if ( $user_parent_account_type == "manager" 
				 || ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ! isset( $list_of_children_for_all_managers ) )
				 || ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ( isset( $list_of_children_for_all_managers ) && is_array( $list_of_children_for_all_managers ) && ! in_array( $current_user_id, $list_of_children_for_all_managers ) ) ) 
			) {

				$items['add-subaccount'] = esc_html__( 'Add Subaccount', 'subaccounts-for-woocommerce' );
			}
		}
	}
	
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'sfwc_add_subaccount_link_my_account' );




/**
 * Populate "Add Subaccount" page (Pt.3).
 *
 * Add content to the new endpoint via shortcode.
 */
function sfwc_add_subaccount_content() {

	echo '<h2 style="margin: 0 0 10px 0;">' . esc_html__( 'Add Subaccount', 'subaccounts-for-woocommerce' ) . '</h2>';
	// echo '<p style="margin: 0 0 40px 0;">Lorem ipsum.</p>';
	echo do_shortcode( '[sfwc_add_subaccount_shortcode]' );

}
#add_action( 'woocommerce_account_add-subaccount_endpoint', 'sfwc_add_subaccount_content' );	// add_action embedded below,
																								// otherwise redirect not working.



/**
 * Add content to "Add Subaccount" page conditionally (Pt.4).
 *
 * Add content to the new endpoint if conditions are satisfied otherwise redirect.
 */
function sfwc_add_content_to_endpoint_conditionally() {
	
	// Get 'Choose who can create and add new subaccounts' from Options settings
	$sfwc_options = (array) get_option('sfwc_options');
						
	// Avoid undefined $sfwc_options_subaccount_creation in case related setting has not been saved yet.
	$sfwc_options_subaccount_creation = ( isset( $sfwc_options['sfwc_options_subaccount_creation'] ) ) ? $sfwc_options['sfwc_options_subaccount_creation'] : 'customer';
	
	// Check if option is enabled.
	if ( $sfwc_options_subaccount_creation == 'customer' || $sfwc_options_subaccount_creation == 'admin_customer' ) {
		



		// Get ID of currently logged-in user
		$current_user_id = get_current_user_id();


		/* Query of all Managers */
		$args_are_managers = array(
			//'role'    => 'customer',
			'role__in' => ['customer', 'subscriber'],
			'orderby' => 'ID',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'sfwc_account_level_type',
					'value' => 'manager',
					'compare' => '=',
				),
			),
		);
		// The User Query
		$customers_are_managers = new WP_User_Query( $args_are_managers );
					
					
					
		// User Loop
		if ( ! empty( $customers_are_managers->get_results() ) ) {

			foreach ( $customers_are_managers->get_results() as $user ) {

				$list_of_children_for_single_user = get_user_meta( $user->ID, 'sfwc_children', true );

				if ( ! empty( $list_of_children_for_single_user ) ) {
					
					foreach ( $list_of_children_for_single_user as $single_id ) {

						$list_of_children_for_all_managers[] = $single_id;
					}
				}
			}
		}

			
		// Get account type of currently logged-in user
		$user_parent_account_type = get_user_meta( $current_user_id, 'sfwc_account_level_type', true );


		if ( is_user_logged_in() && ( current_user_can( 'customer' ) || current_user_can( 'subscriber' ) ) ) {
			/**
			 * Check to see if we are in one of these situations:
			 *
			 *	- User logged in as manager
			 *	- 2nd condition is for those cases where the plugin has been recently installed (first-time installations), thus no manager (or children of managers) have been set yet
			 *	- User logged in as default user that has not got a manager above him (in this case the user should be turned into a manager itself while adding a subaccount)
			 *
			 * If one of these conditions is verified it is possible to show 'Add Subaccount' menu item on My Account page.
			 */
			if ( $user_parent_account_type == "manager" 
				 || ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ! isset( $list_of_children_for_all_managers ) )
				 || ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ( isset( $list_of_children_for_all_managers ) && is_array( $list_of_children_for_all_managers ) && ! in_array( $current_user_id, $list_of_children_for_all_managers ) ) ) 
			) {

				add_action( 'woocommerce_account_add-subaccount_endpoint', 'sfwc_add_subaccount_content' );

			}
		}
	}
	

	/**
	 * Check if the user is switching to another account and if so redirect him to the Dashboard endpoint after the user swtch.
	 *
	 * Premise: by default after an account switch a user is automatically redirected to the same My Account page endpoint he was before the switch.
	 * 
	 * This was causing a couple of issues:
	 *
	 *	1. If a Manager account was visiting the "Add Subaccount" endpoint before the switch,
	 *	   after the switch to a subaccount was still on the "Add Subaccount" endpoint, despite of the page being blank (content conditionally removed, see above).
	 *
	 *	2. Also, if the above Manager decided to go back to its account (while still visiting the above subaccount's blank page),
	 *	   was still redirected to its "Add Subaccount" endpoint. 
	 *	   But at that point if he tried to add a new subaccount would get nonce error: "Nonce could not be verified".
	 *
	 * To prevent all of this, here it is the redirect.
	 */	
	if ( isset( $_POST['sfwc_frontend_children'] ) ) {
		wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
		exit;
	}
}
add_action('template_redirect', 'sfwc_add_content_to_endpoint_conditionally');	// Leave template_redirect,
																				// otherwise redirect not working.



/**
 * Add "Add Subaccount" menu item to My Acount page (Pt.5).
 *
 * Create content for the shortcode (AKA Form for subaccounts creation on frontend).
 */
function sfwc_add_subaccount_form_content () {

	/**
	 * Check number of subaccount already created.
	 *
	 *
	 */


	// Get ID of currently logged-in user
	$user_parent = get_current_user_id();
	//
	$user_parent_data = get_userdata( $user_parent );
	
	

	// Get 'Customer Display Name' from Options settings
    $sfwc_options = (array) get_option('sfwc_options');

	// Avoid undefined $sfwc_options_show_users_list_columns in case related setting has not been saved yet.				
	$sfwc_option_display_name = ( isset( $sfwc_options['sfwc_option_display_name'] ) ) ? $sfwc_options['sfwc_option_display_name'] : 'username';
	
	//Check 'Customer Display Name' in Subaccounts > Settings > Options and display it accordingly
	if ( ( $sfwc_option_display_name == 'full_name' ) && ( $user_parent_data->user_firstname || $user_parent_data->user_lastname ) ) {

		// Echo 'Full Name + Email' (if either First Name or Last Name has been set)
		$user_parent_name = '<strong>' . esc_html( $user_parent_data->user_firstname ) . ' ' . esc_html( $user_parent_data->user_lastname ) . '</strong>';

	} elseif ( ( $sfwc_option_display_name == 'company_name' ) && ( $user_parent_data->billing_company ) ) {

		// Echo 'Company + Email' (if Company name has been set)
		$user_parent_name = '<strong>' . esc_html( $user_parent_data->billing_company ) . '</strong>';

	} else {

		// Otherwise echo 'Username + Email'
		$user_parent_name = '<strong>' . esc_html( $user_parent_data->user_login ) . '</strong>';
	}

	/**
	 * Get subaccounts of the currently logged in user.
	 *
	 * This array might include empty values, aka users that are still set as subaccounts of the current user,
	 * but no longer exist (have been deleted from admin).
	 */
	$already_children = get_user_meta( $user_parent, 'sfwc_children', true );
	
	// Exclude possible empty values (no longer existing users) from array.
	if ( ! empty( $already_children ) ) {

		foreach ( $already_children as $key => $value ) {
			
			// Prevent empty option values within the frontend dropdown user switcher 
			// in case a user has been deleted (but still present within 'sfwc_children' meta of an ex parent account).
			$user_exists = get_userdata( $value );
			if ( $user_exists !== false ) {

				$already_children_existing[] = $value;
			}
		}
		
		
		$qty_subaccount_already_set = count($already_children_existing);
		
		if ( $qty_subaccount_already_set >= 10 && ! sfwc_is_plugin_active( 'subaccounts-for-woocommerce-pro.php' ) ) {
			
			wc_print_notice(
			
				sprintf(
					esc_html__( 'Maximum number of subaccounts already reached for %1$s. Please contact the site administrator and ask to increase this value.', 'subaccounts-for-woocommerce' ), 
					$user_parent_name
				)		
			
			
			, 'error');
			
			return;
		}
	}






	/**
	 * In case form submit was unsuccessful, re-populate input fields with previously posted (wrong) data,
	 * so that user can correct it.
	 *
	 * If successful, input fields cleared with $_POST = array(); in above validation function.
	 */
	$user_login = isset( $_POST['user_login'] ) && $_POST['user_login'] != "" ? sanitize_user( $_POST['user_login'] ) : "";
	$email = isset( $_POST['email'] ) && $_POST['email'] != "" ? sanitize_email( $_POST['email'] ) : "";
	$first_name = isset( $_POST['first_name'] ) && $_POST['first_name'] != "" ? sanitize_text_field( $_POST['first_name'] ) : "";
	$last_name = isset( $_POST['last_name'] ) && $_POST['last_name'] != "" ? sanitize_text_field( $_POST['last_name'] ) : "";
	$company = isset( $_POST['company'] ) && $_POST['company'] != "" ? sanitize_text_field( $_POST['company'] ) : "";

	?>


		<form id="sfwc_form_add_subaccount_frontend" method="post">
		
		<?php wp_nonce_field( 'sfwc_add_subaccount_frontend_action', 'sfwc_add_subaccount_frontend' ); ?>

		<?php
		$username_required_css = ( ( isset( $_POST['user_login'] ) && $_POST['user_login'] == "" ) 
									 || ( isset( $_POST['user_login'] ) &&  username_exists( $_POST['user_login'] ) ) 
									 || ( isset( $_POST['user_login'] ) && ! validate_username( $_POST['user_login'] ) ) 
								 ) ? "color:red;" : "";
		
		
		$email_required_css = ( ( isset( $_POST['email'] ) && $_POST['email'] == "" ) 
								  || ( isset( $_POST['email'] ) && email_exists( $_POST['email'] ) ) 
								  || ( isset( $_POST['email'] ) && ! is_email( $_POST['email'] ) ) 
							  ) ? "color:red;" : "";
		?>

			<div class="user_login" style="margin-bottom:20px; width:48%; float:left;">
				<label for="user_login" style="display:block; margin-bottom:0; <?php echo esc_attr( $username_required_css ); ?>"><?php esc_html_e( 'Username', 'subaccounts-for-woocommerce' ); ?> <span style="font-weight:bold;">*</span></label>
				<input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $user_login ); ?>" style="width:100%;">
			</div>


			<div class="email" style="margin-bottom:20px; width:48%; float:right;">
				<label for="email" style="display:block; margin-bottom:0; <?php echo esc_attr( $email_required_css ); ?>"><?php esc_html_e( 'Email', 'subaccounts-for-woocommerce' ); ?> <span style="font-weight:bold;">*</span></label>
				<input type="text" name="email" id="email" value="<?php echo esc_attr( $email ); ?>" style="width:100%;">
			</div>


			<div class="first_name" style="margin-bottom:20px; width:48%; float:left;">
				<label for="first_name" style="display:block; margin-bottom:0;"><?php esc_html_e( 'First Name', 'subaccounts-for-woocommerce' ); ?></label>
				<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $first_name ); ?>" style="width:100%;">
			</div>

			
			<div class="last_name" style="margin-bottom:20px; width:48%; float:right;">
				<label for="last_name" style="display:block; margin-bottom:0;"><?php esc_html_e( 'Last Name', 'subaccounts-for-woocommerce' ); ?></label>
				<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $last_name ); ?>" style="width:100%;">
			</div>


			<div class="company" style="margin-bottom:20px; width:100%;">
				<label for="company" style="display:block; margin-bottom:0;"><?php esc_html_e( 'Company', 'subaccounts-for-woocommerce' ); ?></label>
				<input type="text" name="company" id="company" value="<?php echo esc_attr( $company ); ?>" style="width:100%;">
			</div>
			
			<p style="padding:15px; background:#f5f5f5; border-left:5px; border-left-color:#7eb330; border-left-style:solid; display:flex;">
				<span style="font-size:35px; color:#7eb330; align-self:center;">&#128712;</span>
				
				<span style="align-self:center; padding-left:10px;">
					<?php echo esc_html__( 'An email containing the username and a link to set the password will be sent to the new account after the subaccount is created.', 'subaccounts-for-woocommerce' ); ?>
				</span>
			</p>

			<input type="submit" value="Add Subaccount" style="padding:10px 40px;">
			
			<p style="margin-top:50px;">
				<span style="font-weight:bold;">*</span> <?php echo esc_html__( 'These fields are required.', 'subaccounts-for-woocommerce' ); ?></span>
			</p>

		</form>


	<?php
}
add_shortcode( 'sfwc_add_subaccount_shortcode', 'sfwc_add_subaccount_form_content');




/**
 * Add "Add Subaccount" menu item to My Acount page (Pt.6).
 *
 * Handle form submitted data.
 */
function sfwc_add_subaccount_form_handler() {


	// Get ID of currently logged-in user
	$user_parent = get_current_user_id();
	
	
	$user_parent_data = get_userdata( $user_parent );


	$user_login = ""; // For validation and sanitization see below
	$email = ""; // For validation and sanitization see below
	$first_name = isset( $_POST['first_name'] ) && $_POST['first_name'] != "" ? sanitize_text_field( $_POST['first_name'] ) : "";
	$last_name = isset( $_POST['last_name'] ) && $_POST['last_name'] != "" ? sanitize_text_field( $_POST['last_name'] ) : "";
	$company = isset( $_POST['company'] ) && $_POST['company'] != "" ? sanitize_text_field( $_POST['company'] ) : ""; 
	



	// Validation and sanitization of Username input field.
	if ( isset( $_POST['user_login'] ) && $_POST['user_login'] == "" ) {

		wc_add_notice( esc_html__( 'Username is required.', 'subaccounts-for-woocommerce' ), 'error');
	
	} elseif ( isset( $_POST['user_login'] ) && $_POST['user_login'] != "" ) {
		
		if ( ! validate_username( $_POST['user_login'] ) ) {
																			
			wc_add_notice( esc_html__( 'Username is not valid.', 'subaccounts-for-woocommerce' ), 'error');

		} else {
			$user_login = sanitize_user( $_POST['user_login'] );
		}
	}



	// Validation and sanitization of Email input field.
	if ( isset( $_POST['email'] ) && $_POST['email'] == "" ) {

		wc_add_notice( esc_html__( 'Email is required.', 'subaccounts-for-woocommerce' ), 'error');

	} elseif ( isset( $_POST['email'] ) && $_POST['email'] != "" ) {
		
		if ( ! is_email( $_POST['email'] ) ) {

			wc_add_notice( esc_html__( 'Email is not valid.', 'subaccounts-for-woocommerce' ), 'error');

		} else {
			$email = sanitize_email( $_POST['email'] );
		}
	}	
	


	

	/**
	 * If at least basic required information for subaccount creation is provided and validated:
	 * 
	 * 	- $user_login
	 * 	- $email
	 *
	 * proceed with subaccount creation.
	 */
	if ( ( isset( $user_login ) && $user_login != "" && validate_username( $user_login ) ) && ( isset( $email ) && $email != "" && is_email( $email ) ) ) {
		
		// Check if nonce is in place and verfy it.
		if ( ! isset( $_POST['sfwc_add_subaccount_frontend'] ) || isset( $_POST['sfwc_add_subaccount_frontend'] ) && ! wp_verify_nonce( $_POST['sfwc_add_subaccount_frontend'], 'sfwc_add_subaccount_frontend_action' ) ) {
																										
			wc_add_notice( esc_html__( 'Nonce could not be verified.', 'subaccounts-for-woocommerce' ), 'error');

		} else {
			
			/**
			 * Everything looks fine, we can proceed with subaccount creation.
			 */
		
			// Generate a password for the subaccount.
			$password = wp_generate_password();
			
			
			$userinfo = array(
				'user_login' => $user_login,
				'user_email' => $email,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'user_pass' => $password,
				//'role' => 'customer'			// Leave commented, this way 
												// Settings > General > New User Default Role 
												// will apply (E.g. customer || subscriber)
			);


			// In case 'New User Default Role' has not been already properly set (E.g. customer || subscriber) in General settings.
			$default_user_role = get_option('default_role');
			
			if ( $default_user_role !== 'customer' && $default_user_role !== 'subscriber' ) {
			
				$userinfo['role'] = 'customer';
			}


			// Create the WordPress User object with the basic required information.
			$user_id = wp_insert_user( $userinfo );

			// If wp_insert_user executes successfully, it will return the id of the created user.
			if ( ! $user_id || is_wp_error( $user_id ) ) {

				// In case something went wrong with wp_insert_user, throw related errors.
				wc_add_notice( $user_id->get_error_message(), 'error');

			} else {
				
				// If wp_insert_user has executed successfully and the id of the created user has been returned,
				// use that id to insert Company name.
				update_user_meta( $user_id, 'billing_company', $company ); // Sanitized @ 1225.

				wc_add_notice( '<strong>' . esc_html__( 'Subaccount successfully added.', 'subaccounts-for-woocommerce' ) . '</strong><br>' . esc_html__( 'You can now switch to the newly added subaccount by selecting it from the drop-down menu.', 'subaccounts-for-woocommerce' ), 'success');			
			

				$already_children = get_user_meta( $user_parent, 'sfwc_children', true ); 	// We need to get the value of the array and update it by adding the new ID,
																							// otherwise array values which are already present will be overwritten and only the last ID will be added.
				// Check to see if thare are children already set...
				if ( is_array( $already_children ) && ! empty( $already_children ) ) {
				
					array_push( $already_children, (string)$user_id );
				  
				// ... If not, create a single element array and store it.
				} else {
					$already_children = array();
					$already_children[] = (string)$user_id;
				}

				update_user_meta( $user_parent, 'sfwc_children', $already_children );
				




				/**
				 * In case the logged in user has a "default" (or "") account level type AND has not got a manager above him,
				 * turn the user itself into a manager while adding a subaccount.
				 */


				/* Query of all Managers */
				$args_are_managers = array(
					//'role'    => 'customer',
					'role__in' => ['customer', 'subscriber'],
					'orderby' => 'ID',
					'order' => 'ASC',
					'meta_query' => array(
						array(
							'key' => 'sfwc_account_level_type',
							'value' => 'manager',
							'compare' => '=',
						),
					),
				);
				// The User Query
				$customers_are_managers = new WP_User_Query( $args_are_managers );
							
							
							
				// User Loop
				if ( ! empty( $customers_are_managers->get_results() ) ) {

					foreach ( $customers_are_managers->get_results() as $user ) {

						$list_of_children_for_single_user = get_user_meta( $user->ID, 'sfwc_children', true );

						if ( ! empty( $list_of_children_for_single_user ) ) {
							
							foreach ( $list_of_children_for_single_user as $single_id ) {

								$list_of_children_for_all_managers[] = $single_id;
							}
						}
					}
				}

					
				// Get account type of currently logged-in user
				$user_parent_account_type = get_user_meta( $user_parent, 'sfwc_account_level_type', true );



				/**
				 * Check to see if:
				 *
				 *	- 1st condition is for those cases where the plugin has been recently installed (first-time installations), thus no manager (or children of managers) have been set yet
				 *	- User logged in as default user that has not got a manager above him (in this case the user must be turned into a manager itself while adding a subaccount)
				 *
				 * If the condition is verified change account type from "default" (or "") to "manager".
				 */
				if ( ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ! isset( $list_of_children_for_all_managers ) ) ||
					 ( ( $user_parent_account_type == "default" || $user_parent_account_type == "" ) && ( isset( $list_of_children_for_all_managers ) && is_array( $list_of_children_for_all_managers ) && ! in_array( $user_parent, $list_of_children_for_all_managers ) ) ) ) {

					// Turn a "default" user into "manager"
					update_user_meta( $user_parent, 'sfwc_account_level_type', 'manager' );
				}






				
				/**
				 * In case the subaccount is being added from a Supervisor account, make the subaccount a Manager
				 * by updating its 'sfwc_account_level_type' meta value.
				 */

				// Check account type of parent account.
				$check_account_type = get_user_meta( $user_parent, 'sfwc_account_level_type', true );
				
				if ( $check_account_type == 'supervisor') {
					
					update_user_meta( $user_id, 'sfwc_account_level_type', 'manager' );
				}





				
				/**
				 * Send WooCommerce "Customer New Account" email notification to newly created subaccount with username and link to set its the password.
				 *
				 * Make sure it's enabled:
				 *
				 * WooCommerce > Settings > Account and privacy 
				 * 
				 * 		- When creating an account, automatically generate an account password
				 *
				 * WooCommerce > Settings > Emails
				 *
				 *		- 'New Account' email
				 *
				 * https://stackoverflow.com/questions/61576356/send-an-email-notification-with-the-generated-password-on-woocommerce-user-creat/#answer-61582804
				 */
				 
				// Get all WooCommerce emails Objects from WC_Emails Object instance
				$emails = WC()->mailer()->get_emails();

				// Send "Customer New Account" email notification.
				$emails['WC_Email_Customer_New_Account']->trigger( $user_id, $password, true );




				// If subaccount has been successfully created, clear the form.
				if ( isset( $user_id ) && !is_wp_error( $user_id ) ) {
					
					$_POST = array();
				}
			}
		}
	}

	/**
	 * Debug.
	 */
	 
	//echo $user_parent;

	// $already_children = get_user_meta( $user_parent, 'sfwc_children', true );
	// echo '<pre>',print_r($already_children,1),'</pre>';

	// $check_account_type = get_user_meta( $user_parent, 'sfwc_account_level_type', true );
	// var_dump( $check_account_type );

}

/**
 * Do not change template_redirect as hook here.
 * 
 * This function needs to run before than:
 *
 *		woocommerce_before_account_navigation, see: add_action('woocommerce_before_account_navigation', 'sfwc_action_woocommerce_account_dashboard');
 *
 * so that the newly created subaccount will appear immediately after form submission within the user switcher (no page refresh required).
 */
add_action( 'template_redirect', 'sfwc_add_subaccount_form_handler');




/**
 * For the following function DO NOT move validation of supervisor related values,
 * so that if Supervisor Add-on is deactivated and there are still customers with account level type set to 'supervisor'
 * the plugin will continue to store/display correct data.
 */
function sfwc_store_subaccounts_meta_data_on_order_creation( $order, $data ) {
	
	
	// Check if logged in user has role of customer or subscriber
	if ( is_user_logged_in() && ( current_user_can('customer') || current_user_can('subscriber') ) ) {
	
		// Get user id from order
		$customer_id = $order->get_user_id();
		
		// Get Account Level Type
		$customer_account_level_type = get_user_meta( $customer_id, 'sfwc_account_level_type', true );
		







		if ( $customer_account_level_type == 'manager' || $customer_account_level_type == 'supervisor' ) {
	
			$manager_id = 'none';

		} else {
			
			
			/**
			 * Retrieve the ID of the manager related to Customer.
			 */
	
			// Get all users with user meta 'sfwc_account_level_type' == 'manager' and filter only the one which has 'sfwc_children' user_meta containing the child ID who made the order
			$args_manager = array(
				//'role'    => 'customer',
				'role__in' => ['customer', 'subscriber'],
				'exclude' => $customer_id, // Exclude ID of customer who made currently displayed order
				'orderby' => 'ID',
				'order' => 'ASC',
				'meta_key' => 'sfwc_account_level_type',
				'meta_value' => 'manager',
				'meta_query' => array(
					array(
						'key' => 'sfwc_children',
						'value' => '"'.$customer_id.'"',
						'compare' => 'LIKE',
					),
				),
			);


			// The User Query
			$user_query_manager = new WP_User_Query( $args_manager );


			// User Loop
			if ( ! empty( $user_query_manager->get_results() ) ) {

				foreach ( $user_query_manager->get_results() as $user ) {
					
					$manager_id = $user->ID;
				}

			} else {
				
				$manager_id = 'not_set';
			}
				
		}		
				



 
		
	if ( $customer_account_level_type == 'supervisor' ) {
		
		$supervisor_id = 'none';
	}
	
	elseif ( $customer_account_level_type == 'manager' ) {
		
		/**
		 * Retrieve the Supervisor's ID related to Customer.
		 */

		// Get all users with user meta 'sfwc_account_level_type' == 'supervisor' and filter only the one which has 'sfwc_children' user_meta containing the ID of the Manager
		$args_supervisor = array(
			//'role'    => 'customer',
			'role__in' => ['customer', 'subscriber'],
			'exclude' => $customer_id, // Exclude ID of customer who made currently displayed order
			'orderby' => 'ID',
			'order' => 'ASC',
			'meta_key' => 'sfwc_account_level_type',
			'meta_value' => 'supervisor',
			'meta_query' => array(
				array(
					'key' => 'sfwc_children',
					'value' => '"'.$customer_id.'"',
					'compare' => 'LIKE',
				),
			),
		);


		// The User Query
		$user_query_supervisor = new WP_User_Query( $args_supervisor );


		// User Loop
		if ( ! empty( $user_query_supervisor->get_results() ) ) {

			foreach ( $user_query_supervisor->get_results() as $user ) {
				
				$supervisor_id = $user->ID;
			}

		} else {
			
			$supervisor_id = 'not_set';
		}

	}

	elseif ( $customer_account_level_type == 'default' || $customer_account_level_type == '' || empty( $customer_account_level_type ) ) {

		// Get all users with user meta 'sfwc_account_level_type' == 'supervisor' and filter only the one which has 'sfwc_children' user_meta containing the ID of the Manager
		$args_supervisor = array(
			//'role'    => 'customer',
			'role__in' => ['customer', 'subscriber'],
			'exclude' => $customer_id, // Exclude ID of customer who made currently displayed order
			'orderby' => 'ID',
			'order' => 'ASC',
			'meta_key' => 'sfwc_account_level_type',
			'meta_value' => 'supervisor',
			'meta_query' => array(
				array(
					'key' => 'sfwc_children',
					'value' => '"'.$manager_id.'"',
					'compare' => 'LIKE',
				),
			),
		);


		// The User Query
		$user_query_supervisor = new WP_User_Query( $args_supervisor );


		if ( ! empty( $user_query_manager->get_results() ) ) {
		
			// User Loop
			if ( ! empty( $user_query_supervisor->get_results() ) ) {

				foreach ( $user_query_supervisor->get_results() as $user ) {
					
					$supervisor_id = $user->ID;
				}

			} else {
				
				$supervisor_id = 'not_set';
			}
		
		} else {
				
				$supervisor_id = 'not_set';
		}
		
	}





		if ( isset($_COOKIE['is_supervisor'] ) ) {
			

			if ( $customer_account_level_type == 'supervisor' ) {
				
				// The order was placed by a supervisor for himself.
				$order_placed_by = 'supervisor_for_himself';

				
			} elseif ( $customer_account_level_type == 'manager' ) {
				
				// The order was placed by a manager for himself.
				$order_placed_by = 'supervisor_for_manager';

				
			} elseif ( $customer_account_level_type == 'default' || $customer_account_level_type == '' || $customer_account_level_type == null ) {
				
				// The order was placed by a manager on behalf of his subaccount.
				$order_placed_by = 'supervisor_for_default';
			}
			
		} elseif ( ! isset($_COOKIE['is_supervisor'] )  && isset($_COOKIE['is_manager'] ) ) {
			
			
			if ( $customer_account_level_type == 'manager' ) {
				
				// The order was placed by a manager for himself.
				$order_placed_by = 'manager_for_himself';
				
			} elseif ( $customer_account_level_type == 'default' || $customer_account_level_type == '' || $customer_account_level_type == null ) {
				
				// The order was placed by a manager on behalf of his subaccount.
				$order_placed_by = 'manager_for_default';
			}
			
		} else {
			
			// The order was placed by a 'default' customer for himself.
			$order_placed_by = 'default_for_himself';

		}




        $valid_customer_account_level_type = array( 'supervisor', 'manager', 'default', '' );

        if ( in_array( $customer_account_level_type, $valid_customer_account_level_type ) ) {
		
			// Store the customer's account level type.
			$order->update_meta_data( '_sfwc_customer_account_level_type', $customer_account_level_type );
		}



		if ( ( $supervisor_id && is_numeric( $supervisor_id ) && $supervisor_id >= 1 && preg_match( '/^[1-9][0-9]*$/', $supervisor_id ) ) || ( $supervisor_id && $supervisor_id == 'not_set' ) || ( $supervisor_id && $supervisor_id == 'none' ) ) {
		
			$valid_supervisor_id = sanitize_text_field( $supervisor_id );
			
			// Store the ID of the Supervisor related to the Customer.
			$order->update_meta_data( '_sfwc_customer_related_supervisor', $valid_supervisor_id );
		}
		
		
		/**
		 * Check if $manager_id (numeric string) is a positive number.
		 *
		 * 3			true
		 * 03			false
		 * 3.5			false
		 * 3,5			false
		 * +3			false
		 * -3			false
		 * 1337e0		false
		 */
		if ( ( $manager_id && is_numeric( $manager_id ) && $manager_id >= 1 && preg_match( '/^[1-9][0-9]*$/', $manager_id ) ) || ( $manager_id && $manager_id == 'not_set' )  || ( $manager_id && $manager_id == 'none' ) ) {
		
			$valid_manager_id = sanitize_text_field( $manager_id );
			
			// Store the ID of the Manager related to the Customer.
			$order->update_meta_data( '_sfwc_customer_related_manager', $valid_manager_id );
		}
		
		

		// Store info about who actually placed the order.
        $valid_order_placed_by = array( 'manager_for_himself', 'manager_for_default', 'default_for_himself', 'supervisor_for_himself', 'supervisor_for_manager', 'supervisor_for_default' );

        if ( in_array( $order_placed_by, $valid_order_placed_by ) ) {

			$order->update_meta_data( '_sfwc_order_placed_by', $order_placed_by );
        }

	}
}
add_action('woocommerce_checkout_create_order', 'sfwc_store_subaccounts_meta_data_on_order_creation', 20, 2);







/**
 * In case an order is created on backend by Admin, _sfwc_order_placed_by order meta will be empty,
 * so update it with the proper value after customer has payed the order.
 *
 */
function sfwc_order_placed_by_update_order_meta_after_payment( $order_id ) {

    //create an order instance
    $order = wc_get_order($order_id);
	
	// Get user id from order
	$customer_id = $order->get_user_id();
	
	
	// Get Account Level Type
	$customer_account_level_type = get_user_meta( $customer_id, 'sfwc_account_level_type', true );
	
	
	if ( ! isset($_COOKIE['is_supervisor'] )  && isset($_COOKIE['is_manager'] ) ) {
		
		
		if ( $customer_account_level_type == 'manager' ) {
			
			// The order was placed by a manager for himself.
			$order_placed_by = 'manager_for_himself';
			
		} elseif ( $customer_account_level_type == 'default' || $customer_account_level_type == '' || $customer_account_level_type == null ) {
			
			// The order was placed by a manager on behalf of his subaccount.
			$order_placed_by = 'manager_for_default';
		}
		
	} elseif ( ! isset($_COOKIE['is_supervisor'] )  && ! isset($_COOKIE['is_manager'] ) ) {
		
		// The order was placed by a 'default' customer for himself.
		$order_placed_by = 'default_for_himself';

	}
	
	
	if ( isset( $order_placed_by ) ) {	// Avoid undefined variable in case 
										// $customer_account_level_type == supervisor
										// which is handled by sfwc_order_placed_by_update_order_meta_after_payment_supervisor in Supervisor add-on

		// Store info about who actually placed the order.
		// In case of supervisor, see function: sfwc_order_placed_by_update_order_meta_after_payment_supervisor in Supervisor add-on
		$valid_order_placed_by = array( 'manager_for_himself', 'manager_for_default', 'default_for_himself' );

		if ( in_array( $order_placed_by, $valid_order_placed_by ) ) {
			
			update_post_meta( $order_id, '_sfwc_order_placed_by', $order_placed_by );
		}
	}
}
add_action('woocommerce_thankyou', 'sfwc_order_placed_by_update_order_meta_after_payment', 10, 1);




/**
 * Adds a new column to the "My Orders" table in the account.
 *
 * @param string[] $columns the columns in the orders table
 * @return string[] updated columns
 */
function sfwc_add_my_account_orders_column( $columns ) {

	$new_columns = array();

	foreach ( $columns as $key => $name ) {

		$new_columns[ $key ] = $name;

		// Add order-placed-by after order status column
		if ( 'order-status' === $key ) {
			$new_columns['order-placed-by'] = esc_html__( 'Order placed by', 'subaccounts-for-woocommerce' );
		}
	}

	return $new_columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'sfwc_add_my_account_orders_column' );




/**
 * Adds data to the custom "Order Placed By" column in "My Account > Orders".
 *
 * For the following function DO NOT move validation of supervisor related values
 * so that if Supervisor Add-on is deactivated and there are still customers with account level type set to 'supervisor'
 * the plugin will continue to store/display correct data.
 */
function sfwc_my_account_orders_order_placed_by( $order ) {
	
	// Retrieve the customer who actually placed the order.
	$order_placed_by = $order->get_meta('_sfwc_order_placed_by');
	

	$customer_id = $order->get_user_id();
	
	// Retrieve user data for customer related to order.
	$userdata_customer = get_userdata( $customer_id );


	// Retrieve the ID of the Manager related to the Customer.
	$customer_related_manager_id = $order->get_meta('_sfwc_customer_related_manager');
	
	// Retrieve user data for Manager.
	$userdata_manager = get_userdata( $customer_related_manager_id );
	
	
	// Retrieve the ID of the Supervisor related to the Customer.
	$customer_related_supervisor_id = $order->get_meta('_sfwc_customer_related_supervisor');
	
	// Retrieve user data for Supervisor.
	$userdata_supervisor = get_userdata( $customer_related_supervisor_id );
	

	if ( $order_placed_by ) {
		
		if ( $order_placed_by == 'supervisor_for_himself' || $order_placed_by == 'manager_for_himself' || $order_placed_by == 'default_for_himself' ) {
			
			printf( '%1$s %2$s', esc_html( $userdata_customer->user_firstname ), esc_html( $userdata_customer->user_lastname ) );
			
		} elseif ( $order_placed_by == 'supervisor_for_manager' || $order_placed_by == 'supervisor_for_default' ) {
			
			printf(  '<small><strong>' . esc_html__( 'Supervisor', 'subaccounts-for-woocommerce' ) . '</strong></small><br>' . '%1$s %2$s', esc_html( $userdata_supervisor->user_firstname ), esc_html( $userdata_supervisor->user_lastname ) );
			
		} elseif ( $order_placed_by == 'manager_for_default' ) {
			
			printf( '<small><strong>' . esc_html__( 'Manager', 'subaccounts-for-woocommerce' ) . '</strong></small><br>' . '%1$s %2$s', esc_html( $userdata_manager->user_firstname ), esc_html( $userdata_manager->user_lastname ) );
			
		}
		
	} else {
		echo esc_html__( 'No information available', 'subaccounts-for-woocommerce' );
	}

}
add_action( 'woocommerce_my_account_my_orders_column_order-placed-by', 'sfwc_my_account_orders_order_placed_by' );