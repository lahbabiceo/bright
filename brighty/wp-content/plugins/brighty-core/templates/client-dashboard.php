<?php
/**
*  
* Template Name: Brighty - User Dashboard
* @package Brighty
* page template for showing items on the client dashboard
*
*/

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/header.php');

$logo_url =  get_theme_mod( 'dashboard_logo' ); 


get_avatar_url( get_current_user_id() )?$avatar = get_avatar_url( get_current_user_id() ): $avatar = 'http://0.gravatar.com/avatar/0b95e79fcfdb8336335e3f62d51780e4?s=128&d=mm&r=g';

global $current_user;

global $wp;

wp_get_current_user();


$cart_page_id = wc_get_page_id( 'cart' );
$cart_page_url = $cart_page_id ? get_permalink( $cart_page_id ) : '';

$cart_count = count( WC()->cart->get_cart() );

?>


<!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->


<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
<a class="navbar-brand me-lg-5" href="../../index.html">
<img class="navbar-brand-dark" src="<?php echo $logo_url; ?>" alt="" /> <img class="navbar-brand-light" src="<?php echo $logo_url; ?>" alt="Volt logo" />
</a>
<div class="d-flex align-items-center">
<button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
</div>
</nav>

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
<div class="sidebar-inner px-4 pt-3">
<div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
<div class="d-flex align-items-center">
<div class="avatar-lg me-4">
  <img src="<?php echo $avatar; ?>" class="card-img-top rounded-circle border-white"
    alt="Bonnie Green">
</div>
<div class="d-block">


  <h2 class="h5 mb-3"><?php echo _('Hi','brighty-core');
  
  if(is_user_logged_in()){
    echo ' '.$current_user->display_name;
  }
  else {
    echo _(' There!','brighty-core');
  }

  
  ?></h2>
  
  <?php

if(!is_user_logged_in()){
    ?>
  <a href="../../pages/examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
    <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>            
    Sign In
  </a>


  <a href="../../pages/examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
  <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
  Create Account
  </a>
<?php } else {
    ?>


<a href="../../pages/examples/sign-in.html" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
    <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>            
    Sign Out
</a>

    <?php
}
?>
</div>
</div>
<div class="collapse-close d-md-none">
<a href="#sidebarMenu" data-bs-toggle="collapse"
    data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
    aria-label="Toggle navigation">
    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
  </a>
</div>
</div>

<?php


        $menuLocation = 'user-dashboard-main-menu-logged-in';
        if(!is_user_logged_in()){
            $menuLocation = 'user-dashboard-main-menu-not-logged-in';
        }

        wp_nav_menu( array(
            'theme_location'    => $menuLocation,
            'depth'             => 2,
            'container'         => '',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'bs-example-navbar-collapse-1',
            'menu_class'        => 'nav flex-column pt-3 pt-md-0'
        ) );
?>







<ul class="nav flex-column pt-3 pt-md-0">

<?php
do_action( 'woocommerce_before_account_navigation' );

?>



		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<!-- <li class=" nav-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li> -->


            <li class=" nav-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>" >
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) );  if(home_url( $wp->request ) == esc_url( wc_get_account_endpoint_url( $endpoint ) ) ){
                    echo " active";
                } ?>"  class="nav-link d-flex align-items-center">
                    <span class="sidebar-icon">
                    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path></svg>
                    </span>
                    <span class="sidebar-text"><?php echo esc_html( $label ); ?></span>
                </a>
            </li>
		<?php endforeach; ?>



<?php do_action( 'woocommerce_after_account_navigation' ); ?>

<li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

<li class="nav-item">
<a href="../../pages/upgrade-to-pro.html"
  class="btn btn-secondary d-flex align-items-center justify-content-center btn-upgrade-pro">
  <span class="sidebar-icon d-inline-flex align-items-center justify-content-center">
    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
  </span> 
  <span>Credits: Rs. 500</span>
</a>
</li>
</ul>
</div>
</nav>

<main class="content">

    <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
<div class="container-fluid px-0">
<div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
    <div class="d-flex align-items-center">
    <!-- Search form -->

    
        <button onclick="jQuery('.sidebar').toggleClass('contracted')" id="sidebar-toggle" class="sidebar-toggle me-3 btn btn-icon-only d-none d-lg-inline-block align-items-center justify-content-center">
            <svg class="toggle-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
        </button>
        <?php echo do_shortcode('[fibosearch]'); ?>
        <!-- <form action="" method="get" class="navbar-search form-inline" id="navbar-search-main">
        <div class="input-group input-group-merge search-bar">
            <span class="input-group-text" id="topbar-addon">
                <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </span>
            <input type="text" name="s" class="form-control" id="topbarInputIconLeft" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
        </div>
        </form> -->
        <!-- / Search form -->


    <div class="px-3 ml-3 pl-3">
        <div class="dropdown">
            <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-th"></i>  &nbsp;  
              <?php  echo _('Services', 'brighty-core'); ?>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                    Add User
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>                            
                    Add Widget
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path><path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path></svg>                            
                    Upload Files
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Preview Security
                </a>
                <div role="separator" class="dropdown-divider my-1"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                    Upgrade to Pro
                </a>
            </div>
        </div>
    </div>


    </div>


