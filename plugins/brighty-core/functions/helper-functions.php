<?php //helper function: get client billing details: returns array

function get_client_billing_address($clientID){

  $billing_same_as_customer_details = get_post_meta($clientID,'billing_same_as_customer_details',true);
  $same_shipping_address = get_post_meta($clientID,'same_shipping_address',true);
  
  $client_admin = get_post_meta($clientID,'contacts',true);

  if($billing_same_as_customer_details && $client_admin){
    $billing = array();
    $billing['address_1'] = get_user_meta($client_admin, 'billing_address_1',true); 
    $billing['city'] =  get_user_meta($client_admin, 'billing_city',true); 
    $billing['state'] = get_user_meta($client_admin, 'billing_state',true); 
    $billing['country'] = get_user_meta($client_admin, 'billing_country',true); 
    $billing['postcode'] = get_user_meta($client_admin, 'billing_postcode',true); 
    $billing['phone'] = get_user_meta($client_admin, 'billing_phone',true); 
    $billing['company'] = get_user_meta($client_admin, 'billing_company',true); 

    // get email 

    $admin_user = get_user_by("ID", $client_admin);
    $billing['email'] = $admin_user->user_email;

    // get phone

    // get name

    return $billing;
  }

  else {
    return get_post_meta($clientID,'billing_details',true);
  }

}


function get_client_shipping_address($clientID){

    
    $same_shipping_address = get_post_meta($clientID,'same_shipping_address',true);
    
    if($same_shipping_address){
       return get_client_billing_address($clientID); 
       
    }
    else{
        return get_post_meta($clientID,'shipping_details',true);
    }
  
}