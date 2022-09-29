<?php
/**
 * Registration Form
 * @package Brighty
 * @version 1.0.0
 */

$user = wp_get_current_user(); 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


 ?>

      

            <div class="woocommerce row align-items-center justify-content-center col-12" >
   

                <?php 
                

                    if(is_user_logged_in()){

                        $html = '
                            <div class="spinner-border" role="status">
                            </div>
                            <script> 
                                    window.location.href = "/my-account";
                            </script>
                        ';

                        echo $html;

                        exit;

                        
                    }


                
                
                do_action( 'woocommerce_before_customer_login_form' );
                
                

                ?>
            
                <form method="post" class="mt-3 woocommerce-form woocommerce-form-register register brighty-register-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <!---start: brighty form ---->

                    <div class="row">
                        <div class="col-6">
                            <div class=" form-group mb-4 form-floating">
                                <input autofocus type="text" class="form-control" name="billing_first_name" id="billing_first_name" value="<?php echo isset($_POST['billing_first_name']) ? esc_attr($_POST['billing_first_name']) : $user->billing_first_name ?>" placeholder="<?php _e( 'First Name', 'woocommerce' ); ?>" />

                                <label for="billing_first_name"><?php _e( 'First Name', 'woocommerce' ); ?> </label>
                            </div> 
                        </div>
                        <div class="col-6">
                            <div class="form-group form-floating">
                                <input type="text" class="input-text form-control"name="billing_last_name" id="billing_last_name" value="<?php echo isset($_POST['billing_last_name']) ? esc_attr($_POST['billing_last_name']) : $user->billing_first_name ?>" placeholder="<?php _e( 'Last Name', 'woocommerce' ); ?>" />
                                <label for="billing_last_name"><?php _e( 'Last Name', 'woocommerce' ); ?> </label>
                            </div> 
                        </div>
                    </div>


                    <div class="clear"></div> 


                    <div class=" form-group mb-4 form-floating">
                        <input type="text" class="input-text form-control" name="billing_company" id="billing_company" value="<?php echo isset($_POST['billing_company']) ? esc_attr($_POST['billing_company']) : $user->billing_first_name ?>" placeholder="<?php _e( 'Company Name', 'woocommerce' ); ?>" />

                        <label for="billing_company"><?php _e( 'Company Name', 'woocommerce' ); ?> </label>
                    </div> 


                    <div class="clear"></div> 


                    <div class=" form-group mb-4 form-floating">
                        <input type="text" class="input-text form-control" name="billing_address_1" id="billing_address_1" value="<?php echo isset($_POST['billing_address_1']) ? esc_attr($_POST['billing_address_1']) : $user->billing_first_name ?>" placeholder="<?php _e( 'Address', 'woocommerce' ); ?>" />
                        <label for="billing_address_1"><?php _e( 'Address', 'woocommerce' ); ?></label>
                    </div> 

                    <div class="clear"></div> 


                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-4 form-floating ">
                                <input type="text" class="input-text form-control" name="billing_city" id="billing_city" value="<?php echo isset($_POST['billing_city']) ? esc_attr($_POST['billing_city']) : $user->billing_first_name ?>" placeholder="<?php _e( 'City', 'woocommerce' ); ?>" />
                                <label for="billing_city"><?php _e( 'City', 'woocommerce' ); ?> </label>
                            </div> 
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-4 form-floating">
                                <input type="text" placeholder="<?php _e( 'State', 'woocommerce' ); ?>" class="input-text form-control" name="billing_state" id="billing_state" value="<?php echo isset($_POST['billing_state']) ? esc_attr($_POST['billing_state']) : $user->billing_first_name ?>" placeholder="<?php _e( 'State', 'woocommerce' ); ?>" />
                                <label for="billing_state"><?php _e( 'State', 'woocommerce' ); ?> </label>
                            </div> 
                        </div>
                    </div>

                    <div class="clear"></div> 


                    <div class="row">
                        <div class="col-6">
                            <div class="woocommerce-form-row  woocommerce-form-row--wide form-row form-group mb-4 form-floating">
                                <input type="text" class="input-text form-control" name="billing_postcode" id="billing_postcode" value="<?php echo isset($_POST['billing_postcode']) ? esc_attr($_POST['billing_postcode']) : $user->billing_first_name ?>" placeholder="<?php _e( 'Postcode', 'woocommerce' ); ?> " />
                                <label for="billing_postcode"><?php _e( 'Pincode', 'woocommerce' ); ?> </label>
                            </div> 
                        </div>
                        <div class="col-6">
                            <?php 
                                global $woocommerce;    
                                woocommerce_form_field( 'billing_country', array( 'input_class' => array('input-text','form-select'),'type' => 'country', 'class'=>'woocommerce-form-row  woocommerce-form-row--wide form-row form-floating',  ) ); 
                            ?>
                        </div>
                    </div>


                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-group  form-floating ">
                        <input type="text" class="input-text form-control" name="billing_phone" id="billing_phone" value="<?php echo isset($_POST['billing_phone']) ? esc_attr($_POST['billing_phone']) : $user->billing_first_name ?>" placeholder="<?php _e( 'Phone', 'woocommerce' ); ?> " />
                        <label for="billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?> </label>
                    </div> 


                    <div class="clear"></div> 


                    <div class="form-group mb-4">
                        <label for="email"></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg> 
                            </span>
                            <input name="email" type="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" class="form-control" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" id="email"  required="">
                        </div>
                    </div>
                
                    
                    <div class="form-group mb-4">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon2">
                                <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg> 
                            </span>
                            <input type="password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" class="form-control" id="confirm_password" required=""  name="password" autocomplete="new-password" >
                        </div>
                    </div>


                    <?php do_action( 'woocommerce_register_form' ); ?>

                    <p class="woocommerce-form-row form-row">
                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn btn-gray-800 w-100" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                    </p>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>

           
            <div class="d-flex justify-content-center align-items-center mt-4">
              <span class="fw-normal"><?php _e( 'Already Registered?', 'brighty-core' ); ?> <a href="/login" class="fw-bold"><?php _e( 'Sign In', 'woocommerce' ); ?></a>
              </span>
            </div>

          
            <?php do_action( 'woocommerce_after_customer_login_form' ); ?>