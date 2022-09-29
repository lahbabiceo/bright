<?php
/**
 * Html code for settings tab
 */
?>
<section id="cev_content_settings" class="cev_tab_section">
	<form method="post" id="cev_settings_form" action="" enctype="multipart/form-data"><?php #nonce ?>
		<h2 class="tab_section_heading botton_border"><?php esc_html_e( 'Customer email verification', 'customer-email-verification-for-woocommerce' ); ?></h2>
		<?php $this->get_html( $this->get_cev_settings_data() ); ?>
		<?php $this->get_html_new( $this->get_cev_settings_data_new() ); ?>
		<div class="submit cev-btn">			
			<button name="save" class="cev_settings_save button-primary woocommerce-save-button" type="submit" value="Save changes"><?php esc_html_e( 'Save changes', 'customer-email-verification-for-woocommerce' ); ?></button>
			<?php wp_nonce_field( 'cev_settings_form_nonce', 'cev_settings_form_nonce' ); ?>
			<div class="spinner  " style="float:none"></div>
			<input type="hidden" name="action" value="cev_settings_form_update">
		</div>
	</form>
</section>
