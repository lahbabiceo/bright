<?php


// add brighty css files


add_action( 'wp_enqueue_scripts', 'brighty_styles' );

function brighty_styles() {

    wp_enqueue_style( 'brighty_css', BRIGHTY_CORE_PLUGIN_URL . 'css/style.css', false, '1.0.0' );

}