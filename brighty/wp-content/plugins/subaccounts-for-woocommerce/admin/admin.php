<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}




/**
 * Set WooCommerce screen IDs.
 *
 * This will enqueue WooCommerce CSS/JS and allow to show tooltips text on hover on:
 *
 *	- Plugin settings page
 *	- Admin users list
 */
function sfwc_set_wc_screen_ids( $screen ){
      $screen[] = 'woocommerce_page_subaccounts';
	  $screen[] = 'users';
      return $screen;
}
add_filter('woocommerce_screen_ids', 'sfwc_set_wc_screen_ids' );

/**
 * For debug. Keep commented.
 * For each admin page show current screen ID.
 */
/*
function sfwc_dump_screen_ids() {
	var_dump( get_current_screen()->id );
}
add_action('admin_notices', 'sfwc_dump_screen_ids');
*/




/**
 * Add Custom Links.
 *
 * Add following links next to 'Deactivate Plugin' option on plugins list page:
 *
 *	- Settings
 *	- Premium version of Subaccounts
 */
function sfwc_settings_plugin_link( $links, $file ) {

	if ( $file == 'subaccounts-for-woocommerce/subaccounts-for-woocommerce.php' ) {
		
        // Insert "Settings" link at the beginning.
        $sfwc_settings_link = '<a href="admin.php?page=subaccounts">' . esc_html__( 'Settings', 'subaccounts-for-woocommerce' ) . '</a>';
        array_unshift( $links, $sfwc_settings_link );

		$links['get_subaccounts_pro'] = '<nobr style="padding-top:2px; display:block;">
											<a href="' . admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ) . '" style="font-weight:bold;">'
												. esc_html__( 'Get Subaccounts Pro', 'subaccounts-for-woocommerce' ) . ' ★
											</a>
										</nobr>';
    }
    return $links;
}
add_filter('plugin_action_links', 'sfwc_settings_plugin_link', 10, 2);




/**
 * Backend style
 */
function sfwc_enqueue_backend_style() {

    // $plugin_url = plugin_dir_url( __FILE__ );
    // wp_enqueue_style( 'sfwc_backend_style', $plugin_url . 'assets/css/admin.css' );
	
	wp_enqueue_style( 'sfwc_backend_style', WP_PLUGIN_URL . '/subaccounts-for-woocommerce/assets/css/admin.css' );
}
add_action('admin_enqueue_scripts', 'sfwc_enqueue_backend_style');




/**
 * Display Subaccounts information on Order page.
 *
 * Add a meta box showing information about 'Manager' (and 'Supervisor' if 'Supervisor Add-on' installed) for the customer who made the order.
 */

// Adding Meta container admin shop_order pages
function sfwc_add_meta_box() {
	
	if ( ! wp_doing_ajax() ) {

		$sfwc_options = (array) get_option('sfwc_options');
		
		// Avoid undefined $sfwc_options_show_order_meta_box in case related setting has not been saved yet.
		$sfwc_options_show_order_meta_box = ( isset( $sfwc_options['sfwc_options_show_order_meta_box'] ) ) ? $sfwc_options['sfwc_options_show_order_meta_box'] : '0';

		// Check if option enabled in Subaccounts > Settings > Options
		if ( $sfwc_options_show_order_meta_box == '1' ) {
			add_meta_box('woocommerce-order-subaccounts', esc_html__( 'Subaccounts Info', 'subaccounts-for-woocommerce' ), 'sfwc_add_meta_box_content', 'shop_order', 'side', 'core');
		}
	}
}
add_action('add_meta_boxes', 'sfwc_add_meta_box'); // sfwc_add_meta_box_content in: includes > functions.php




/**
 * Save Manager info in Order's post meta when order is created from backend.
 * 
 * In case of order created from backend by admin, save Manager information in Order's post meta.
 *
 * See also: sfwc_update_meta_after_payment function from my-account.php,
 * for updating Order's post meta with "order placed by" information (after the customer completes the order).
 */
function sfwc_store_subaccounts_meta_data_on_order_creation_admin_side( $order_id ) {
	
	if ( ! wp_doing_ajax() ) {
	
		// Get an instance of the WC_Order Object from the Order ID
		$order = wc_get_order( $order_id );

		// Get the Customer ID (User ID)
		$customer_id = $order->get_user_id();
		
		// Get Account Level Type
		$customer_account_level_type = get_user_meta( $customer_id, 'sfwc_account_level_type', true );
		
		
		$user_query_manager = []; // Prevent undefined variable when doing action below.
		
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


		do_action('sfwc_store_additional_subaccounts_meta_on_order_creation_admin_side', $order_id, $customer_account_level_type, $customer_id, $user_query_manager, $manager_id );	
			

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
			update_post_meta( $order_id, '_sfwc_customer_related_manager', $valid_manager_id );
		}
		
		
		
		
		/**
		 * Validate the value of 'account level type' for 'manager' and 'default' account types. 
		 *
		 * For the validation of 'supervisor' see function: sfwc_store_subaccounts_meta_data_on_order_creation_admin_side_supervisor in Supervisor add-on
		 */	
		$valid_customer_account_level_type = array( 'manager', 'default', '' );

		if ( in_array( $customer_account_level_type, $valid_customer_account_level_type ) ) {
		
			// Store the customer's account level type.
			update_post_meta( $order_id, '_sfwc_customer_account_level_type', $customer_account_level_type );
		}
	
	}
}
add_action('woocommerce_new_order', 'sfwc_store_subaccounts_meta_data_on_order_creation_admin_side', 10, 1);	// woocommerce_new_order triggered both for frontend
																												// and backend order creation; for backend only check with is_admin.
																												
																												// Also, woocommerce_new_order triggered only on order creation,
																												// but not on order update.

