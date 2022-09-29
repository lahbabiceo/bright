<?php
class Brighty_WC_Endpoint {

	/**
	 * Custom endpoint name.
	 *
	 * @var string
	 */
	public  $endpoint = 'notifications-list';
	public  $file = '';
    public $title;
    public $name= '';

	/**
	 * Plugin actions.
	 */
	public function __construct($name,$title,$filelocation) {
        $this->endpoint = $name;
        $this->endpoint_title($title);
        $this->file = $filelocation;
        $this->title = $title;


		// Actions used to insert a new endpoint in the WordPress.
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'woocommerce_get_query_vars', array( $this, 'get_query_vars' ), 0 );

		// Change the My Accout page title.
		add_filter( 'the_title', array( $this, 'endpoint_title' ) );

		// Insering your new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
		add_action( 'woocommerce_account_' . $this->endpoint .  '_endpoint', array( $this, 'endpoint_content' ) );
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( $this->endpoint,  EP_PAGES );

	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function get_query_vars( $vars ) {

        
		$vars[ $this->endpoint ] = $this->endpoint;

		return $vars;
	}

	/**
	 * Set endpoint title.
	 *
	 * @param string $title
	 * @return string
	 */
	public function endpoint_title( $title ) {

		global $wp_query;
        $endpoint = $this->endpoint;

		$is_endpoint = isset( $wp_query->query_vars[ $endpoint ] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			// New page title.
			$title = __( $this->title, 'woocommerce' );

			remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
		}

		return $title;
	}

	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @param array $items
	 * @return array
	 */
	public function new_menu_items( $items ) {
		// Remove the logout menu item.
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

		// Insert your custom endpoint.
		$items[ $this->endpoint ] = __( $this->title, 'woocommerce' );

		// Insert back the logout item.
		$items['customer-logout'] = $logout;

		return $items;
	}

	/**
	 * Endpoint HTML content.
	 */
	public function endpoint_content() {
		
	    require_once($this->file);
		// You can load a template here with wc_get_template( 'myaccount/my-custom-endpoint.php' );
	}

	/**
	 * Plugin install action.
	 * Flush rewrite rules to make our custom endpoint available.
	 */
	public static function install() {
		flush_rewrite_rules();
	}
}



$notifications_list_endpoint = new Brighty_WC_Endpoint("notifications-list","Notifications",BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/notifications-list.php');

$documents_endpoint = new Brighty_WC_Endpoint("documents","Documents",BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/documents.php');
$security_endpoint = new Brighty_WC_Endpoint("security","Account Security",BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/security.php');
$profile_endpoint = new Brighty_WC_Endpoint("profile","Profile",BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/edit-account.php');

// Flush rewrite rules on plugin activation.
register_activation_hook( __FILE__, array( 'Brighty_WC_Endpoint', 'install' ) );
