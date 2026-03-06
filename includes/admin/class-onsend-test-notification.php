<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_Test_Notification {

    // Constructor
    public function __construct() {

        add_filter( 'onsend_settings_sections', array( $this, 'register_settings_sections' ) );
        add_action( 'wp_ajax_onsend_wc_send_test_notification', array( $this, 'send_test_notification' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );

        // Remove admin notices query args
        add_filter( 'removable_query_args', function( $args ) {
            $args[] = 'onsend_test_notification_success';
            $args[] = 'onsend_test_notification_error';

            return $args;
        } );

    }

    // Register the module's settings sections in the plugin settings
    public function register_settings_sections( $sections ) {

        $sections[] = array(
            'id'     => 'test_notification',
            'title'  => __( 'Send Test Message', 'onsend' ),
            'icon'   => 'fas fa-paper-plane',
            'fields' => array(
                array(
                    'id'            => 'test_notification_recipient',
                    'type'          => 'text',
                    'title'         => __( 'Recipient', 'onsend' ),
                ),
                array(
                    'id'            => 'test_notification_message',
                    'type'          => 'wp_editor',
                    'title'         => __( 'Message', 'onsend' ),
                    'default'       => __( 'Test sending WhatsApp notification through OnSend WordPress plugin.', 'onsend' ),
                    'tinymce'       => true,
                    'quicktags'     => false,
                    'media_buttons' => false,
                    'height'        => '100px',
                ),
                array(
                    'title'         => ' ',
                    'type'          => 'content',
                    'content'       => '<a class="button button-primary" href="' . esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=onsend_wc_send_test_notification' ), 'onsend_wc_send_test_notification' ) ) . '">' . esc_html__( 'Send Test Message', 'onsend' ) . '</a>',
                ),
            ),
        );

        return $sections;

    }

    // Handle actions - send WhatsApp notification
    public function send_test_notification() {

        $redirect_url = admin_url( 'admin.php?page=onsend#tab=send-test-message' );

        try {
            $nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( $_GET['_wpnonce'] ) : null;

            if ( !wp_verify_nonce( $nonce, 'onsend_wc_send_test_notification' ) ) {
                throw new Exception( __( 'Unable to send test notification. Please try again!', 'onsend' ) );
            }

            $recipient = trim( onsend_get_setting( 'test_notification_recipient' ) );
            $message   = trim( onsend_whatsapp_format_message( onsend_get_setting( 'test_notification_message' ) ) );

            if ( !$recipient ) {
                throw new Exception( __( 'Recipient is required to send test notification.', 'onsend' ) );
            }

            if ( !$message ) {
                throw new Exception( __( 'Message is required to send test notification.', 'onsend' ) );
            }

            $recipient = onsend_format_phone( $recipient );

            if ( !$recipient ) {
                throw new Exception( __( 'Invalid recipient.', 'onsend' ) );
            }

            $api = new OnSend_WC_API();

            $args = array(
                'phone_number' => $recipient,
                'message'      => $message,
                'type'         => 'text',
            );

            list( $code, $response ) = $api->send( $args );

            $response_status = isset( $response['success'] ) ? (bool) $response['success'] : false;

            if ( $response_status != true ) {
                $error_message = isset( $response['message'] ) ? $response['message'] : __( 'Unknown error', 'onsend' );
                throw new Exception( $response['message'] );
            }

            $redirect_url = add_query_arg( 'onsend_test_notification_success', true, $redirect_url );
        } catch ( Exception $e ) {
            $redirect_url = add_query_arg( 'onsend_test_notification_error', esc_html( $e->getMessage() ), $redirect_url );
        }

        wp_redirect( $redirect_url );
        exit;

    }

    // Show admin notice(s) for send test notification
    public function admin_notices() {

        global $pagenow;

        $page = isset( $_GET['page'] ) ? wp_unslash( $_GET['page'] ) : null;

        if ( !( $pagenow === 'admin.php' && $page === 'onsend' ) ) {
            return;
        }

        $success = isset( $_GET['onsend_test_notification_success'] ) ? wp_unslash( $_GET['onsend_test_notification_success'] ) : false;
        $error = isset( $_GET['onsend_test_notification_error'] ) ? wp_unslash( $_GET['onsend_test_notification_error'] ) : false;

        if ( $success === '1' ) {
            onsend_notice( esc_html__( 'Successfully send test notification.', 'onsend' ) );
        } elseif ( $error ) {
            onsend_notice( esc_html( sprintf( __( 'Failed sending test notification: %s', 'onsend' ), $error ) ) );
        }
    }

}
new OnSend_Test_Notification();