/**
 * woocommerce_process_shop_order_meta
 *
 * Triggered only for backend order creation.
 * Triggered for both order creation and order update; to trigger it only in one of the two cases,
 * check if order already exists.
 */

/*
function save_order_custom_field_meta( $order_id ) {

	// Do something...
}
add_action( 'woocommerce_process_shop_order_meta', 'save_order_custom_field_meta' );
*/




/**
 * Create admin plugin pages/subpages:
 *
 *	- Subaccounts
 * 		-- Settings
 */
function sfwc_admin_menu() {

	// Add WooCommerce menu sub-page.
	add_submenu_page(
		'woocommerce',															// $parent_slug
		esc_html__( 'Subaccounts Options Page', 'subaccounts-for-woocommerce' ),	// $page_title
		esc_html__( 'Subaccounts', 'subaccounts-for-woocommerce' ),					// $menu_title
		'manage_woocommerce',													// $capability
		'subaccounts',															// $menu_slug
		'sfwc_admin_page_contents',												// $function
		//9999																	// $position
	);

}
add_action('admin_menu', 'sfwc_admin_menu', 60);





/**
 * Create plugin's settings.
 *
 * 
 */
function sfwc_settings_init() {
	
	// Create Appearance Settings

	$args_sfwc_switcher_appearance = array(
		'type' => 'array',
		'sanitize_callback' => 'sfwc_switcher_appearance_validate' // validation callback
	);


	register_setting(
			'sfwc_switcher_appearance_group',	// group, used for settings_fields()
			'sfwc_switcher_appearance',			// option name, used as key in database
			$args_sfwc_switcher_appearance		// $args
	);



	add_settings_section(
			'sfwc_switcher_appearance_section',													// HTML ID tag for the section
			esc_html__( 'Frontend User Switcher Pane Appearance', 'subaccounts-for-woocommerce' ),	// Title for the section
			'', 																				//'sfwc_setting_section_callback_function'
			'subaccounts'                    													// Menu slug, same as 4th parameter of add_menu_page or 5th parameter of add_submenu_page
	);


	add_settings_field(
			'sfwc_switcher_pane_bg_color',									// HTML ID of the input element
			esc_html__( 'Background Color:', 'subaccounts-for-woocommerce' ),	// Title displayed at the left of input element
			'sfwc_switcher_pane_bg_color_html',								// Callback function for HTML input markup
			'subaccounts',
			'sfwc_switcher_appearance_section'
	);

	add_settings_field(
			'sfwc_switcher_pane_headline_color',
			esc_html__( 'Headline Color:', 'subaccounts-for-woocommerce' ),
			'sfwc_switcher_pane_headline_color_html',
			'subaccounts',
			'sfwc_switcher_appearance_section'
	);

	add_settings_field(
			'sfwc_switcher_pane_text_color',
			esc_html__( 'Text Color:', 'subaccounts-for-woocommerce' ),
			'sfwc_switcher_pane_text_color_html',
			'subaccounts',
			'sfwc_switcher_appearance_section'
	);

	add_settings_field(
			'sfwc_switcher_pane_select_bg_color',
			esc_html__( 'Button Background Color:', 'subaccounts-for-woocommerce' ),
			'sfwc_switcher_pane_select_bg_color_html',
			'subaccounts',
			'sfwc_switcher_appearance_section'
	);

	add_settings_field(
			'sfwc_switcher_pane_select_text_color',
			esc_html__( 'Button Text Color:', 'subaccounts-for-woocommerce' ),
			'sfwc_switcher_pane_select_text_color_html',
			'subaccounts',
			'sfwc_switcher_appearance_section'
	);




	// Create Options Settings

	$args_sfwc_options = array(
		'type' => 'array',
		'sanitize_callback' => 'sfwc_options_validate' // validation callback
	);

	register_setting(
			'sfwc_options_group',	// group, used for settings_fields()
			'sfwc_options',			// option name, used as key in database
			$args_sfwc_options		// $args
	);


	add_settings_section(
			'sfwc_options_section',								// HTML ID tag for the section
			'',													// Title for the section
			'',													//'sfwc_setting_section_callback_function' Callback for section description
			'subaccounts&tab=options'							// Menu slug, same as 4th parameter of add_menu_page or 5th parameter of add_submenu_page
	);

    // Check if function exists from PRO plugin
    if ( ! function_exists( 'sfwc_pro_settings_init' ) ) {

		add_settings_field(
				'sfwc_option_display_name',
				esc_html__('Customer Display Name', 'subaccounts-for-woocommerce')
				. '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Set Customer\'s Display Name across the Subaccounts Plugin.', 'subaccounts-for-woocommerce' ) . '"></span>',
				'sfwc_option_display_name_html',
				'subaccounts&tab=options',
				'sfwc_options_section'
		);
	
		add_settings_field(
				'sfwc_options_show_order_meta_box',
				esc_html__('Show subaccounts information on WooCommerce order page', 'subaccounts-for-woocommerce')
				. '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Show Customer\'s Manager and Supervisor information on WooCommerce Order Page (Admin area).', 'subaccounts-for-woocommerce' ) . '"></span>',
				'sfwc_options_show_order_meta_box_html',
				'subaccounts&tab=options',
				'sfwc_options_section'
		);
	}
}
add_action('admin_init', 'sfwc_settings_init');




