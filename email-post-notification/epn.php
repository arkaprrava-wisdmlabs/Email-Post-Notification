<?php

/*
Plugin Name: Email Post Notification
Description: This is a Plugin for mailing daily latest post to the admin daily
Version: 1.0.0
Author: Arkaprava
Text Domain:   epn
Domain Path:   /lang
*/

if(!defined('ABSPATH')){
    die();
}


function epn_activation(){
    if ( !wp_next_scheduled( 'email_latest_posts_to_admin_daily' ) ) {
        wp_schedule_event( time(), 'daily', 'email_latest_posts_to_admin_daily' );
    }
}
register_activation_hook( __FILE__, 'epn_activation' );

function epn_deactivation(){
    $timestamp = wp_next_scheduled( 'email_latest_posts_to_admin_daily' );
    wp_unschedule_event( $timestamp, 'email_latest_posts_to_admin_daily' );
}
register_deactivation_hook( __FILE__, 'epn_deactivation' );

add_action( 'email_latest_posts_to_admin_daily', 'epn_email_content' );
include( 'includes\epn-activator.php' );