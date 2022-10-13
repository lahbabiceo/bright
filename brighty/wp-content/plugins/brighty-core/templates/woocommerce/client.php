<?php 

require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');

?>
<div class="row">



  
   <div class="col-12">

      <?php 



          $args = array(
            'posts_per_page'   => -1,
            'post_type' => 'client',
            'meta_query' => array(
              'relation' => 'OR',
                array(
                    'key'     => 'contacts',
                    'value'   => get_current_user_ID()
                ),
                array(
                    'key'     => 'access',
                    'value'   => get_current_user_ID()
                ),
            ),
        );
        
        $clients = get_posts( $args );

      

         ?>

        <div class="">


          
         
            
            <?php


              foreach($clients as $client){


                $billing = get_client_billing_address($client->ID);
                $shipping = get_client_shipping_address($client->ID);
       
        
            
            ?>


<section >
  <div class="container py-2">
    

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?php echo $client->post_title."<br />(CRN: ".$client->ID.")"; ?></h5>
            <p class="text-muted mb-1"><?php 


                  
$admin_list = get_post_meta($client->ID,'contacts');
$is_admin = 0;
foreach($admin_list as $admin){
  if($admin == get_current_user_ID())
  {
    echo "&nbsp;<span class='badge bg-success mr-2'>Admin</span>";
    $is_admin =1;
  }
}

if(!$is_admin){
  $permissions_list = get_post_meta($client->ID,'permissions',true);
  foreach($permissions_list as $permission){
    if($permission['user'] == get_current_user_ID()){
      foreach($permission['allowed_permissions'] as $feature){
        echo "&nbsp;<span class='badge bg-info mr-2'>$feature</span>";
      }
    }
  }
}

?></p>
            <p class="text-muted mb-4"><?php echo $billing['city']." ".$billing['state']." ".$billing['country']." ".$billing['postcode']; ?></p>
            <div class="d-flex justify-content-center mb-2">
              <button type="button" class="btn btn-primary"><i class="fa fa-edit"></i>  Edit</button>
              <button onclick="leave_company(<?php echo $client->ID;  ?>)" type="button" class="btn btn-danger ms-1">Leave</button>
            
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-billing" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Billing Address</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-shipping" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Shipping Address</button>
          </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-billing" role="tabpanel" aria-labelledby="pills-home-tab">
            
            
  <div class="row">
      <form>
        <input type="hidden" name="crn" value="<?php echo $client->ID; ?>">
        <input type="hidden" name="action" value="brighty_update_company_billing_details">

        <div class="row">
          <div class="col-md-12">
            <div class="form-check form-switch">
              <input class="form-check-input" name="" type="checkbox" id="flexSwitchCheckChecked" checked="checked"> 
              <label class="form-check-label" for="flexSwitchCheckChecked">Keep Company Address Same as Admin Account Address</label>
            </div>
          </div>
          <div class="form-group col-md-12 col-lg-12 my-3">
            <label for="inputEmail4">Company Name</label>
            <input type="text" name="billing_company" value="<?php echo $billing['company']; ?>" class="form-control" id="inputEmail4" placeholder="Company Name">
          </div>
          <div class="form-group col-md-6 col-lg-6">
            <label for="inputEmail4">Email</label>
            <input type="email" name="billing_email" value="<?php echo $billing['email']; ?>" class="form-control" id="inputEmail4" placeholder="Email">
          </div>
          <div class="form-group col-md-6 col-lg-6">
            <label for="inputPassword4">Phone</label>
            <input type="text" name="billing_phone" value="<?php echo $billing['phone']; ?>" class="form-control" id="inputPassword4" placeholder="Phone">
          </div>
        </div>
        <div class="form-group my-2">
          <label for="inputAddress">Address</label>
          <input type="text" name="billing_address_1" value="<?php echo $billing['address_1']; ?>" class="form-control" id="inputAddress" placeholder="1234 Main St">
        </div>
        
        <div class="row">
          <div class="form-group col-md-6">
            <label for="inputCity">City</label>
            <input type="text" name="billing_city" value="<?php echo $billing['city']; ?>" class="form-control" id="inputCity">
          </div>
          <div class="form-group col-md-4">
            <label for="billingState">State</label>
            <input type="text" name="billing_state" value="<?php echo $billing['state']; ?>" class="form-control" id="inputState">
          </div>
          <div class="form-group col-md-2">
            <label for="inputZip">PostCode</label>
            <input type="text" name="billing_postcode" value="<?php echo $billing['postcode']; ?>"  class="form-control" id="inputZip">
          </div>
        </div>
        <button type="submit" class="btn btn-primary my-3">Save Billing Details</button>
      </form>
  </div>
  
            
            
            <div class="card mb-4">




                    <div class="card-body">










                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Name</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $billing['company']; ?></p>
                        </div>
                      </div>

                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $billing['email']; ?></p>
                        </div>
                      </div>
                      <hr>

                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $billing['phone']; ?></p>
                        </div>
                      </div>
                      <hr>

                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $billing['address_1']." ".$billing['city']." ".$billing['state']." ".$billing['country']." ".$billing['postcode']; ?></p>
                        </div>
                      </div>

                    </div> <!-- cardbody -->
                  </div><!-- card -->
                
            </div><!--  tab pane -->

          <div class="tab-pane fade" id="pills-shipping" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="card mb-4">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Name</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $shipping['company']; ?></p>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $shipping['email']; ?></p>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $shipping['phone']; ?></p>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-9">
                          <p class="text-muted mb-0"><?php echo $shipping['address_1']." ".$shipping['city']." ".$shipping['state']." ".$shipping['country']." ".$shipping['postcode']; ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
       
<h6 class="ms-2">Admins & Contacts               
  <button onclick="set_values_in_permission_form('',<?php echo $client->ID; ?>,'')" data-bs-toggle="modal" data-bs-target="#permissionsModal"  type="button" class="btn btn-sm btn-secondary ms-1 pull-right"> <i class="fa fa-user-plus"></i> Add Permission or Admin</button>
</h6>


<div class="card">
<div class="card-body">
  <ul class="list-group list-group-flush list my--3">

  <?php 
  
    $admins = get_post_meta($client->ID,'contacts');

    foreach($admins as $admin){

      $admin_user = get_user_by("ID",$admin);

    
  ?>

    <li class="list-group-item px-0">
      <div class="row align-items-center">
        <div class="col-auto">
          <a href="#" class="avatar">
            <img class="rounded" alt="Image placeholder" src="<?php echo get_avatar_url($admin_user->ID); ?>">
          </a>
        </div>
        <div class="col-auto ms--2">
          <h4 class="h6 mb-0">
            <a href="#"><?php echo $admin_user->display_name; ?></a>
          </h4>
          <div class="d-flex align-items-center">
            <div class="bg-success dot rounded-circle me-1"></div>
            <small>Admin</small>
          </div>
        </div>
        <div class="col text-end">
          <?php if(get_current_user_ID()!=$admin_user->ID){ ?>
          <button onclick="remove_user_from_client(<?php echo $admin_user->ID.",".$client->ID; ?>)" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center">
            <svg class="icon icon-xxs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg> Remove </button>
            <?php  } ?>
        </div>
      </div>
    </li>

<?php } ?>


<?php 
  
  $access = get_post_meta($client->ID,'access');

  
  foreach($access as $accessible) {

    $permissions_list = get_post_meta($client->ID,'permissions',true);
    


    

    

    $access_user = get_user_by("ID",$accessible,true);

?>

    <li class="list-group-item px-0">
      <div class="row align-items-center">
        <div class="col-auto">
          <a href="#" class="avatar">
            <img class="rounded-circle" alt="Image placeholder" src="<?php echo get_avatar_url($access_user->ID); ?>">
          </a>
        </div>
        

        <div class="col-auto ms--2">
          <h4 class="h6 mb-0">
            <a href="#"><?php echo $access_user->display_name." <span class='badge bg-secondary text-primary'><i class='fa fa-envelope-o'></i> ".$access_user->user_email."</span>"; ?></a>
          </h4>

          <div class="d-flex align-items-center">

            

            <?php 
          
            

          foreach($permissions_list as $permission){
            if($permission['user'] == $accessible){
              foreach($permission['allowed_permissions'] as $feature){
                echo "&nbsp;<div class='bg-info dot rounded-circle me-1'></div>
                <small>$feature</small>";
              }
            }
          }
        
      
      
      ?>

          </div>

        </div>
        

        <div class="col text-end">
          <button onclick="remove_user_from_client(<?php echo $access_user->ID.",".$client->ID; ?>)" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
            <i class="fa fa-times"></i>  
          Remove </button>
        </div>
      </div>
    </li>
<?php } ?>
  </ul>
  </div>
  </div>


      </div>
    </div>
  </div>
</section>















              <tr>
                <td>
                <?php echo $client->ID; ?>
                </td>
                <td>
                  <a href="#" class="d-flex align-items-center">
                    <div class="d-block">
                      <div class="small text-gray"><?php $billing = get_post_meta($client->ID,'details',true); print_r($billing['email']); ?></div>
                    </div>
                  </a>
                </td>
                <td>
                  <span class="fw-normal"></span>
                </td>
              
                <td>
                
                  
                      
              
                </td>
              </tr>
              <?php } 
              
              if(!$clients){ 
              ?>

                <tr>
                  <td colspan="4">
                  <div class="alert alert-warning">
                   <i class="fa fa-check"></i> It seems like you have registered as an individual user. If you would like to register as a company, click here 
                  </div>
                  </td>
                </tr>

        <?php } ?>
            
            
            
              
              
        
        </div>


         <?php
         
         
        
         
         ?>

   </div>
       