/*
function sfwc_setting_section_callback_function() {
echo '<p>Intro text for our settings section</p>';
}
*/




/**
 * Appearance Tab markup
 */
function sfwc_switcher_pane_bg_color_html() {
	
	// Get 'Appearance' settings
	$sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');

	// Get Pane Background Color.
	$sfwc_switcher_pane_bg_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_bg_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_bg_color'] : '#def6ff';
	?>
	
	<!-- <label for="my-input"><?php esc_html_e('My Input'); ?></label> -->
	<input type="text" id="sfwc_switcher_pane_bg_color" name="sfwc_switcher_appearance[sfwc_switcher_pane_bg_color]" value="<?php echo esc_attr( $sfwc_switcher_pane_bg_color ); ?>" class="sfwc-color-field" data-default-color="#def6ff" />
	
	<?php
}

function sfwc_switcher_pane_headline_color_html() {
	
	// Get 'Appearance' settings
	$sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');

	// Get Pane Headline Color
	$sfwc_switcher_pane_headline_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_headline_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_headline_color'] : '#0088cc';
	?>

	<input type="text" id="sfwc_switcher_pane_headline_color" name="sfwc_switcher_appearance[sfwc_switcher_pane_headline_color]" value="<?php echo esc_attr( $sfwc_switcher_pane_headline_color ); ?>" class="sfwc-color-field" data-default-color="#0088cc" />

	<?php
}

function sfwc_switcher_pane_text_color_html() {
	
	// Get 'Appearance' settings
	$sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');

	// Get Pane Text Color.
	$sfwc_switcher_pane_text_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_text_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_text_color'] : '#3b3b3b';
	?>

	<input type="text" id="sfwc_switcher_pane_text_color" name="sfwc_switcher_appearance[sfwc_switcher_pane_text_color]" value="<?php echo esc_attr( $sfwc_switcher_pane_text_color ); ?>" class="sfwc-color-field" data-default-color="#3b3b3b" />

	<?php
}

function sfwc_switcher_pane_select_bg_color_html() {

	// Get 'Appearance' settings
	$sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');

	// Get Pane Select Button Background Color.
	$sfwc_switcher_pane_select_bg_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_select_bg_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_select_bg_color'] : '#0088cc';
	?>

	<input type="text" id="sfwc_switcher_pane_select_bg_color" name="sfwc_switcher_appearance[sfwc_switcher_pane_select_bg_color]" value="<?php echo esc_attr( $sfwc_switcher_pane_select_bg_color ); ?>" class="sfwc-color-field" data-default-color="#0088cc" />

	<?php
}

function sfwc_switcher_pane_select_text_color_html() {

	// Get 'Appearance' settings.
	$sfwc_switcher_appearance = (array) get_option('sfwc_switcher_appearance');

	// Get Pane Select Button Text Color.
	$sfwc_switcher_pane_select_text_color = ( isset( $sfwc_switcher_appearance['sfwc_switcher_pane_select_text_color'] ) ) ? $sfwc_switcher_appearance['sfwc_switcher_pane_select_text_color'] : '#ffffff';
	?>

	<input type="text" id="sfwc_switcher_pane_select_text_color" name="sfwc_switcher_appearance[sfwc_switcher_pane_select_text_color]" value="<?php echo esc_attr( $sfwc_switcher_pane_select_text_color ); ?>" class="sfwc-color-field" data-default-color="#ffffff" />

	<?php
}

/**
 * Options Tab markup
 */
