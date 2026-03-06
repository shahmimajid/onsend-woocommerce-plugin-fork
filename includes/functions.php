<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Display a notice
function onsend_notice( $message, $type = 'success' ) {

    $plugin = esc_html__( 'OnSend', 'onsend' );

    printf( '<div class="notice notice-%1$s"><p><strong>%2$s:</strong> %3$s</p></div>', esc_attr( $type ), $plugin, $message );

}