</div>




<!-- add permission popuop -->



<!-- Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Adding Permission</h5>
        <button type="button" class="btn btn-outline-secondary close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <p class="description mt-0">Please select all permissions that you want to grant to this user</p>
                <form method="post" id="permissionForm">
                  <input type="hidden" name="action" value="brighty_add_access">
                 
                    <div class="row">
                        <div class="col-10">
                          <label class="form-check-label"  >Email</label>
                          <input name="email" value="invoices" class="form-control" type="text" id="emailField" >
                        </div>

                        <div class="col-2">
                          <label class="form-check-label"  >CRN</label>
                          <input name="crn" value="" class="form-control disabled" type="text" id="crnField" >
                        </div>
                    </div>


                    <div class="row">
                      
                        <div class="col-12 mt-4 mb-3">
                          <label class="form-check-label"  >Permissions</label>
                          <br />
                          <div class="form-check form-check-inline">
                            <input name="make_admin" value="yes" id="makeadmin" class="form-check-input" type="checkbox"  >
                            <label class="form-check-label" for="makeadmin"   >Make Admin </label>
                          </div>
                        </div>

                        <div class="col-12">

                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="notifications" class="form-check-input" type="checkbox"  checked >
                            <label class="form-check-label"   >Notifications</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="invoices" class="form-check-input" type="checkbox"  checked >
                            <label class="form-check-label"   >Invoices</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="invoices" class="form-check-input" type="checkbox"  checked >
                            <label class="form-check-label"   >Invoices</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="projects" class="form-check-input" type="checkbox" id="inlineCheckbox2" >
                            <label class="form-check-label" for="inlineCheckbox2">Projects</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="tasks" class="form-check-input" type="checkbox" id="inlineCheckbox3" >
                            <label class="form-check-label" for="inlineCheckbox3">Tasks</label>
                          </div>
                          <div name="permissions[]" value="orders" class="form-check form-check-inline">
                            <input name="permissions[]" value="Orders" class="form-check-input" type="checkbox" id="inlineCheckbox4" >
                            <label class="form-check-label" for="inlineCheckbox4">Orders</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="tickets" class="form-check-input" type="checkbox" id="inlineCheckbox5" >
                            <label class="form-check-label" for="inlineCheckbox5">Support Tickets</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="domains" class="form-check-input" type="checkbox" id="inlineCheckbox6" checked value="option3" >
                            <label class="form-check-label" for="inlineCheckbox6"  >Domains</label>
                          </div>

                          <div class="form-check form-check-inline">
                            <input name="permissions[]" value="hosting" class="form-check-input" type="checkbox" id="inlineCheckbox7" checked value="option3" >
                            <label class="form-check-label" for="inlineCheckbox7" >Hosting</label>
                          </div>

                          <div class="form-group">
                            <button type="submit" class="btn btn-primary" >Add Permission</button>
                          </div>

                          </div>


                      </div>


                  </form>
               </div>
      
      
    </div>
  </div>
