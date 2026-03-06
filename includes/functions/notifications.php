<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Get the list of specific notifications from the plugin settings, eg: $key = woocommerce_notifications
function onsend_get_notifications_settings( $key ) {

    $notifications = onsend_get_setting( $key );

    if ( !$notifications ) {
        return false;
    }

    // Remove empty notification
    $notifications = array_filter( $notifications, function( $notification ) {
        return isset( $notification['message'] ) && !empty( $notification['message'] );
    } );

    return $notifications;

}
