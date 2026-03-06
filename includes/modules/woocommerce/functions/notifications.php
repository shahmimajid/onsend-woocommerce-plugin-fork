<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Parse (set the default values) specific WooCommerce notification settings from the plugin settings
function onsend_wc_parse_notification_settings( array &$notification ) {

    $notification = wp_parse_args( $notification, array(
        'title'        => '',
        'order_status' => '',
        'message'      => '',
        'media'        => '',
        'media_url'    => '',
    ) );

    return $notification;

}

// Get the list of WooCommerce notifications status
function onsend_wc_get_notifications_status( $order_id ) {

    $all_notifications_status = get_post_meta( $order_id, '_onsend_wc_notifications_status', true );

    if ( !$all_notifications_status || !is_array( $all_notifications_status ) ) {
        return array();
    }

    $notification_status_defaults = array(
        'success'   => false,
        'message'   => null,
        'timestamp' => null,
        'title'     => null,
        'phone'     => null,
    );

    // Remove empty notifications
    $all_notifications_status = array_filter( $all_notifications_status, function( $notification_status ) use ( $notification_status_defaults ) {
        $notification_status = wp_parse_args( $notification_status, $notification_status_defaults );

        return $notification_status['timestamp'] && $notification_status['phone'];
    } );

    // Sort by timestamp
    usort( $all_notifications_status, function( $a, $b ) {
        return $a['timestamp'] < $b['timestamp'];
    } );

    return $all_notifications_status;

}

// Get the list of WooCommerce notifications status
function onsend_wc_update_notifications_status( $order_id, array $notification_status ) {

    delete_transient( "onsend_wc_order_{$order_id}_notifications_status_pending" );

    $notification_status_defaults = array(
        'success'   => false,
        'message'   => null,
        'timestamp' => null,
        'title'     => null,
        'phone'     => null,
    );

    $notification_status = wp_parse_args( $notification_status, $notification_status_defaults );

    $all_notifications_status = onsend_wc_get_notifications_status( $order_id );
    array_unshift( $all_notifications_status, $notification_status );

    return update_post_meta( $order_id, '_onsend_wc_notifications_status', $all_notifications_status );

}
