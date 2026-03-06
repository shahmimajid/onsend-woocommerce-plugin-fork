<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_WC_Notifications {

    // Constructor
    public function __construct() {

        $is_enabled = onsend_get_setting( 'woocommerce_enable' ) == '1';

        if ( !$is_enabled ) {
            return;
        }

        add_action( 'onsend_wc_send_notification', array( $this, 'send_notification' ), 10, 3 );

        add_action( 'onsend_wc_schedule_send_notifications', array( $this, 'schedule_send_notifications' ) );
        add_action( 'woocommerce_new_order', array( $this, 'schedule_send_notifications' ) );
        add_action( 'woocommerce_order_status_changed', array( $this, 'schedule_send_notifications' ) );

    }

    // Send a notification to the specified phone number
    public function send_notification( $notification, $order_id, $phone ) {

        $order = wc_get_order( $order_id );

        if ( !$order ) {
            return false;
        }

        onsend_wc_parse_notification_settings( $notification );

        if ( $notification['order_status'] !== 'wc-' . $order->get_status() ) {
            return false;
        }

        if ( !$notification['message'] && !$notification['media_url'] ) {
            return false;
        }

        try {
            $api = new OnSend_WC_API();

            $phone = onsend_format_phone( $phone );
            $content = onsend_wc_do_shortcode( onsend_whatsapp_format_message( $notification['message'] ), $order );

            $args = array(
                'phone_number' => $phone,
                'message'      => $content,
                'type'         => 'text',
            );

            $accepted_media_type = array_keys( onsend_get_accepted_media_type() );

            if ( in_array( $notification['media'], $accepted_media_type ) ) {
                $args['type'] = $notification['media'];
                $args['url'] = $notification['media_url'];

                if ( $notification['media'] === 'document' ) {
                    $args['mimetype'] = mime_content_type( $notification['media_url'] );
                    $args['filename'] = $notification['media_filename'];
                }
            }

            list( $code, $response ) = $api->send( $args );

            if ( is_array( $response ) && !empty( $response ) ) {
                $response_status = isset( $response['success'] ) ? (bool) $response['success'] : false;
                $response_message = isset( $response['message'] ) ? sanitize_text_field( $response['message'] ) : null;

                onsend_wc_update_notifications_status( $order_id, array(
                    'success'   => $response_status,
                    'message'   => $response_message,
                    'timestamp' => time(),
                    'title'     => $notification['title'],
                    'phone'     => $phone,
                ) );
            }

            return $response;
        } catch ( Exception $e ) {
            onsend_wc_update_notifications_status( $order_id, array(
                'success'   => false,
                'message'   => sanitize_text_field( wp_strip_all_tags( $e->getMessage() ) ),
                'timestamp' => time(),
                'title'     => $notification['title'],
                'phone'     => $phone,
            ) );
        }

        return false;

    }

    // Send all notifications for the specified order
    public function schedule_send_notifications( $order_id ) {

        if ( !function_exists( 'wc_get_order' ) ) {
            return false;
        }

        $order = wc_get_order( $order_id );

        if ( !$order ) {
            return false;
        }

        $notifications = onsend_get_notifications_settings( 'woocommerce_notifications' );

        if ( !$notifications ) {
            return false;
        }

        foreach ( $notifications as $notification ) {
            onsend_wc_parse_notification_settings( $notification );

            if ( $notification['order_status'] === 'wc-' . $order->get_status() ) {
                // Community Fork Fix: Idempotency Guard to prevent duplicate messages
                // Create a unique key based on notification title, target status, and order ID
                $notification_id = md5( $notification['title'] . $notification['order_status'] . $order_id );
                $already_sent = get_post_meta( $order_id, '_onsend_sent_' . $notification_id, true );

                if ( ! $already_sent ) {
                    $recipients = $this->get_notification_recipients( $notification['recipients'], $order );

                    foreach ( $recipients as $recipient ) {
                        wp_schedule_single_event( time(), 'onsend_wc_send_notification', array( $notification, $order_id, $recipient ) );
                    }

                    // Mark as scheduled/sent to prevent duplicates in the same status cycle
                    update_post_meta( $order_id, '_onsend_sent_' . $notification_id, time() );
                }
            }
        }

    }

    // Get the recipient(s) for the notification for the specified order
    // refer onsend_do_shortcode
    private function get_notification_recipients( $recipients, $order ) {

        $shortcodes = onsend_wc_get_shortcodes_value( $order );

        preg_match_all( '/{(.*?)}/', $recipients, $matches );
        preg_match_all( '/\((.*?)\)/', $recipients, $text_spinner_matches );

        // Check for shortcode tags in the recipients value
        if ( isset( $matches[1][0] ) ) {
            for ( $i = 0; $i < count( $matches[1] ); $i++ ) {
                $shortcode_tag = $matches[0][ $i ];

                // Get shortcode tag without its attributes
                $shortcode_key = explode( ' ', $matches[1][ $i ] );
                $shortcode_key = isset( $shortcode_key[0] ) ? $shortcode_key[0] : false;

                if ( !$shortcode_key ) {
                    continue;
                }

                // Get real value of the shortcode
                $shortcode_value = isset( $shortcodes[ $shortcode_key ] ) ? $shortcodes[ $shortcode_key ] : false;

                // Replace the shortcode tag with its real value
                $recipients = str_replace( $shortcode_tag, $shortcode_value, $recipients );
            }
        }

        $recipients = explode( ',', $recipients );
        $recipients = array_map( 'trim', $recipients );
        $recipients = array_unique( $recipients );

        return $recipients;

    }

}
new OnSend_WC_Notifications();
