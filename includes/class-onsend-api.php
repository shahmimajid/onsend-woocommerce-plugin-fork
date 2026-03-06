<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_API extends OnSend_Client {

    // Constructor
    public function __construct( $token, $debug = false ) {

        $this->token = $token;
        $this->debug = $debug;

    }

    // Send a message
    public function send( array $params ) {
        return $this->post( 'v1/send', $params );
    }

}