function sfwc_option_display_name_html() {

	// Get 'Options' settings.
    $sfwc_options = (array) get_option( 'sfwc_options' );
	
	// Get Display Name mode.
	$sfwc_option_display_name = ( isset( $sfwc_options['sfwc_option_display_name'] ) ) ? $sfwc_options['sfwc_option_display_name'] : 'username';
    ?>


    <input style="float:left;clear:left;position:relative;top:5px;" type="radio" name="sfwc_options[sfwc_option_display_name]" value="username" <?php checked('username', $sfwc_option_display_name, true); ?> >
    <label style="float:left;clear:right;margin-bottom:15px;" for="username"><?php esc_html_e( 'Username + Email', 'subaccounts-for-woocommerce' ); ?><br>
        <span style="color:#74808c;"><strong><?php esc_html_e( 'Example:', 'subaccounts-for-woocommerce' ); ?></strong> John.doe - [ johndoe@email.com ]</span></label><br><br>


    <input style="float:left;clear:left;position:relative;top:5px;" type="radio" name="sfwc_options[sfwc_option_display_name]" value="full_name" <?php checked('full_name', $sfwc_option_display_name, true); ?> >
    <label style="float:left;clear:right;margin-bottom:15px;" for="full_name"><?php esc_html_e( 'Full Name + Email', 'subaccounts-for-woocommerce' ); ?><br>
        <span style="color:#74808c;"><strong><?php esc_html_e( 'Example:', 'subaccounts-for-woocommerce' ); ?></strong> John Doe - [ johndoe@email.com ]</span><br>
        <em><?php esc_html_e( "If neither the First Name nor Last Name have been set, the customer's Username will be shown as a fallback.", "woocommerce_subaccounts" ); ?></em>
    </label><br><br>


    <input style="float:left;clear:left;position:relative;top:5px;" type="radio" name="sfwc_options[sfwc_option_display_name]" value="company_name" <?php checked('company_name', $sfwc_option_display_name, true); ?> >
    <label style="float:left;clear:right;margin-bottom:15px;" for="company_name"><?php esc_html_e( 'Company + Email', 'subaccounts-for-woocommerce' ); ?><br>
        <span style="color:#74808c;"><strong><?php esc_html_e( 'Example:', 'subaccounts-for-woocommerce' ); ?></strong> Enterprise Inc - [ johndoe@email.com ]</span><br>
        <em><?php esc_html_e( "If no Company Name has been set, the customer's Username will be shown as a fallback.", "woocommerce_subaccounts" ); ?></em>
    </label>

    <?php
}

function sfwc_options_show_order_meta_box_html() {

	// Get 'Options' settings
	$sfwc_options = (array) get_option('sfwc_options');

	// Get Show Meta Box value
	$sfwc_options_show_order_meta_box = ( isset( $sfwc_options['sfwc_options_show_order_meta_box'] ) ) ? $sfwc_options['sfwc_options_show_order_meta_box'] : 0;
	?>

	<input type="checkbox" id="sfwc_options_show_order_meta_box" name="sfwc_options[sfwc_options_show_order_meta_box]" value="1" <?php checked(1, $sfwc_options_show_order_meta_box, true) ?> />
	<?php
	esc_html_e( "You may need to enable this under 'Screen Options' too.", 'subaccounts-for-woocommerce' );
}




/**
 * Appearance Options Validation
 */
function sfwc_switcher_appearance_validate( $input ) {

	// Create our array for storing the validated options
	$output = array();

	// Loop through each of the incoming options
	foreach ( $input as $key => $value ) {

		// Check if the current option has a value
		if ( isset( $input[$key] ) ) {

			// if user insert a HEX color with #    	
			if ( preg_match( '/^#[0-9a-fA-F]{6}$/i', $input[$key] ) ) {

				// Sanitization should not be required here due to strict preg_match comparison, anyway...
				$output[$key] = sanitize_text_field( $input[$key] );
			} else {

				if ( $input[$key] == $input['sfwc_switcher_pane_bg_color'] ) {

					add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_hex_color', esc_html__( 'Incorrect value entered for: Background Color.', 'subaccounts-for-woocommerce' ), 'warning' );
				}
				if ( $input[$key] == $input['sfwc_switcher_pane_headline_color'] ) {

					add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_hex_color', esc_html__( 'Incorrect value entered for: Headline Color.', 'subaccounts-for-woocommerce' ), 'warning' );
				}
				if ( $input[$key] == $input['sfwc_switcher_pane_text_color'] ) {

					add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_hex_color', esc_html__( 'Incorrect value entered for: Text Color.', 'subaccounts-for-woocommerce' ), 'warning' );
				}
				if ( $input[$key] == $input['sfwc_switcher_pane_select_bg_color'] ) {

					add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_hex_color', esc_html__( 'Incorrect value entered for: Button Background Color.', 'subaccounts-for-woocommerce' ), 'warning' );
				}
				if ( $input[$key] == $input['sfwc_switcher_pane_select_text_color'] ) {

					add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_hex_color', esc_html__( 'Incorrect value entered for: Button Text Color.', 'subaccounts-for-woocommerce' ), 'warning' );
				}
			}
		}
	}

	// Return the array processing any additional functions filtered by this action
	return apply_filters('sfwc_switcher_appearance_validate', $output, $input);
}




