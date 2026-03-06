<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_WC_API extends OnSend_API {

    // Constructor
    public function __construct() {

        $this->token = onsend_get_setting( 'token' );
        $this->debug = onsend_get_setting( 'debug' ) === '1' ? true : false;

    }

    // Log a message in the WooCommerce logs
    protected function log( $message ) {

        if ( $this->debug ) {
            onsend_wc_logger( $message, 'api' );
        }

    }

}