</div>






<script>


function remove_user_from_client(userid,crn){

    Swal.fire({

    title: 'Are you sure you want to remove this user?',
    icon: 'info',

    showCancelButton: true,
    confirmButtonText: 'Yes, Remove the User',

    preConfirm: () => {
      return fetch('/wp-admin/admin-ajax.php?action=brighty_remove_user_from_client&crn='+crn+'&user='+userid)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.text()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        }).then((result) => {
          Swal.fire({
            title: result
          }).then(function(){
            location.reload()
          })

          })
    },
    allowOutsideClick: () => !Swal.isLoading()
    });



}



function set_values_in_permission_form(email,crn,userid){

   jQuery('#emailField').val(email);
   jQuery('#crnField').val(crn);
   jQuery('#useridField').val(userid);

}


window.ajaxURL = '/wp-admin/admin-ajax.php';

let permissionForm = document.getElementById('permissionForm')

permissionForm.onsubmit = async (e) => {

    e.preventDefault();

    console.log("submitting form");


    formData = new FormData(permissionForm);

    for(let [name, value] of formData) {
      console.log(`${name} = ${value}`); // key1 = value1, then key2 = value2
    }

    Swal.fire({
        title: 'Adding New User Access to Client..',
        text: "You can manage access in future by clicking here.",
        icon: 'info'
    });

    fetch(window.ajaxURL, {
      method: 'POST',
      body: formData
    })
    .then(function (response){
      return response.text()
    })
    .then(function(html){

      jQuery('#permissionsModal').click();  //hacky method to close bootstrap modal: TODO better solution 

        Swal.fire({
            title: 'Access Status',
            text: html,
            icon: 'info'
        }).then(function(){
          location.reload()
        });
      console.log(html);
      
    });

};






