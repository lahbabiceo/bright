<?php 
   $user = wp_get_current_user() ;

   require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');
   $editable = is_wc_endpoint_url( 'edit-account' );


?>
<div class="row">






      <div class="col-12 col-xl-8">

         <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

            <div class="card  card-body border-0 shadow mb-4 profile-card <?php  if(!$editable){ echo 'no-edit'; }  ?>">
                  <h2 class="h5 mb-4">General information</h2>
                  <a class="btn btn-primary btn-sm edit-profile right-5" href="/my-account/edit-account/" class="edit">
                  <i class="fa fa-edit"></i>   
                  Edit</a>

                  <span onclick="jQuery('.profile-card').toggleClass('no-edit');jQuery('.profile-card input,.profile-card select').attr('disabled','')" class="btn btn-primary btn-sm close right-5" >
                  <i class="fa fa-close"></i>   
                  Cancel</span>
               
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <div>
                              <label for="first_name">First Name</label> 
                           
                              <input <?php  if(!$editable){ echo 'disabled '; }  ?> class="form-control" name="account_first_name"  type="text" autocomplete="given-name" placeholder="Tariq Abdullah"  value="<?php echo esc_attr( $user->first_name ); ?>">

                              
                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <div>
                              <label for="last_name">Last Name</label> 
                              <input <?php  if(!$editable){ echo 'disabled '; }  ?>   class="form-control" type="text" placeholder="Also your last name"
                              name="account_last_name" id="last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>"
                              >
                           </div>
                        </div>
                     </div>


                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <div>
                              <label for="first_name">Display Name</label> 
                              <input <?php  if(!$editable){ echo 'disabled '; }  ?>   class="form-control" id="phone" type="text" placeholder="Enter Display  Name" name="account_display_name" value="<?php echo esc_attr( $user->display_name) ; ?>">
                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <div>
                              <label for="first_name">Company Name</label> 
                              <input <?php  if(!$editable){ echo 'disabled '; }  ?>  class="form-control" id="phone" type="text" placeholder="Enter Company  Name" name="billing_company" value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_company',true) ); ?>">
                           </div>
                        </div>
                     </div>



                     <div class="row align-items-center">
                        <div class="col-md-6 mb-3">
                           <label for="birthday">Birthday</label>
                           <div class="input-group">
                              <span class="input-group-text">
                                 <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                 </svg>
                              </span>
                              <input <?php  if(!$editable){ echo 'disabled '; }  ?>  name="dob"  data-datepicker="" class="form-control datepicker-input" id="birthday" type="text" placeholder="dd/mm/yyyy"
                              value="<?php echo esc_attr( get_user_meta($user->ID, 'dob',true) ); ?>" >
                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <label for="gender">Gender</label> 
                           <select <?php  if(!$editable){ echo 'disabled '; }  ?>  name="gender" class="form-select mb-0" id="gender" aria-label="Gender select example">
                              <option selected="selected">Gender</option>
                              <option value="1" <?php 
                              if(get_user_meta($user->ID, 'gender',true)==1){ echo "selected"; } ?>>Female</option>
                              <option value="2"  <?php 
                              if(get_user_meta($user->ID, 'gender',true)==2){ echo "selected"; } ?>>Male</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <div class="form-group">
                              <label for="email">Email</label> 
                              <input class="form-control" <?php  if(!$editable){ echo 'disabled '; }  ?>  id="email" type="email" placeholder="name@company.com" autocomplete="email" name="account_email"
                              value="<?php echo esc_attr( $user->user_email ); ?>"
                              >

                             

                           </div>
                        </div>
                        <div class="col-md-6 mb-3">
                           <div class="form-group">
                              <label for="phone">Phone</label> 
                              <input class="form-control"  <?php  if(!$editable){ echo 'disabled '; }  ?>  id="phone" type="text" placeholder="Enter Phone Number" required=""
                              name="billing_phone"
                              value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_phone',true) ); ?>">
                           </div>
                        </div>
                     </div>
                  
                  
                     <div class="mt-3">
                        <button name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" class="save-btn btn btn-gray-800 mt-2 animate-up-2" type="submit">
                           Save all
                        </button>
                     </div>
               
               </div>
            
               


            <div class="card no-edit card-body border-0 shadow mb-4 address-card">

                  <h2 class="h5 mb-4">Location</h2>
                  <a href="/my-account/edit-address/billing"  class="btn btn-primary btn-sm edit-profile right-5" href="http://localhost:8080/my-account/edit-address/billing/" class="edit">
                     <i class="fa fa-edit"></i>   
                     Edit
                  </a>

                  <div class="row">
                     <div class="col-sm-12 mb-3">
                        <div class="form-group"><label for="address">Address</label> <input  disabled  class="form-control" id="address" type="text" placeholder="Enter your home address" required="" value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_address_1',true)  ); echo " ";
                        echo esc_attr( get_user_meta($user->ID, 'billing_address_2',true)  );
                        ?>"></div>
                     </div>
                     
                  </div>

                  <div class="row">
                     <div class="col-sm-4 mb-3">
                        <div class="form-group">
                           <label for="city">City</label> 
                           <input class="form-control"  disabled  id="city" type="text" placeholder="City" required=""
                           value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_city',true)  ); ?>">
                        </div>
                     </div>

                     <div class="col-sm-4 mb-3">
                        <label for="state">State</label> 
                        <input class="form-control"  disabled  id="city" type="text" placeholder="City" required=""
                           value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_state',true)  ); ?>">
                     </div>

                     <div class="col-sm-4">
                        <div class="form-group"><label for="zip">ZIP</label> <input class="form-control"  disabled  id="zip" type="tel" placeholder="ZIP" required="" value="<?php echo esc_attr( get_user_meta($user->ID, 'billing_postcode',true)  ); ?>"></div>
                     </div>
                  </div>
                  
                     
               </div>
            
               
         
               <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		
             
		
      
               <input type="hidden" name="action" value="save_account_details" />

      
            
            
         </form>

         <div class="card shadow border-0 text-center brighty-avatar-upload-section">
               <?php echo do_shortcode('[avatar_upload]'); ?>
         </div>

      </div>
  

   <div class="col-12 col-xl-4">
      <div class="row">
         <div class="col-12 mb-4">
            <div class="card shadow border-0 text-center p-0">
               <div class="profile-cover rounded-top"  style="background: url(&quot;<?php

               $default = BRIGHTY_CORE_PLUGIN_URL.'public/assets/img/signin-bg.svg';
               echo get_theme_mod( 'default_user_cover_picture',$default);
               
               ?>&quot;);"></div>
               <div class="card-body pb-5">
                  <img src="<?php echo get_avatar_url($user->ID); ?>" class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait">
                  <h4 class="h3"><?php echo esc_attr( $user->display_name); ?></h4>
                  <h5 class="fw-normal"><?php echo esc_attr( get_user_meta($user->ID, 'billing_company',true) ); ?></h5>
                  <p class="text-gray mb-4"><?php 

                  echo esc_attr( get_user_meta($user->ID, 'billing_city',true) )."&nbsp;"; 
                  echo esc_attr( get_user_meta($user->ID, 'billing_state',true) ).",&nbsp;";  
                  echo esc_attr( get_user_meta($user->ID, 'billing_country',true) );  
                  
                  ?></p>
                  
               </div>
            </div>


   <?php 
   
      $has_account_manager = get_user_meta(get_current_user_id(), 'account_manager',true);
     
      if($has_account_manager){
         $account_manager = get_userdata( $has_account_manager );
        
      }

      if(($has_account_manager || get_theme_mod( 'default_account_manager_name'))&&get_theme_mod( 'enable_account_manager')) {

   ?>


            <div class="card border-0 shadow mt-5">
               <div class="card-body">
                  <h2 class="fs-5 fw-bold mb-1">Your Account Manager</h2>
                 
                  <div class="d-block">

                  <li class="list-group-item bg-transparent px-0">
                  <div class="row align-items-center">
                     <div class="col-auto"> <a href="#" class="avatar-md"><img class="rounded" alt="Image placeholder" src="<?php

                     if($has_account_manager){

                           echo get_avatar_url($has_account_manager); 

                     }
                     else {
                     
                        $default = BRIGHTY_CORE_PLUGIN_URL.'public/assets/img/signin-bg.svg';
                        echo get_theme_mod( 'default_account_manager_photo',$default);
                     }
               
                     ?>"></a></div>
                     <div class="col-auto px-0">
                           <h4 class="fs-6 text-dark mb-0"><a href="#"><?php

                  if($has_account_manager){
                        echo $account_manager->display_name;
                  }
                  else {
                        echo get_theme_mod( 'default_account_manager_name');
                  }

                           ?></a></h4>
                           <span class="small"><?php 
                        
                        if($has_account_manager){
                              echo get_user_meta($account_manager->ID,'account_manager_position',true);
                        }
                          else {
                           echo get_theme_mod( 'default_account_manager_position'); 
                          } ?></span>
                     </div>
                  </div>


                  <p><?php 
                  
                  if($has_account_manager){
                     echo get_user_meta($account_manager->ID,'description',true);
               }
               else {
                  echo get_theme_mod( 'default_account_manager_description'); 
               }
                  ?></p>


                  </li>
                     <div class="d-flex align-items-center me-5">
                        <div class="icon-shape icon-sm icon-shape-purple rounded me-3">
                           <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                           </svg>
                        </div>
                        <div class="d-block">
                           <label class="mb-0">Phone</label>
                           <h4 class="mb-0"><?php
                           if($has_account_manager){
                              echo get_user_meta($account_manager->ID,'billing_phone',true);
                        }
                           else {
                           echo get_theme_mod( 'default_account_manager_phone'); 
                           }
                           ?></h4>
                        </div>
                     </div>
                     <div class="d-flex align-items-center  pt-3">
                        <div class="icon-shape icon-sm icon-shape-danger rounded me-3">
                           <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"></path>
                           </svg>
                        </div>
                        <div class="d-block">
                        <label class="mb-0">Email</label>
                           <p class="mb-0" style="font-size: 11px;"><?php 
                            if($has_account_manager){
                              echo $account_manager->user_email;
                        }
                           else {
                           echo get_theme_mod( 'default_account_manager_email'); 
                           
                           }?></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

   <?php } ?>
         
         
         </div>
         </div>

         
        
        
      </div>
   </div>


   <div class="col-12 col-xl-4">
      <div class="row">
         
   </div>
</div>