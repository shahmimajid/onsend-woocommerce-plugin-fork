<?php
if ( !defined( 'ABSPATH' ) ) exit;

abstract class OnSend_Client {

    const BASE_URL = 'https://onsend.io/api/';

    protected $token;
    protected $debug;

    // HTTP request URL
    private function get_url( $route = null ) {
        return self::BASE_URL . $route;
    }

    // HTTP request headers
    private function get_headers() {

        return array(
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        );

    }

    // HTTP GET request
    protected function get( $route, $params = array() ) {
        return $this->request( $route, $params, 'GET' );
    }

    // HTTP POST request
    protected function post( $route, $params = array() ) {
        return $this->request( $route, $params );
    }

    // HTTP request
    protected function request( $route, $params = array(), $method = 'POST' ) {

        if ( !$this->token ) {
            throw new Exception( 'Missing authentication token' );
        }

        $url = $this->get_url( $route );

        $args = array(
            'headers' => $this->get_headers(),
            'body'    => $params,
            'timeout' => 30,
        );

        $this->log( 'URL: ' . $url );
        $this->log( 'Headers: ' . wp_json_encode( $args['headers'] ) );
        $this->log( 'Body: ' . wp_json_encode( $params ) );

        switch ( $method ) {
            case 'GET':
                $response = wp_remote_get( $url, $args );
                break;

            case 'POST':
                $response = wp_remote_post( $url, $args );
                break;

            default:
                $args['method'] = $method;
                $response = wp_remote_request( $url, $args );
        }

        if ( is_wp_error( $response ) ) {
            $this->log( 'Response Error: ' . $response->get_error_message() );
            throw new Exception( $response->get_error_message() );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        $this->log( 'Response: ' . wp_json_encode( $body ) );

        return array( $code, $body );

    }

    // Debug logging
    protected function log( $message ) {
        return;
    }

}