function send_access_request(){
  let crn = jQuery('#crn').val();
  if(crn){

            Swal.fire({

            title: 'Confirm Request to Client?',
            icon: 'info',

            showCancelButton: true,
            confirmButtonText: 'Yes!',

            
            preConfirm: () => {
              return fetch('/wp-admin/admin-ajax.php?action=brighty_request_access&crn='+crn)
                .then(response => {
                  if (!response.ok) {
                    throw new Error(response.statusText)
                  }
                  return response.text()
                })
                .catch(error => {
                  Swal.showValidationMessage(
                    `Request failed: ${error}`
                  )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then((result) => {
            
              Swal.fire({
                title: `Request Sent to Admin`
              }).then(function(){
                location.reload()
              })
            
          })




  }
  else{
    
    Swal.fire({
                title: `Invalid CRN`
              });
  }
}

function deny_access_request(id,crn,requested_on){

 
  Swal.fire({
        title: 'Deny Access Request?',
        text: "Are you sure you want to deny access request?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Deny the Request'
      }).then((result) => {
        if (result.isConfirmed) {
          console.log('/wp-admin/admin-ajax.php?action=brighty_deny_access_request&crn='+crn+'&id='+id+'&requested_on='+requested_on);

          fetch('/wp-admin/admin-ajax.php?action=brighty_deny_access_request&crn='+crn+'&id='+id+'&requested_on='+requested_on)
            .then(response => {
          
              if (!response.ok) {
                throw new Error(response.statusText)
              }

              else{
              
                  Swal.fire({
                    title: 'Sucessfully Denied Request',
                    text: "You have successfully Denied Access Request from this user",
                    icon: 'info',
                  }).then(function(){
                    location.reload();
                  });

              }
          });



        }
      })




}



function leave_company(crn){


  Swal.fire({
        title: 'Careful',
        text: "If you leave the company, you will lose access to all of its invoices, projects etc.; Also note that no data is deleted from our system and any pending invoices and other liabilities still apply.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'I Understand, Leave the Company'
      }).then((result) => {
        if (result.isConfirmed) {

          fetch('/wp-admin/admin-ajax.php?action=brighty_leave_company&crn='+crn)
            .then(response => {
          
              if (!response.ok) {
                throw new Error(response.statusText)
              }

              else{
              
                  Swal.fire({
                    title: 'Client Access Removed',
                    text: "You have successfully left this client",
                    icon: 'info',
                  }).then(function(){
                    location.reload();
                  });

              }
          });



        }
      })




}

function cancel_access_request(dt,crn){

        Swal.fire({
        title: 'Are you sure ?',
        text: "Are you sure you want to cancel request for access?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Cancel Request'
      }).then((result) => {
        if (result.isConfirmed) {

          fetch('/wp-admin/admin-ajax.php?action=brighty_cancel_access_request&crn='+crn+'&date='+dt)
            .then(response => {
          
              if (!response.ok) {
                throw new Error(response.statusText)
              }

              else{
              
                  Swal.fire({
                    title: 'Removed',
                    text: "Your Request was removed",
                    icon: 'info',
                  }).then(function(){
                    location.reload();
                  });

              }
          });



        }
      })
}





//   Swal.fire({
//   title: 'Error!',
//   text: 'Do you want to continue',
//   icon: 'error',
//   confirmButtonText: 'Cool'
// });
</script>