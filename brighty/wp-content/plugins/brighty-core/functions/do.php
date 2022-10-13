<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;





/**
* Customizer additions.
*/




require BRIGHTY_CORE_PLUGIN_DIR . '/functions/helper-functions.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/required-plugins.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/customizer.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/shortcodes/registration-shortcode.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/shortcodes/invoices-shortcode.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/menu-locations.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/register-page-templates.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/remove-clutter.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/ajax-endpoints.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/enque-css.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/wc-endpoints.php';
require BRIGHTY_CORE_PLUGIN_DIR . '/functions/update-account-details-woo.php';



//add tabs to my account menu 

// START: :::add notification endpoint

// add_action('init', function() {
// 	add_rewrite_endpoint('notifications-list',  EP_PAGES);
// });

// add_filter('woocommerce_account_menu_items', function($items) {
	
// 	$items['notifications-list'] = __('Notifications', 'brighty-core');
// 	return $items;
// });

// add_action('woocommerce_account_notifications-list_endpoint', function() {
    
// });


//add code to woocommerce myaccount page (main dashboard) 


add_action( 'woocommerce_before_my_account', 'brighty_woocommerce_account_content' );

function brighty_woocommerce_account_content(  ) {

    global $current_user; 
    include BRIGHTY_CORE_PLUGIN_DIR . '/templates/woocommerce/myaccount.php';
    
}

//add code to woocommerce myaccount account-details page

add_action( 'woocommerce_before_edit_account_form', 'action_woocommerce_account_edit' );

function action_woocommerce_account_edit(  ) {

    global $current_user; 
    include BRIGHTY_CORE_PLUGIN_DIR . '/templates/woocommerce/edit-account.php';
    
}

//add code to woocommerce order list

add_action( 'woocommerce_before_account_orders', 'action_woocommerce_list_orders' );

function action_woocommerce_list_orders(  ) {

    include BRIGHTY_CORE_PLUGIN_DIR . '/templates/woocommerce/list-orders.php';
    
}

//add my account menu


add_action( 'woocommerce_account_navigation', 'brighty_add_my_account_top_menu' );

function brighty_add_my_account_top_menu(  ) {

    include BRIGHTY_CORE_PLUGIN_DIR . '/templates/woocommerce/my-account-top-menu.php';
    
}


// add a top level menu to the top

add_action( 'admin_menu', 'brighty_register_custom_menu_link' );
/**
 * @author    Brad Dalton
 * @example   http://wpsites.net/wordpress-admin/add-top-level-custom-admin-menu-link-in-dashboard-to-any-url/
 * @copyright 2014 WP Sites
 */
function brighty_register_custom_menu_link(){

    global $menu;
    add_menu_page( 'Sales', 'Sales', 'manage_options', 'sales', 'brighty_get_sales_page', 'dashicons-media-interactive', 3 ); 
    add_menu_page( 'Customers', 'Customers', 'manage_options', 'customers', 'brighty_get_customers_page', 'dashicons-media-interactive', 3 ); 
    $menu[4] = ['', 'read', '', '', 'wp-menu-separator'];
}

function brighty_get_sales_page(){
    

    //TODO : return Content of this page
    
    echo "hello here comes the sales page dashboard";


	exit;
}


function brighty_get_customers_page(){

    //TODO : return Content of this page
    
    echo "hello here comes the sales page dashboard";

	exit;
}



/// add widget on the dashboard

add_action('wp_dashboard_setup', 'brighty_dashboard_widgets');
  
function brighty_dashboard_widgets() {
        
    global $wp_meta_boxes;
    wp_add_dashboard_widget('brighty_my_todo_items', 'My To Do Items', 'brighty_my_todo_items',$priority = 'low');

}
 
function brighty_my_todo_items() {
    echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="https://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
}

 