<!-- Navbar links -->
<ul class="navbar-nav align-items-center">



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

  
  <a href="'.get_the_permalink().'" class="list-group-item list-group-item-action border-bottom '.$highlight.'">
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
                <small class="text-danger">'.$row->sent_date.'</small>
              </div>
          </div>
          <p class="font-small mt-1 mb-0"></p>
        </div>
    </div>
  </a>';


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

  
  <a href="'.get_the_permalink().'" class="list-group-item list-group-item-action border-bottom '.$highlight.'">
    <div class="row align-items-center">
        <div class="col-auto">
          <!-- Avatar -->';

          if($notification_thumbnail){
            $notifications_list .= '<img alt="" src="'.$notification_thumbnail.'" class="avatar-md rounded">';
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


?>




<li class="nav-item dropdown">
  <a current="<?php echo get_user_meta( get_current_user_id(), "brighty_email_read", true ); ?>" last_email_id="<?php echo $latest_email_id; ?>" last_notification_id="<?php echo $latest_notification_id; ?>" class="nav-link text-dark notification-bell mark-notifications-read <?php echo $unread.' '.$latest_notification_id  ; echo get_user_meta( get_current_user_id(), "brighty_notifications_read", true ); ?> dropdown-toggle" data-unread-notifications="true" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" >
    <svg class="icon icon-sm text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-center mt-2 py-0">
    <div class="list-group list-group-flush">
      <a href="#" class="text-center text-primary fw-bold border-bottom border-light py-3">Notifications</a>
      


      <?php 
      
      

echo $mail_list;

echo $notifications_list;
      ?>
      
      
      
      <a href="#" class="dropdown-item text-center fw-bold rounded-bottom py-3">
        <svg class="icon icon-xxs text-gray-400 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
        View all
      </a>
    </div>
  </div>
</li>


<li class="nav-item dropdown px-3">
                    <a  href="<?php echo $cart_page_url  ?>">
                            <svg class="icon icon-sm me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>                    
                            <?php if($cart_count>0){ ?>
                            <span class="badge bg-success align-top" style="margin-left:-10px; margin-top:-4px"><?php echo $cart_count; ?></span>
                            <?php } ?>
                        </a>
    </li>

<?php
if(is_user_logged_in() ){

?>
<li class="nav-item dropdown ms-lg-3">
  <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="media d-flex align-items-center">
      <img class="avatar rounded-circle" alt="Image placeholder" src="<?php echo $avatar; ?>">
      <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
        <span class="mb-0 font-small fw-bold text-gray-900"><?php echo  $current_user->display_name; ?></span>
      </div>
    </div>
  </a>
  <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
    <a class="dropdown-item d-flex align-items-center" href="#">
      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path></svg>
      My Profile
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
      Settings
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      Messages
    </a>
    <a class="dropdown-item d-flex align-items-center" href="#">
      <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path></svg>
      Support
    </a>
    <div role="separator" class="dropdown-divider my-1"></div>
    <a class="dropdown-item d-flex align-items-center" href="<?php echo wp_logout_url(); ?>">
      <svg class="dropdown-icon text-danger me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>                
      Logout
    </a>
  </div>
</li>

<?php } ?>


<?php if(!is_user_logged_in() ){  ?>


<li class="nav-item dropdown">
            <a class="btn btn-sm btn-outline btn-outline-primary ">Sign In</a>
            <a class="btn btn-sm btn-secondary"><i class="fa fa-user-circle-o"></i> Register</a>
            <a class="">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
            </a>
</li>
<?php } ?>
</ul>
</div>
</div>
</nav>

   

    <div class="row">

        <div class="col-12 mb-4  py-4">
        <?php if ( have_posts() ) : ?>

                <?php while ( have_posts() ) :
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


                    <h1 class="page-title">
                    <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                        <?php the_title(); ?>
                    </a>
                    </h1>

                   

                    <?php
                        the_content();
                    ?>

                </article>
                <?php endwhile; ?>

                <?php the_posts_pagination(); ?>

                <?php endif; ?>

        </div>

    </div>


   
    
   


<footer class="bg-white rounded shadow p-5 mb-4 mt-4">
<div class="row">
<div class="col-12 col-md-4 col-xl-6 mb-4 mb-md-0">
    <p class="mb-0 text-center text-lg-start">© 2019-<span class="current-year"></span> <a class="text-primary fw-normal" href="https://themesberg.com" target="_blank">Themesberg</a></p>
</div>
<div class="col-12 col-md-8 col-xl-6 text-center text-lg-start">
    <!-- List -->
    <ul class="list-inline list-group-flush list-group-borderless text-md-end mb-0">
        <li class="list-inline-item px-0 px-sm-2">
            <a href="https://themesberg.com/about">About</a>
        </li>
        <li class="list-inline-item px-0 px-sm-2">
            <a href="https://themesberg.com/themes">Themes</a>
        </li>
        <li class="list-inline-item px-0 px-sm-2">
            <a href="https://themesberg.com/blog">Blog</a>
        </li>
        <li class="list-inline-item px-0 px-sm-2">
            <a href="https://themesberg.com/contact">Contact</a>
        </li>
    </ul>
</div>
</div>
</footer>
</main>

<!-- Core -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/nouislider/distribute/nouislider.min.js"></script>

<!-- Smooth scroll -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Charts -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/chartist/dist/chartist.min.js"></script>
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

<!-- Datepicker -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Sweet Alerts 2 -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Notyf -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/notyf/notyf.min.js"></script>

<!-- Simplebar -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- BRIGHTY JS -->
<script src="<? echo BRIGHTY_CORE_PLUGIN_URL; ?>/js/brighty-core.js"></script>



<?php

require_once(BRIGHTY_CORE_PLUGIN_DIR.'/template-parts/footer.php');

?>