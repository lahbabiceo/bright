<?php

$more_plugins = array(
	0 => array(
		'title' => 'Advanced Shipment Tracking Pro',
		'description' => 'Advanced Shipment Tracking comes with powerful features to help you manage & automate the WooCommerce fulfillment workflow.',
		'url' => 'https://www.zorem.com/product/woocommerce-advanced-shipment-tracking/?utm_source=wp-admin&utm_medium=ast-pro&utm_campaign=add-ons',
		'image' => 'ast-icon.png',
		'width' => '140px',
		'file' => 'ast-pro/ast-pro.php'
	),
	1 => array(
		'title' => 'SMS for WooCommerce',
		'description' => 'Keep your customers informed by sending them automated SMS text messages with order & delivery updates. You can send SMS notifications to customers when the order status is updated or when the shipment is out for delivery and more…',
		'url' => 'https://www.zorem.com/products/sms-for-woocommerce/?utm_source=wp-admin&utm_medium=SMSWOO&utm_campaign=add-ons',
		'image' => 'smswoo-icon.png',
		'width' => '90px',
		'file' => 'sms-for-woocommerce/sms-for-woocommerce.php'
	),
	2 => array(
		'title' => 'Advanced Local Pickup',
		'description' => 'The Advanced Local Pickup (ALP) helps you manage the local pickup orders workflow more conveniently by extending the WooCommerce Local Pickup shipping method. The Pro you set up multiple pickup locations, , split the business hours, apply discounts by pickup location, display local pickup message on the products pages, allow customers to choose pickup location per product, force products to be local pickup only and more…',
		'url' => 'https://www.zorem.com/product/advanced-local-pickup-pro/?utm_source=wp-admin&utm_medium=SMSWOO&utm_campaign=add-ons',
		'image' => 'alp-icon.png',
		'width' => '60px',
		'file' => 'advanced-local-pickup-pro/advanced-local-pickup-pro.php'
	),
	3 => array(
		'title' => 'Advanced Order Status Manager',
		'description' => 'The Advanced Order Status Manager allows store owners to manage the WooCommerce orders statuses, create, edit, and delete custom Custom Order Statuses and integrate them into the WooCommerce orders flow.',
		'url' => 'https://www.zorem.com/products/advanced-order-status-manager/?utm_source=wp-admin&utm_medium=OSM&utm_campaign=add-ons',
		'image' => 'osm-icon.png',
		'width' => '60px',
		'file' => 'advanced-order-status-manager/advanced-order-status-manager.php'
	),
	4 => array(
		'title' => 'Sales Report Email Pro',
		'description' => 'The Sales Report Email Pro will help know how well your store is performing and how your products are selling by sending you a daily, weekly, or monthly sales report by email, directly from your WooCommerce store.',
		'url' => 'https://www.zorem.com/products/sales-report-email-for-woocommerce/?utm_source=wp-admin&utm_medium=SRE&utm_campaign=add-ons',
		'image' => 'sre-icon.png',
		'width' => '60px',
		'file' => 'sales-report-email-pro-addon/sales-report-email-pro-addon.php'
	),		
	5 => array(
		'title' => 'Country Based Restrictions Pro',
		'description' => 'The country-based restrictions plugin by zorem works by the WooCommerce Geolocation or the shipping country added by the customer and allows you to restrict products on your store to sell or not to sell to specific countries.',
		'url' => 'https://www.zorem.com/products/country-based-restriction-pro/?utm_source=wp-admin&utm_medium=CBR&utm_campaign=add-ons',
		'image' => 'cbr-icon.png',
		'width' => '70px',
		'file' => 'country-based-restriction-pro-addon/country-based-restriction-pro-addon.php'
	),	
);

?>
<section id="cev_content_addons" class="cev_tab_section">
	<div class="d_table addons_page_dtable" style="">        	
		<section id="content_tab_addons" class="">        				
			
			<div class="cev-pro-section">
				<div class="cev-row">
					<div class="cev-col-6">
						<div class="cev_col_inner">
							<h1 class="cev_pro_landing_header">Customer Email Verification Pro</h1>
							<ul class="cev_pro_features_list">								
								<li>Email verification on checkout</li>
								<li>Verification for free orders only</li>
								<li>Automatic account email verification</li>
								<li>Checkout verification type</li>			
								<li>Fully customize the verification process</li>
								<li>Delay new-account email</li>
								<li>Re-verify customer email address</li>							
								<li>Verification expiration</li>
								<li>Limit resend</li>
								<li>Bulk resend and auto-delete</li>
							</ul>
							<a href="https://woocommerce.com/products/customer-email-verification/" class="button button-primary btn_cev2 btn_large" target="_blank">UPGRADE NOW</a>							
						</div>
					</div>									
					<div class="cev-col-6">
						<div class="cev_pro_landing_banner">
							<img src="<?php echo esc_url( woo_customer_email_verification()->plugin_dir_url() ); ?>assets/images/cev-banner.png">
						</div>
					</div>
				</div>
			</div>
			
			<div class="plugins_section zorem_plugin_section">				
				<div class="zorem_plugin_container">
					<?php foreach ( $more_plugins as $mplugin ) { ?>
						<div class="zorem_single_plugin">
							<div class="free_plugin_inner">
								<div class="plugin_image">
									<img src="<?php echo esc_url( woo_customer_email_verification()->plugin_dir_url() ); ?>assets/images/<?php esc_html_e( $mplugin['image'] ); ?>" width="<?php esc_html_e( $mplugin['width'] ); ?>">
								</div>
								<div class="plugin_description">
									<h3 class="plugin_title"><?php esc_html_e( $mplugin['title'] ); ?></h3>
									<p><?php esc_html_e( $mplugin['description'] ); ?></p>
									<?php 
									if ( is_plugin_active( $mplugin['file'] ) ) {
										?>
									<button type="button" class="button button button-primary btn_blue2">Active</button>
								<?php } else { ?>
									<a href="<?php esc_html_e( $mplugin['url'] ); ?>" class="button button-primary btn_cev2" target="blank">more info</a>
								<?php } ?>								
								</div>
							</div>	
						</div>	
					<?php } ?>						
				</div>
			</div>
		</section>						
	</div>
</section>
