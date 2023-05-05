<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 */


 /**
  *  add admin pro css
  */

add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {

    wp_enqueue_style( 'admin_pro_admin_area_css', AP_ADMIN_PRO_URL . 'css/admin-area.css', false, '1.0.0' );

}


 /**
  *  add admin pro js
  *
  */

function admin_pro_js($hook) {
    wp_enqueue_script('admin_pro_js', AP_ADMIN_PRO_URL  . '/js/admin-pro.js');
}

add_action('admin_enqueue_scripts', 'admin_pro_js');


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;