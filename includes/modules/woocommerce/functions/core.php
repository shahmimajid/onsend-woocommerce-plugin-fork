<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Log a message in the WooCommerce logs
function onsend_wc_logger( $message, $group = '' ) {

    if ( !function_exists( 'wc_get_logger' ) ) {
        return false;
    }

    $handle = 'onsend';

    if ( $group ) {
        $handle .= '-' . $group;
    }

    return wc_get_logger()->add( $handle, $message );

}
