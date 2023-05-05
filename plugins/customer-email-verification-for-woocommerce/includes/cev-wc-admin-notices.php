<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_CEV_Admin_Notices_Under_WC_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WC_CEV_Admin_Notices_Under_WC_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {
		add_action('init', array( $this, 'admin_notices_for_cev_pro' ) );
	}

	public function admin_notices_for_cev_pro() {

		if ( class_exists( 'customer_email_verification_pro' ) ) {
			return;
		}
		
		if ( ! class_exists( 'Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes' ) ) {
			return;
		}
		
		$already_set = get_transient( 'cev_pro_wc_admin' );
		
		if ( 'yes' == $already_set ) {
			return;
		}
		
		set_transient( 'cev_pro_wc_admin', 'yes' );				
		
		$note_name = 'cev_pro_notice';
		$data_store = WC_Data_Store::load( 'admin-note' );		
		
		// Otherwise, add the note
		$activated_time = current_time( 'timestamp', 0 );
		$activated_time_formatted = gmdate( 'F jS', $activated_time );
		$note = new Automattic\WooCommerce\Admin\Notes\WC_Admin_Note();
		$note->set_title( 'Upgrade to Customer Email Verification Pro!' );
		$note->set_content( 'The pro version allows you to require customers to verify their email address before they can checkout on your store, you can delay the new account email notification to after successful verification, require verification when the email address is updated from the account, set validation code expiration and more..' );
		$note->set_content_data( (object) array(
			'getting_started'     => true,
			'activated'           => $activated_time,
			'activated_formatted' => $activated_time_formatted,
		) );
		$note->set_type( 'info' );
		$note->set_layout('plain');
		$note->set_image('');
		$note->set_name( $note_name );
		$note->set_source( 'Customer Verification Pro' );
		$note->set_layout('plain');
		$note->set_image('');
		// This example has two actions. A note can have 0 or 1 as well.
		$note->add_action(
			'settings', 'Upgrade to Pro Now', 'https://www.zorem.com/product/customer-email-verification-for-woocommerce/'
		);		
		$note->save();
	}
			
}

/**
 * Returns an instance of WC_CEV_Admin_Notices_Under_WC_Admin.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return WC_CEV_Admin_Notices_Under_WC_Admin
*/
function WC_CEV_Admin_Notices_Under_WC_Admin() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new WC_CEV_Admin_Notices_Under_WC_Admin();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
WC_CEV_Admin_Notices_Under_WC_Admin();
