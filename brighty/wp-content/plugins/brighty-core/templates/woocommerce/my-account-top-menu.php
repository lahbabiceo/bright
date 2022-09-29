<?php 

if(is_wc_endpoint_url( 'edit-address' ) || is_wc_endpoint_url( 'edit-account' ) || is_wc_endpoint_url( 'notifications-list')  || is_wc_endpoint_url( 'documents') || is_wc_endpoint_url( 'security') || is_wc_endpoint_url( 'profile'))  { ?>

   <div class="col-12 mb-5 account-settings-page-menu">
      
      <a class="btn btn-transparent <?php if(is_wc_endpoint_url( 'profile' ) || is_wc_endpoint_url( 'edit-account' ) ) echo "active"; ?>" style="background:none; box-shadow:none;"  href="/my-account/profile/">Profile</a>
      <a class="btn btn-transparent <?php if(is_wc_endpoint_url( 'notifications-list' )) echo "active"; ?>" href="/my-account/notifications-list/">Notifications</a>
      <a class="btn btn-transparent <?php if(is_wc_endpoint_url( 'documents' )) echo "active"; ?>"  href="/my-account/documents/" >Documents</a>
      <a class="btn btn-transparent <?php if(is_wc_endpoint_url( 'edit-address' )) echo "active"; ?>" href="/my-account/edit-address/">Address</a>
      <a class="btn btn-transparent <?php if(is_wc_endpoint_url( 'security' )) echo "active"; ?>" href="/my-account/security/">Security </a>

      <div class="dropdown-divider mt-0"></div>
   </div>

<?php } ?>