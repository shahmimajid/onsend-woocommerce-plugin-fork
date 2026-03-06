<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Get plugin setting value by its key
function onsend_get_setting( $key, $default = null ) {

    $settings = get_option( 'onsend' );

    if ( isset( $settings[ $key ] ) && !empty( $settings[ $key ] ) ) {
        return $settings[ $key ];
    }

    return $default;

}

// Check if the current admin page is our plugin settings page
function onsend_is_settings_page() {

    global $pagenow;

    $page = isset( $_GET['page'] ) ? wp_unslash( $_GET['page'] ) : null;

    if ( $pagenow === 'admin.php' && $page === 'onsend' ) {
        return true;
    }

    return false;

}

// Display a notice
function onsend_notice( $message, $type = 'success' ) {

    $plugin = esc_html__( 'OnSend', 'onsend' );

    printf( '<div class="notice notice-%1$s"><p><strong>%2$s:</strong> %3$s</p></div>', esc_attr( $type ), $plugin, $message );

}

// Returns a list of accepted media type whens sending a notification
function onsend_get_accepted_media_type() {

    return array(
        'image'    => __( 'Image', 'onsend' ),
        'video'    => __( 'Video', 'onsend' ),
        'document' => __( 'Document', 'onsend' ),
    );

}

// Format a phone number
function onsend_format_phone( $phone ) {

    // Get numbers only
    $phone = preg_replace('/[^0-9]/', '', $phone );

    // Add country code in the front of phone number if the phone number starts with zero (0)
    if ( strpos( $phone, '0' ) === 0 ) {
        $phone = '6' . $phone;
    }

    return $phone;

}

// Converts HTML content to WhatsApp formatted content
function onsend_whatsapp_format_message( $message ) {

    if ( method_exists( 'OnSend_Whatsapp_Editor', 'get_formatted_content' ) ) {
        return OnSend_Whatsapp_Editor::get_formatted_content( $message );
    }

    return $message;

}