/**
 * Settings Options Validation
 */
function sfwc_options_validate( $input ) {

	// Create our array for storing the validated options
	$output = array();



	
    // Check if the current option has a value
    if ( isset( $input['sfwc_option_display_name'] ) ) {

        $valid_radio_display_name = array( 'username', 'full_name', 'company_name' );

        if ( in_array( $input['sfwc_option_display_name'], $valid_radio_display_name ) ) {

            $output['sfwc_option_display_name'] = sanitize_text_field( $input['sfwc_option_display_name'] );

        } else {

            add_settings_error( 'sfwc_settings_messages', 'sfwc_wrong_info_email', esc_html__( 'Incorrect value entered for: Customer Display Name.', 'subaccounts-for-woocommerce' ), 'error' );
        }
    }




	// Default value if not set
	$output['sfwc_options_show_order_meta_box'] = '0';

	// Check if the current option has a value
	if ( isset( $input['sfwc_options_show_order_meta_box'] ) ) {

		$output['sfwc_options_show_order_meta_box'] = '1';
	}


	// Validate PRO settings
	do_action_ref_array('sfwc_options_validate', array(&$output, &$input));

	return $output;
}




/**
 * Create Tabbed content for settings page
 */
function sfwc_admin_page_contents() {

	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages
	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {

		// add settings saved message with the class of "updated"
		add_settings_error( 'sfwc_settings_messages', 'sfwc_settings_message', esc_html__( 'Settings Saved.', 'subaccounts-for-woocommerce' ), 'updated' );
	}

	// show error/update messages
	settings_errors('sfwc_settings_messages');


	global $sfwc_active_tab;
	$sfwc_active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'appearance';
	?>


	<h1 style="margin-top:40px;">
		<span style="font-size:30px; margin-right:5px; position:relative; top:-6px;" class="dashicons dashicons-groups"></span>
	<?php esc_html_e( 'Subaccounts for WooCommerce', 'subaccounts-for-woocommerce' ); ?>
	</h1>

	<h2>
	<?php esc_html_e('Settings Page', 'subaccounts-for-woocommerce'); ?>
	</h2>		

	<p>
	<?php esc_html_e( 'On this page you can configure the Subaccounts plugin settings.', 'subaccounts-for-woocommerce' ); ?>
	</p>



	<h2 class="nav-tab-wrapper">
	<?php
	do_action('sfwc_settings_tab');
	?>
	</h2>
	<?php
	do_action('sfwc_settings_content');
}

/**
 * Tab: 1
 */
function sfwc_appearance_tab() {
	global $sfwc_active_tab;
	?>
	<a class="nav-tab <?php echo $sfwc_active_tab == 'appearance' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url('admin.php?page=subaccounts&tab=appearance'); ?>"><?php esc_html_e('Appearance', 'subaccounts-for-woocommerce'); ?> </a>
	<?php
}
add_action('sfwc_settings_tab', 'sfwc_appearance_tab', 1);




