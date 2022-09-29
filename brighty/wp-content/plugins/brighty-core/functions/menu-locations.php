<?php


// register user-dashboard menu locations

add_action( 'init', 'brighty_menus' );

function brighty_menus() {
    register_nav_menus(
        array(
            'user-dashboard-main-menu-not-logged-in' => __( 'Dashboard Main [NON LOGIN]' ),
            'user-dashboard-main-menu-logged-in' => __( 'Dashboard Main [LOGGED IN]' ),
            'user-dashboard-top-menu-not-logged-in' => __( 'Dashboard Top [LOGGED IN]' ),
            'user-dashboard-top-menu-logged-in' => __( 'Dashboard Top [NON LOG IN]' ),
            'user-dashboard-footer-menu-not-logged-in' => __( 'Dashboard Footer [NON LOG IN]' ),
            'user-dashboard-footer-menu-logged-in' => __( 'Dashboard Footer [LOGGED IN]' )
        )
     );
}

