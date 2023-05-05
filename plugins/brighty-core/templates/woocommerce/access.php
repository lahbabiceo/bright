<?php 

require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');

?>
<div class="row">




   <div class="col-6 " id="tfa-settings-panel">

      <div class="accordion" id="accordionExample">
         <div class="accordion-item card mb-3 shadow bg-white border-warning  ">
            <div class="accordion-header p-4" id="requestForm1">
                  <h2 class="h5 mb-4">Request Access </h2>
                  
                  

                  <p class="description">
                     You can request access from client you need access to. The Admin can approve or disapprove your request accordingly. 
                  </p>

                  <form >
                    <div class="input-group" >
                        <input id="crn" required type="number" class="form-control" id="exampleInputIconRight" placeholder="Enter CRN" aria-label="Search" autocomplete="off"> 
                        <span onclick="send_access_request()" type="submit" class="input-group-text btn btn-primary" id="basic-addon2">
                          Send Request
                        </span>
                    </div>
                  </form>
                  
                
            </div>
            <div id="requestForm" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
               <div class="accordion-body">
                
               </div>
            </div>
         </div>
      </div>

   </div>




   <div class="col-6 " id="tfa-settings-panel">

      <div class="accordion" id="accordionPendingRequest">
         <div class="accordion-item card mb-3 shadow bg-white border-warning  ">
            <div class="accordion-header p-4" id="headingOne">
                  <h2 class="h5 mb-4">Pending Requests </h2>
                 

                 
                <?php 
                
                  //get clients which have admin access for this user

                $no_requests = 1;

                $args = array(
                    'posts_per_page'   => -1,
                    'post_type' => 'client',
                    'meta_query' => array(
                        array(
                            'key'     => 'contacts',
                            'value'   => get_current_user_ID()
                        ),
                    ),
                );
                
                $clients = get_posts( $args );
                // print_r($clients);
                

                foreach($clients as $client){

                  //fetch if any pending requests exists
                  $requests = get_post_meta($client->ID,'requests');

                 

                  foreach($requests as $request) {

                    $no_requests = 0;


                    $user_data = get_userdata($request['user']);
                ?>

                 <div class="row bg-secondary rounded p-3 my-3">
                    <div class=" col-8">
                        <h6 class="mb-1 "><?php echo $user_data->display_name; ?> </h6> 
                        <p class=" description pb-0">
                          Requested access to <a href=""><?php echo $client->post_title."(".$client->ID.")"; ?></a><br/>
                          Email: <?php echo $user_data->user_email; ?>  UserID: <?php echo $request['user']; ?>
                        </p>
                    </div>

                    <div class="col-4">
<!-- data-bs-toggle="collapse" data-bs-target="#collapsePendingRequest" aria-expanded="true" aria-controls="collapsePendingRequest" -->
                      <button data-bs-toggle="modal" data-bs-target="#permissionsModal" class="btn btn-success" type="button"  onclick="set_values_in_permission_form(<?php echo "'$user_data->user_email',$client->ID,$request[user]";  ?>)">
                          Allow
                      </button>

                      <button onclick="deny_access_request(<?php echo "$request[user],$request[crn],'$request[requested_on]'";  ?>)" class="btn btn-primary" type="button">
                        Deny
                      </button>

                    </div>
                  </div>


                  <?php 
                  } 
                  
                  
                }
                  ?>


               

                 
                <?php
                  
                  
                  //get_post_meta($_GET['crn'], 'requests', $permission_request);
                  //   //add to pending request for user
                  $permission_requests = get_user_meta(get_current_user_id(),'sent_requests');
                                  
              

                  foreach ($permission_requests as $key => $permission_request ){ 

                  


                    $no_requests = 0;
              ?>

                 <div class="row bg-info rounded p-3 my-3">
                    <div class=" col-8 text-white">
                        <h6 class="mb-1 ">Requested Access to CRN <?php echo $permission_request['crn']; ?> </h6> 
                        <p class=" description pb-0">
                          Your Request is pending approval
                        </p>
                    </div>

                    <div class="col-4">


                      <button onclick="cancel_access_request(<?php 

                      echo "'".$permission_request['requested_on']."', ".$permission_request['crn']; 
                      
                      ?>)"  class="btn btn-outline-white" type="button" >
                        Cancel Request
                      </button>

                    </div>
                  </div>

                
                  <?php } 
                  if($no_requests) {
                  ?>


                  <div class="alert alert-primary">
                    <i class="fa fa-check"></i> You don't have any sent or recieved requests in pending state. When you recieve a request, it will appear here.
                  </div>

                  <?php } ?>
                 
                 
            </div>
            <div id="collapsePendingRequest" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
               <div class="accordion-body">

                  
                      
               </div>
            </div>
         </div>
      </div>

   </div>



  
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

        <div class="card card-body shadow border-0 table-wrapper table-responsive">


          <h2 class="h5 mb-4">Clients You have Access To</h2>
          
          <table class="table user-table table-hover align-items-center">
            <thead>
            <tr>
              <th class="border-0 rounded-start">CRN</th>
              <th class="border-0">Name</th>
              <th class="border-0">Permissions</th>
              <th class="border-0">Actions</th>
            </tr>
            </thead>


            <tbody>
            
            <?php


              foreach($clients as $client){


       
        
            
            ?>

              <tr>
                <td>
                <?php echo $client->ID; ?>
                </td>
                <td>
                  <a href="#" class="d-flex align-items-center">
                    <div class="d-block">
                      <span class="fw-bold"> <?php echo $client->post_title; ?></span>
                      <div class="small text-gray"><?php 
                      $billing = get_client_billing_address($client->ID); 

                      echo $billing['email']; ?></div>
                    </div>
                  </a>
                </td>
                <td>
                  <span class="fw-normal"><?php 


                  
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

                  ?></span>
                </td>
              
                <td>
                
                  
                      <a class="btn btn-sm btn-primary" href="#">
                        <svg class="dropdown-icon text-gray-400 me-2" style="width:20px" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"></path>
                        </svg> View </a>

                     

                        <a class="btn btn-sm btn-secondary" href="#">
                        <svg class="dropdown-icon text-gray-400 me-2" style="width:20px"  fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg> Edit </a>
                        
                      <span onclick="leave_company(<?php echo $client->ID;  ?>)" class="btn btn-sm btn-danger" href="#">
                        <svg class="dropdown-icon text-danger me-2" style="width:20px" fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path d="M11 6a3 3 0 11-6 0 3 3 0 016 0zM14 17a6 6 0 00-12 0h12zM13 8a1 1 0 100 2h4a1 1 0 100-2h-4z"></path>
                        </svg> Leave  </span>
                  
                
              
                </td>
              </tr>
              <?php } 
              
              if(!$clients){ 
              ?>

                <tr>
                  <td colspan="4">
                  <div class="alert alert-warning">
                   <i class="fa fa-check"></i> You don't have access to any other company as of now. Once you recieve access to any other company, you can see them here. 
                  </div>
                  </td>
                </tr>

        <?php } ?>
            
            
            
              
              
              
            </tbody>
          </table>
        
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <p class="description mt-0">Please select all permissions that you want to grant to this user</p>
                <form method="post" id="permissionForm">
                  <input type="hidden" name="action" value="brighty_add_access">
                 
                    <div class="row">
                        <div class="col-4">
                          <label class="form-check-label"  >Email</label>
                          <input name="email" value="invoices" class="form-control" type="text" id="emailField" >
                        </div>

                        <div class="col-4">
                          <label class="form-check-label"  >User ID</label>
                          <input name="userid" value="invoices" class="form-control" type="text" id="useridField" >
                        </div>

                        <div class="col-4">
                          <label class="form-check-label"  >CRN</label>
                          <input name="crn" value="invoices" class="form-control" type="text" id="crnField" >
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
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>






<script>

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

    // for(let [name, value] of formData) {
    //   console.log(`${name} = ${value}`); // key1 = value1, then key2 = value2
    // }

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