function sfwc_appearance_tab_content() {
	global $sfwc_active_tab;
	if ( '' || 'appearance' != $sfwc_active_tab )
		return;
	?>

	<div id="sub_accounts_settings_appearance_tab">
		<div id="sub_accounts_settings_appearance_tab_left">
		<!-- <h3><?php esc_html_e( 'Frontend User Switcher Pane Appearance', 'subaccounts-for-woocommerce' ); ?></h3> -->

			<!-- Appearance content here -->
			<form method="POST" action="options.php">
			<?php
			settings_fields('sfwc_switcher_appearance_group');
			do_settings_sections('subaccounts');
			submit_button();
			?>
			</form>

		</div>


		<div id="sub_accounts_settings_appearance_tab_right">

			<h3><?php esc_html_e( 'User Switcher Pane Preview', 'subaccounts-for-woocommerce' ); ?></h3>

			<p>
				<?php
					$user_switcher_pane_translation = '<em>' . esc_html__( 'User Switcher Pane', 'subaccounts-for-woocommerce' ) . '</em>';
					$my_account_translation = '<strong>' . esc_html__( 'My Account', 'subaccounts-for-woocommerce' ) . '</strong>';
					$supervisor_add_on_translation = '<em>' . esc_html__( 'Supervisor Add-on', 'subaccounts-for-woocommerce' ) . '</em>';
					
					printf(
						esc_html__( 'This is the %1$s preview which is visible in %2$s area only for Manager account types. In case the %3$s is installed, the User Switcher Pane is also visible for Supervisor account types.', 'subaccounts-for-woocommerce' ), 
						$user_switcher_pane_translation,
						$my_account_translation,
						$supervisor_add_on_translation
					);
				?>
			</p>

			<div id="sfwc-user-switcher-pane">

				<h3><?php esc_html_e( 'You are currently logged in as:', 'subaccounts-for-woocommerce' ); ?></h3>
				<p style="color:' . $sfwc_switcher_pane_text_color . ';"><strong><?php esc_html_e( 'User:', 'subaccounts-for-woocommerce' ); ?></strong> John Doe - [ johndoe@email.com ]</p>

				<form method="post">
					<select>

						<option selected="selected" disabled><?php esc_html_e( 'Select Account', 'subaccounts-for-woocommerce' ); ?>&nbsp; &#8644;</option>
						<option>James Miller - [ jamesmiller@email.com ]</option>
						<option>Robert Williams - [ robertwilliams@email.com ]</option>
						<option>Rebecca Smith - [ rebeccasmith@email.com ]</option>

					</select>
				</form>
			</div>

			<p id="sfwc-user-switcher-pane-default-values"><?php esc_html_e( '[Restore default color scheme]', 'subaccounts-for-woocommerce' ); ?></p>
		</div>
	</div>

	<script>
	jQuery(document).ready(function ($) {

		$('#sfwc-user-switcher-pane').css('background-color', ($('#sfwc_switcher_pane_bg_color').val()));
		$('#sfwc_switcher_pane_bg_color').wpColorPicker({
			change: function (event, ui) {
				var theColor = ui.color.toString();
				$('#sfwc-user-switcher-pane').css('background-color', theColor);
			}
		});



		$('#sfwc-user-switcher-pane h3').css('color', ($('#sfwc_switcher_pane_headline_color').val()));
		$('#sfwc_switcher_pane_headline_color').wpColorPicker({
			change: function (event, ui) {
				var theColor = ui.color.toString();
				$('#sfwc-user-switcher-pane h3').css('color', theColor);
			}
		});



		$('#sfwc-user-switcher-pane p').css('color', ($('#sfwc_switcher_pane_text_color').val()));
		$('#sfwc_switcher_pane_text_color').wpColorPicker({
			change: function (event, ui) {
				var theColor = ui.color.toString();
				$('#sfwc-user-switcher-pane p').css('color', theColor);
			}
		});



		$('#sfwc-user-switcher-pane select').css('background-color', ($('#sfwc_switcher_pane_select_bg_color').val()));
		$('#sfwc_switcher_pane_select_bg_color').wpColorPicker({
			change: function (event, ui) {
				var theColor = ui.color.toString();
				$('#sfwc-user-switcher-pane select').css('background-color', theColor);
			}
		});



		$('#sfwc-user-switcher-pane select').css('color', ($('#sfwc_switcher_pane_select_text_color').val()));
		$('#sfwc_switcher_pane_select_text_color').wpColorPicker({
			change: function (event, ui) {
				var theColor = ui.color.toString();
				$('#sfwc-user-switcher-pane select').css('color', theColor);
			}
		});


		/* Restore default color scheme */
		jQuery('#sfwc-user-switcher-pane-default-values').on('click', function () {

			$('#sfwc_switcher_pane_bg_color').val('#def6ff'); // Reset input value
			$('#sfwc_switcher_pane_bg_color').closest('.wp-picker-container').children('.wp-color-result').css('background-color', '#def6ff'); // Reset wp color picker box
			$('#sfwc-user-switcher-pane').css('background-color', '#def6ff'); // Reset User Switcher preview

			$('#sfwc_switcher_pane_headline_color').val('#0088cc');
			$('#sfwc_switcher_pane_headline_color').closest('.wp-picker-container').children('.wp-color-result').css('background-color', '#0088cc');
			$('#sfwc-user-switcher-pane h3').css('color', '#0088cc');

			$('#sfwc_switcher_pane_text_color').val('#3b3b3b');
			$('#sfwc_switcher_pane_text_color').closest('.wp-picker-container').children('.wp-color-result').css('background-color', '#3b3b3b');
			$('#sfwc-user-switcher-pane p').css('color', '#3b3b3b');

			$('#sfwc_switcher_pane_select_bg_color').val('#0088cc');
			$('#sfwc_switcher_pane_select_bg_color').closest('.wp-picker-container').children('.wp-color-result').css('background-color', '#0088cc');
			$('#sfwc-user-switcher-pane select').css('background-color', '#0088cc');

			$('#sfwc_switcher_pane_select_text_color').val('#ffffff');
			$('#sfwc_switcher_pane_select_text_color').closest('.wp-picker-container').children('.wp-color-result').css('background-color', '#ffffff');
			$('#sfwc-user-switcher-pane select').css('color', '#ffffff');
		});

	});
	</script>

	<?php
}
add_action('sfwc_settings_content', 'sfwc_appearance_tab_content');




