<?php 

require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');

$user = wp_get_current_user() ;
?>
<!-- ***logout from everywhere
***2factor authentication
*** remove gdpr data
*** delete account -->

<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;


$sessions = WP_Session_Tokens::get_instance( get_current_user_id() );

 ?>


<div class="row">


	<div class="col-6 " id="tfa-settings-panel">

		<div class="accordion" id="accordionExample">
			<div class="accordion-item card mb-3 shadow bg-white border-warning  ">
				<div class="accordion-header p-4" id="headingOne">
						<h2 class="h5 mb-4">Two Factor Authentication </h2>
						<p class="description">
							Protect your account with Two Factor Authentication. Use your favorite authenticator app to approve login and stay safe.
						</p>
						<button onclick="jQuery('#tfa-settings-panel').toggleClass('col-12')" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Setup Two Factor Authentication 
						</button>
				</div>
				<div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
					<div class="accordion-body">
						<?php echo do_shortcode('[twofactor_user_settings]'); ?>
					</div>
				</div>
			</div>
		</div>

	</div>


	<div class="col-6">
		<div class="card shadow">
			<div class="card-body">
				<?php if (  count( $sessions->get_all() ) === 1 ) : ?>
										<tr class="user-sessions-wrap hide-if-no-js">
											<h2 class="h5 mb-4"><?php _e( 'Sessions' ); ?></h2>
											<td aria-live="assertive">
												<p class="description">
													<?php _e( 'You are logged in only at this location. If you forget to logout elsewhere in future, You can use button below to log out everywhere else.' ); ?>
												</p>
												<div class="destroy-sessions"><button type="button" disabled class="button btn btn-outline-primary <?php if($_GET['cleared']){ echo "btn-outline-success"; } ?> "><?php if($_GET['cleared']){ echo "You have been logged out everywhere else"; } else { _e( 'Log Out Everywhere Else' ); } ?></button></div>
												
											</td>
										</tr>
									<?php elseif ( count( $sessions->get_all() ) > 1 ) : ?>
										<tr class="user-sessions-wrap hide-if-no-js">
											<h2 class="h5 mb-4"><?php _e( 'Sessions' ); ?></h2>
											<td aria-live="assertive">
												<p class="description">
													<?php _e( 'Did you lose your phone or leave your account logged in at a public computer? You can log out everywhere else, and stay logged in here.' ); ?>
												</p>
												<div class="destroy-sessions"><a href="/wp-admin/admin-ajax.php?action=brighty_logout_everywhere_else"><button  type="button" class="btn btn-primary" id="destroy-sessions"><?php _e( 'Log Out Everywhere Else' ); ?></button></a></div>
												
											</td>
										</tr>
									<?php elseif ( $sessions->get_all() ) : ?>
										<tr class="user-sessions-wrap hide-if-no-js">
											<th><?php _e( 'Sessions' ); ?></th>
											<td>
												<p><button type="button" class="button" id="destroy-sessions"><?php if($_GET['cleared']){ echo "You have been logged our everywhere else"; } else { _e( 'Log Out Everywhere' );} ?></button></p>
												<p class="description">
													<?php
													/* translators: %s: User's display name. */
													printf( __( 'Log %s out of all locations.' ), $profile_user->display_name );
													?>
												</p>
											</td>
										</tr>
									<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="col-6 " id="password-change-panel">

		<div class="accordion" id="passwordChange">
			<div class="accordion-item card mb-3 shadow bg-white border-warning  ">
				<div class="accordion-header p-4" id="headingTwo">
						<h2 class="h5 mb-4">Change Password </h2>
						<p class="description">
							You can change your password here. 
						</p>
						<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
							Change Password
						</button>
				</div>
				<div id="collapseTwo" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
					<div class="accordion-body">
													
							<form class="woocommerce-EditAccountForm edit-account card" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

							<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

							<div class="card-body">
								<fieldset >
									<h2 class="h5 mb-4"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></h2>

									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide description">
										<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
										<input type="password" class="woocommerce-Input woocommerce-Input--password  form-control" name="password_current" id="password_current" autocomplete="off" />
									</p>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide description mt-3">
										<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
										<input type="password" class="woocommerce-Input woocommerce-Input--password  form-control" name="password_1" id="password_1" autocomplete="off" />
									</p>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide description mt-3">
										<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
										<input type="password" class="woocommerce-Input woocommerce-Input--password  form-control" name="password_2" id="password_2" autocomplete="off" />
									</p>
								</fieldset>
								
								<div class="clear"></div>

								<?php do_action( 'woocommerce_edit_account_form' ); ?>

								<p>
									<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
									<button class="btn btn-primary btn-sm mt-3" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
									<input type="hidden" name="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
									<input type="hidden" name="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
									<input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name) ; ?>" />
									<input type="hidden" name="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
									<input type="hidden" name="action" value="save_account_details" />
								</p>

								<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
							</div>
							</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>



					</div>
				</div>
			</div>
		</div>

	</div>

</div>



<?php do_action( 'woocommerce_after_edit_account_form' ); ?>








<script>
	

</script>
