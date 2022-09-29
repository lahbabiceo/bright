<?php 

require_once(BRIGHTY_CORE_PLUGIN_DIR .'/templates/woocommerce/my-account-top-menu.php');
global $current_user;
?>

<div class="row">
<div class="col-8">
    

<?php 

//list email notification

global $wpdb;

$table_name = $wpdb->prefix . 'wml_entries';
$query_cols  = [ 'id', 'to_email', 'subject', 'message', 'headers', 'attachments', "DATE_FORMAT(sent_date, '%Y/%m/%d %H:%i:%S') as sent_date" ];
$entry_query = 'SELECT distinct ' . implode( ',', $query_cols ) . ' FROM ' . $table_name;
$where[]     = "to_email = '$current_user->user_email'";
$orderby = ' order by id desc';
$limit = ' limit 0, 5';
$entry_query .= ' WHERE ' . implode( ' and ', $where ) . $orderby . $limit;

$mail_list ='';
$unread = '';
$latest_email_id = 0; 
$unread = get_user_meta(get_current_user_id(), "brighty_email_read",true )?get_user_meta(get_current_user_id(), "brighty_email_read",true ):0;






$sql = $wpdb->get_results( $entry_query );



foreach ( $sql as $key => $row ) {

    

    if($unread < $row->id ){
        $unread = 'unread';
    }
    
    $highlight = '';

    if($latest_email_id < $row->id ){
        $latest_email_id = $row->id ; 
    }
    
    if($row->id > get_user_meta(get_current_user_id(), "brighty_email_read",true )){
    
        $highlight = 'bg-secondary';
    
    }
    

    $mail_list .= '

  
  <span onclick="open_notification(\''.$row->id.'\')" data-bs-toggle="modal" data-bs-target="#modal-default" class="list-group-item list-group-item-action border-bottom '.$highlight.'">
    <div class="row align-items-center">
        <div class="col-auto">
          <!-- Avatar -->
          
          <svg class="avatar-md rounded" class="icon icon-md" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>

        </div>
        <div class="col ps-0 ms-2">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="h6 mb-0 text-small">'.$row->subject.'</h4>
              </div>
              <div class="text-end">
                <small class="text-danger">';

    $formatted_date = $row->sent_date;
                
    $mail_list .= $formatted_date.'</small>
              </div>
          </div>
          <p class="font-small mt-1 mb-0"></p>
        </div>
    </div>
  </span>';


}


//$sql = $wpdb->get_results( $entry_query );
      


///list notification post type

$args = array(
    'nopaging' => true,
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_type'         =>'notifications'
);

$notifications = new WP_Query( $args );

$latest_notification_id = 0;

$notifications_list = '';





    // The Loop
if ( $notifications->have_posts() ) {




while ( $notifications->have_posts() ) {

    
$notifications->the_post();

if(get_the_ID() > $latest_notification_id){
    $latest_notification_id = get_the_ID();
}

$notification_thumbnail = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), "full");




$unread = get_user_meta(get_current_user_id(), "brighty_notifications_read",true )?get_user_meta(get_current_user_id(), "brighty_notifications_read",true ):0;


if($unread < $latest_notification_id ){
    $unread = 'unread';
}

$highlight = '';

if(get_the_ID() > get_user_meta(get_current_user_id(), "brighty_notifications_read",true )){

    $highlight = 'bg-secondary';

}
$notifications_list .= '

  
  <a target="_blank" href="'.get_the_permalink().'" class="list-group-item list-group-item-action border-bottom '.$highlight.'">
    <div class="row align-items-center">
        <div class="col-auto">
          <!-- Avatar -->'; 

          if($notification_thumbnail){
            $notifications_list .= '<img alt="" src="'.$notification_thumbnail.'" class="avatar-md rounded">';
          }
          else{
            $notifications_list .= '<svg style="    height: 40px;" class="icon icon-xxs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>';
          }
          
          $notifications_list .= '
        </div>
        <div class="col ps-0 ms-2">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="h6 mb-0 text-small">'.get_the_title().'</h4>
              </div>
              <div class="text-end">
                <small class="text-danger">'.get_the_date().'</small>
              </div>
          </div>
          <p class="font-small mt-1 mb-0">'.get_the_excerpt().'</p>
        </div>
    </div>
  </a>';


  
}
}


echo $mail_list;

echo $notifications_list;
?>


</div>

<div class="col-md-4">


<div class="card card-body border-0 shadow mb-4 mb-xl-0">


        <?php

                $default = [
                    [
                        'notification-name'   => esc_html__( 'Company News', 'brighty-core' ),
                        'notification-description'    => 'Get Rocket news, announcements, and product updates',
                        'notification-status'   => 'ON',
                        'notification-id' =>'companynews'
                    ],
                    [
                        'notification-name'   => esc_html__( 'Account Activity', 'brighty-core' ),
                        'notification-description'    => 'Get important notifications about you or activity you\'ve missed',
                        'notification-status'   => 'ON',
                        'notification-id' =>'accountactivity'
                    ],
                    [
                        'notification-name'   => esc_html__( 'Meetups Near You', 'brighty-core' ),
                        'notification-description'    => 'Get an email when a Dribbble Meetup is posted close to my location',
                        'notification-status'   => 'ON',
                        'notification-id' =>'meetup'
                    ],
                ];
                $notification_types = get_theme_mod( 'notification-types',$default);
           

        ?>


         <h2 class="h5 mb-4">Alerts &amp; Notifications</h2>
         <ul class="list-group list-group-flush">

            <?php foreach($notification_types as $notification_type) { ?>
                <li class="list-group-item d-flex align-items-center justify-content-between px-0 border-bottom">
                <div>
                    <h3 class="h6 mb-1"><?php echo $notification_type['notification-name']; ?></h3>
                    <p class="small pe-4"><?php echo $notification_type['notification-description']; ?></p>
                </div>
                <div>
                    <div class="form-check form-switch" onclick="toggleNotification('<?php echo $notification_type['notification-id']; ?>')"><input <?php 
                    $user_preference = get_user_meta( get_current_user_id(), $notification_type['notification-id'],true);
                    
                    if($user_preference=="ON"){ 
                        echo "checked"; 
                    }
                    if($notification_type['notification-status']=="ON" && !$user_preference){ 
                        echo "checked"; 
                    }

                    ?> class="form-check-input" type="checkbox" id="user-notification-1"> <label class="form-check-label" for="user-notification-1"></label></div>
                </div>
                </li>
            <?php } ?>
            
         </ul>
      </div>

      </div>
</div>

<!-- Modal Content -->
<div style="z-index:999999" class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div style="max-width:700px"  class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title" id="notification-title"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>  
            <div class="modal-body p-2 text-center" id="notification-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!---- Open notifications  -->
<script>
    function open_notification(id){
        jQuery('#notification-title').html('Please wait...')
        jQuery('#notification-body').html('<div class="spinner-border" role="status"></div>');

        jQuery('#notification-body').load('/wp-admin/admin-ajax.php?action=brighty_get_notification&id='+id)
        jQuery('#notification-title').load('/wp-admin/admin-ajax.php?action=brighty_get_notification&id='+id+' #subject')

    }
    function toggleNotification(name){

        jQuery.get('/wp-admin/admin-ajax.php?action=brighty_toggle_notifications_type&notification='+name);
    
    }
</script>