/**
 * Tab: 2
 */
function sfwc_options_tab() {
	global $sfwc_active_tab;
	?>
	<a class="nav-tab <?php echo $sfwc_active_tab == 'options' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=subaccounts&tab=options' ); ?>"><?php esc_html_e( 'Options', 'subaccounts-for-woocommerce' ); ?> </a>
	<?php
}
add_action('sfwc_settings_tab', 'sfwc_options_tab');



function sfwc_options_tab_content() {

	global $sfwc_active_tab;

	if ( 'options' != $sfwc_active_tab )
		return;
	?>

	<div id="sub_accounts_settings_options_tab">

		<h3><?php esc_html_e( 'Options', 'subaccounts-for-woocommerce' ); ?></h3>


		<!-- Appearance content here -->
		<form method="POST" action="options.php">

		<?php
		// Fake options (only available in Pro version)
		do_action('sfwc_dummy_html_markup_before_enabled_options');
		
		// Real options
		settings_fields('sfwc_options_group');
		do_settings_sections('subaccounts&tab=options');

		// Fake options (only available in Pro version)
		do_action('sfwc_dummy_html_markup_after_enabled_options');

		submit_button();
		?>

		</form>
	</div>
	<?php
}
add_action('sfwc_settings_content', 'sfwc_options_tab_content');




/**
 * Dummy content for Option tab in plugin's Settings admin page.
 *
 * Add dummy content for Option tab in case Subaccounts Pro is not installed.
 */
function sfwc_add_dummy_html_markup_before_enabled_options() {
	?>

	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row" style="padding: 15px 10px 20px 0;">
					<?php echo esc_html__('Choose who can create and add new subaccounts', 'subaccounts-for-woocommerce'); ?>
					<br>
					<a style="color:#148ff3;" href="<?php echo admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ); ?>">
					<?php echo esc_html__( 'Get Subaccounts Pro', 'subaccounts-for-woocommerce' ); ?></a>

					<span style="font-weight:400;">
						<?php echo esc_html__( 'and unlock this feature!' ); ?>
					</span>
				</th>
				<td>
					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_set_subaccounts_option" value="" disabled>
					<label for=""><?php esc_html_e('Admin Only', 'subaccounts-for-woocommerce'); ?></label><br>

					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_set_subaccounts_option" value="" disabled checked>
					<label for=""><?php esc_html_e('Customers Only', 'subaccounts-for-woocommerce'); ?></label><br>

					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_set_subaccounts_option" value="" disabled>
					<label for=""><?php esc_html_e('Both Admin and Customers', 'subaccounts-for-woocommerce'); ?></label>
					
					<p style="margin-top:8px;">
					
						<?php
							$important_strong = '<strong>' . esc_html__( 'Important:', 'subaccounts-for-woocommerce' ) . '</strong>';
							$add_subaccount_translation = '<em>' . esc_html__( 'Add Subaccount', 'subaccounts-for-woocommerce' ) . '</em>';
							$my_account_translation = '<em>' . esc_html__( 'My Account', 'subaccounts-for-woocommerce' ) . '</em>';
							$permalink_path = '<em>' . esc_html__( 'Settings > Permalinks', 'subaccounts-for-woocommerce' ) . '</em>';
							$save_changes = '<em>' . esc_html__( 'Save Changes', 'subaccounts-for-woocommerce' ) . '</em>';
							
							
							printf(
								esc_html__( '%1$s in case the %2$s menu item is not shown on %3$s page, please update the WordPress permalinks structure by going to: %4$s and clicking %5$s.', 'subaccounts-for-woocommerce' ), 
								$important_strong,
								$add_subaccount_translation,
								$my_account_translation,
								$permalink_path,
								$save_changes
							);
						?>

					</p>
				
				</td>
			</tr>

		<tbody>
	</table>
	<?php
}
add_action('sfwc_dummy_html_markup_before_enabled_options', 'sfwc_add_dummy_html_markup_before_enabled_options');




/**
 * Dummy content for Option tab in plugin's Settings admin page.
 *
 * Add dummy content for Option tab in case Subaccounts Pro is not installed.
 */
