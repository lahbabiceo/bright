<?php

// set ajax endpoint for marking notifications as read when in the client dashboard

add_action('wp_head', 'brighty_ajaxurl');

function brighty_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';

}

// view invoice endpoint



// adding mark all notifications as read

add_action('wp_ajax_brighty_open_invoice', 'brighty_open_invoice');  

function brighty_open_invoice(){


    $invoice =  '
    
    <div class="card">
    <div class="card-body">
      <div class="container mb-5 mt-3">
        <div class="row d-flex align-items-baseline">
          <div class="col-xl-9">
            <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #123-123</strong></p>
          </div>
          <div class="col-xl-3 float-end">
            <a class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                class="fas fa-print text-primary"></i> Print</a>
            <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i
                class="far fa-file-pdf text-danger"></i> Export</a>
          </div>
          <hr>
        </div>
  
        <div class="container">
          <div class="col-md-12">
            <div class="text-center">
              <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
              <p class="pt-0">MDBootstrap.com</p>
            </div>
  
          </div>
  
  
          <div class="row">
            <div class="col-xl-8">
              <ul class="list-unstyled">
                <li class="text-muted">To: <span style="color:#5d9fc5 ;">John Lorem</span></li>
                <li class="text-muted">Street, City</li>
                <li class="text-muted">State, Country</li>
                <li class="text-muted"><i class="fas fa-phone"></i> 123-456-789</li>
              </ul>
            </div>
            <div class="col-xl-4">
              <p class="text-muted">Invoice</p>
              <ul class="list-unstyled">
                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                    class="fw-bold">ID:</span>#123-456</li>
                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                    class="fw-bold">Creation Date: </span>Jun 23,2021</li>
                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                    class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                    Unpaid</span></li>
              </ul>
            </div>
          </div>
  
          <div class="row my-2 mx-1 justify-content-center">
            <table class="table table-striped table-borderless">
              <thead style="background-color:#84B0CA ;" class="text-white">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Description</th>
                  <th scope="col">Qty</th>
                  <th scope="col">Unit Price</th>
                  <th scope="col">Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Pro Package</td>
                  <td>4</td>
                  <td>$200</td>
                  <td>$800</td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Web hosting</td>
                  <td>1</td>
                  <td>$10</td>
                  <td>$10</td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Consulting</td>
                  <td>1 year</td>
                  <td>$300</td>
                  <td>$300</td>
                </tr>
              </tbody>
  
            </table>
          </div>
          <div class="row">
            <div class="col-xl-8">
              <p class="ms-3">Add additional notes and payment information</p>
  
            </div>
            <div class="col-xl-3">
              <ul class="list-unstyled">
                <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>$1110</li>
                <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Tax(15%)</span>$111</li>
              </ul>
              <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                  style="font-size: 25px;">$1221</span></p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xl-10">
              <p>Thank you for your purchase</p>
            </div>
            <div class="col-xl-2">
              <button type="button" class="btn btn-primary text-capitalize"
                style="background-color:#60bdf3 ;">Pay Now</button>
            </div>
          </div>
  
        </div>
      </div>
    </div>
  </div>
    
    
    ';


    echo $invoice;

    wp_die();// Required to terminate immediately and return a proper response

}







// adding mark all notifications as read

add_action('wp_ajax_brighty_notifications_mark_read', 'brighty_notifications_mark_read');  

function brighty_notifications_mark_read(){

    echo "Setting ". $_POST['brighty_notifications_read_upto']." for ".get_current_user_id()." - ".get_user_meta( get_current_user_id(), "brighty_notifications_read", true );
    update_user_meta( get_current_user_id(), 'brighty_notifications_read', $_POST['brighty_notifications_read_upto']);
    update_user_meta( get_current_user_id(), 'brighty_email_read', $_POST['brighty_email_read_upto']);
    wp_die();// Required to terminate immediately and return a proper response

}

//logout everywhere else


add_action( 'wp_ajax_brighty_logout_everywhere_else', 'brighty_logout_everywhere_else' );

function brighty_logout_everywhere_else(){
  $sessions = WP_Session_Tokens::get_instance( get_current_user_id() );
  $sessions->destroy_others(  wp_get_session_token() );
  wp_redirect(wc_get_endpoint_url( 'security', '', get_permalink( get_option('woocommerce_myaccount_page_id') ) ).'/?cleared=1');
  exit();
}



// upload required documents


add_action( 'wp_ajax_brighty_upload_document', 'brighty_upload_document' );

function brighty_upload_document(){

 // echo "yoyo";
	// check security nonce which one we created in html form and sending with data.
	check_ajax_referer('brighty_upload_document', 'security');

  $uploadedfile = $_FILES['file'];

  $upload_overrides = array(
    'test_form' => false
  );


  $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

  if ( $movefile && ! isset( $movefile['error'] ) ) {

      update_user_meta(get_current_user_id(),$_POST['document-name'],$movefile['url']);
      wp_redirect(wc_get_endpoint_url( 'documents', '', get_permalink( get_option('woocommerce_myaccount_page_id') ) ));
      exit();

  } else {
      /*
      * Error generated by _wp_handle_upload()
      * @see _wp_handle_upload() in wp-admin/includes/file.php
      */
      echo $movefile['error'];
  }

  wp_die();

}


// toggle notification types to recieve : my-account->notifications->toggle

add_action('wp_ajax_brighty_toggle_notifications_type', 'brighty_toggle_notifications_type');  

function brighty_toggle_notifications_type(){


  $current_notification_status = get_user_meta( get_current_user_id(), $_GET['notification'],true);
  
  if($current_notification_status =="ON"){
    update_user_meta( get_current_user_id(), $_GET['notification'], "OFF");
  }
  else{
    update_user_meta( get_current_user_id(), $_GET['notification'], "ON");  
  }

  wp_die();// Required to terminate immediately and return a proper response

}


// get email notification preview

add_action('wp_ajax_brighty_get_notification', 'brighty_get_notification');  

function brighty_get_notification(){



      global $wpdb;

      $current_user =  get_user_by('ID',get_current_user_id());;


      $table_name = $wpdb->prefix . 'wml_entries';
      $query_cols  = [ 'id', 'to_email', 'subject', 'message', 'headers', 'attachments', "DATE_FORMAT(sent_date, '%Y/%m/%d %H:%i:%S') as sent_date" ];
      $entry_query = 'SELECT distinct ' . implode( ',', $query_cols ) . ' FROM ' . $table_name;
      $where[]     = "to_email = '$current_user->user_email' and id = ".$_GET['id'];
      $orderby = ' order by id desc';
      $limit = ' limit 0, 5';
      $entry_query .= ' WHERE ' . implode( ' and ', $where ) . $orderby . $limit;

      $mail_list ='';
      
      $sql = $wpdb->get_results( $entry_query );


     if($sql){ 
      foreach ( $sql as $key => $row ) {

        $mail_list .= '</span>'.$row->message.'<span id="subject">'.$row->subject.'</span>';
      }
  }

    echo $mail_list;
    
    wp_die();// Required to terminate immediately and return a proper response

}