function sfwc_add_dummy_html_markup_after_enabled_options() {
	?>

	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row" style="padding: 15px 10px 20px 0;">
					<?php echo esc_html__('Show subaccounts information on WooCommerce orders list page', 'subaccounts-for-woocommerce') . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__("Add 'Account Type' and 'Parent Accounts' columns to WooCommerce orders list page in WordPress admin area.", 'subaccounts-for-woocommerce') . '"></span>'; ?>
					<br>
					<a style="color:#148ff3;" href="<?php echo admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ); ?>">
					<?php echo esc_html__( 'Get Subaccounts Pro', 'subaccounts-for-woocommerce' ); ?></a>

					<span style="font-weight:400;">
						<?php echo esc_html__( 'and unlock this feature!' ); ?>
					</span>
				</th>
				<td>
					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="checkbox" name="sfwc_columns_order" value="" disabled>
					<span style="vertical-align: middle;"><?php esc_html_e( "You may need to enable this under 'Screen Options' too.", "woocommerce_subaccounts" ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row" style="padding: 15px 10px 20px 0;">
					<?php echo esc_html__( 'Show subaccounts information on WordPress users list page', 'subaccounts-for-woocommerce' ) . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( "Add 'Account Type' and 'Parent Accounts' columns to users list page in WordPress admin area.", "woocommerce_subaccounts" ) . '"></span>'; ?>
					<br>
					<a style="color:#148ff3;" href="<?php echo admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ); ?>">
					<?php echo esc_html__( 'Get Subaccounts Pro', 'subaccounts-for-woocommerce' ); ?></a>

					<span style="font-weight:400;">
						<?php echo esc_html__( 'and unlock this feature!' ); ?>
					</span>
				</th>
				<td>
					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="checkbox" name="sfwc_columns_users" value="" disabled>
					<span style="vertical-align: middle;"><?php esc_html_e( "You may need to enable this under 'Screen Options' too.", "woocommerce_subaccounts" ); ?></span>
				</td>
			</tr>
			<tr>
				<th scope="row" style="padding: 15px 10px 20px 0;">
					<?php echo esc_html__('Add subaccounts information to new order emails', 'subaccounts-for-woocommerce') . '<span class="tips woocommerce-help-tip" data-tip="' . esc_attr__( 'Show customer\'s Manager and Supervisor information in new order emails.', "woocommerce_subaccounts" ) . '"></span>'; ?>
					<br>
					<a style="color:#148ff3;" href="<?php echo admin_url( '/admin.php?checkout=true&page=subaccounts-pricing&plugin_id=10457&plan_id=17669&pricing_id=19941&billing_cycle=annual' ); ?>">
					<?php echo esc_html__( 'Get Subaccounts Pro', 'subaccounts-for-woocommerce' ); ?></a>

					<span style="font-weight:400;">
						<?php echo esc_html__( 'and unlock this feature!' ); ?>
					</span>
				</th>
				<td>
					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_email_option" value="" disabled checked>
					<label for=""><?php esc_html_e('No', 'subaccounts-for-woocommerce'); ?></label><br>

					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_email_option" value="" disabled>
					<label for=""><?php esc_html_e('For Admin Only', 'subaccounts-for-woocommerce'); ?></label><br>

					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_email_option" value="" disabled>
					<label for=""><?php esc_html_e('For Customer Only', 'subaccounts-for-woocommerce'); ?></label><br>

					<input title="<?php esc_attr_e( 'This feature is only available on Subaccounts Pro', 'subaccounts-for-woocommerce'); ?>" type="radio" name="sfwc_email_option" value="" disabled>
					<label for=""><?php esc_html_e('For both Admin and Customer', 'subaccounts-for-woocommerce'); ?></label>
					
					<p style="margin-top:8px;">
					
						<?php
							$important_strong = '<strong>' . esc_html__( 'Important:', 'subaccounts-for-woocommerce' ) . '</strong>';
							$email_settings_path = '<em>' . esc_html__( 'WooCommerce > Settings > Emails', 'subaccounts-for-woocommerce' ) . '</em>';
							
							
							printf(
								esc_html__( '%1$s make sure email notifications are properly set and enabled in: %2$s.', 'subaccounts-for-woocommerce' ), 
								$important_strong,
								$email_settings_path
							);
						?>

					</p>
				</td>
			</tr>

		<tbody>
	</table>
	<?php
}
add_action('sfwc_dummy_html_markup_after_enabled_options', 'sfwc_add_dummy_html_markup_after_enabled_options');




/**
 * Color picker scripts.
 *
 * Required for Appearance tab in plugin's Settings admin page.
 */
function sfwc_enqueue_color_picker() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
add_action('admin_enqueue_scripts', 'sfwc_enqueue_color_picker');

function sfwc_admin_inline_js_color_picker() {
	echo "<script>
		jQuery(document).ready(function($){
		$('.sfwc-color-field').wpColorPicker();
		});
		</script>";
}
add_action('admin_print_footer_scripts', 'sfwc_admin_inline_js_color_picker');






/**
 * Register new endpoint "Add Subaccount" to use for My Account page.
 * 
 *		-------------------------------------------------
 * 		Remember to update Permalinks to avoid 404 error.
 *		-------------------------------------------------
 *
 * Do NOT move this piece of code to: subaccounts-for-woocommerce > public > my-account.php
 * Other code in: subaccounts-for-woocommerce > public > my-account.php
 * See: "Add Subaccount" menu item to My Acount page.
 */
function sfwc_add_subaccount_endpoint() {
    add_rewrite_endpoint( 'add-subaccount', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'sfwc_add_subaccount_endpoint' );