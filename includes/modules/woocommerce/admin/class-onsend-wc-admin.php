<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_WC_Admin {

    // Constructor
    public function __construct() {

        // Columns
        add_filter( 'manage_edit-shop_order_columns', array( $this, 'register_columns' ) );
        add_action( 'manage_shop_order_posts_custom_column', array( $this, 'populate_columns' ), 10, 2 );

        // Actions
        add_filter( 'woocommerce_admin_order_actions', array( $this, 'register_actions' ), 10, 2 );
        add_action( 'wp_ajax_onsend_wc_send_notifications', array( $this, 'send_notifications' ) );

        // Bulk actions
        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'register_bulk_actions' ) );
        add_action( 'handle_bulk_actions-edit-shop_order', array( $this, 'handle_bulk_actions' ), 10, 3 );

        // Notices
        add_action( 'admin_notices', array( $this, 'order_action_notices' ) );
        add_action( 'admin_notices', array( $this, 'bulk_action_notices' ) );

        // Metabox
        add_filter( 'rwmb_meta_boxes', array( $this, 'register_metabox' ) );

        // Remove admin notices query args
        add_filter( 'removable_query_args', function( $args ) {
            $args[] = 'onsend_action';

            return $args;
        } );

    }

    // Register additional column - notification status
    public function register_columns( $columns ) {

        $new_columns = array();

        foreach ( $columns as $name => $value ) {
            $new_columns[ $name ] = $value;

            // Register notification status column before "Actions" column
            if ( $name === 'wc_actions' ) {
                $new_columns['onsend_wc_notification_status'] = __( 'OnSend Notification Status', 'onsend' );
            }
        }

        return $new_columns;

    }

    // Populate additional column - notification status
    public function populate_columns( $column, $order_id ) {

        global $the_order;

        switch ( $column ) {
            case 'onsend_wc_notification_status':
                $current_notification_status = null;

                $notifications_status_pending_state = get_transient( "onsend_wc_order_{$order_id}_notifications_status_pending" );

                if ( $notifications_status_pending_state === '1' ) {
                    $current_notification_status = 'pending';
                } else {
                    $notifications_status = get_post_meta( $order_id, '_onsend_wc_notifications_status', true );

                    if ( isset( $notifications_status[0]['success'] ) ) {
                        if ( $notifications_status[0]['success'] == true ) {
                            $current_notification_status = 'success';
                        } else {
                            $current_notification_status = 'failed';
                        }
                    }
                }

                if ( $current_notification_status ) {
                    echo '<span class="onsend-wc-notification-status status-' . $current_notification_status . ' tips" data-tip="' . esc_attr( $notifications_status[0]['message'] ) . '">' . ucwords( $current_notification_status ) . '</span>';
                } else {
                    echo '&ndash;';
                }
                break;
        }

    }

    // Register actions button
    public function register_actions( $actions, $order ) {

        $actions['onsend_wc_send_notifications'] = array(
            'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=onsend_wc_send_notifications&order_id=' . $order->get_id() ), 'onsend_wc_send_notifications' ),
            'name'   => __( 'Send Messaging Notifications', 'onsend' ),
            'action' => 'onsend_wc_send_notifications',
        );

        return $actions;

    }

    // Handle actions - send Messenging notification
    public function send_notifications() {

        $nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( $_GET['_wpnonce'] ) : null;
        $order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : null;

        if ( !wp_verify_nonce( $nonce, 'onsend_wc_send_notifications' ) ) {
            return false;
        }

        if ( !$order_id ) {
            return false;
        }

        set_transient( "onsend_wc_order_{$order_id}_notifications_status_pending", true, DAY_IN_SECONDS );
        wp_schedule_single_event( time(), 'onsend_wc_schedule_send_notifications', array( $order_id ) );

        wp_redirect( admin_url( 'edit.php?post_type=shop_order&onsend_action=send_notifications&order_id=' . $order_id ) );
        exit;

    }

    // Register bulk actions
    public function register_bulk_actions( $actions ) {

        $actions['onsend_wc_send_notifications'] = __( 'Send messaging notifications', 'onsend' );
        return $actions;

    }

    // Handle bulk actions
    public function handle_bulk_actions( $redirect, $action, $order_ids ) {

        if ( $action !== 'onsend_wc_send_notifications' ) {
            return $redirect;
        }

        $changed = 0;

        foreach ( $order_ids as $order_id ) {
            $order = wc_get_order( $order_id );

            if ( $order ) {
                $changed++;

                set_transient( "onsend_wc_order_{$order_id}_notifications_status_pending", true, DAY_IN_SECONDS );
                wp_schedule_single_event( time(), 'onsend_wc_schedule_send_notifications', array( $order_id ) );
            }
        }

        return add_query_arg( array(
            'bulk_action' => 'onsend_wc_send_notifications',
            'changed'     => $changed,
            'ids'         => implode( ',', $order_ids ),
        ), $redirect );

    }

    // Show admin notice(s) for order action
    public function order_action_notices() {

        global $pagenow, $post_type;

        if ( !( $pagenow === 'edit.php' && $post_type === 'shop_order' ) ) {
            return;
        }

        $action_name = isset( $_GET['onsend_action'] ) ? wp_unslash( $_GET['onsend_action'] ) : null;
        $order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : null;

        if ( $action_name === 'send_notifications' ) {
            onsend_notice( esc_html( sprintf( __( 'Sending notification(s) for order #%d.', 'onsend' ), $order_id ) ) );
        }
    }

    // Show admin notice(s) for bulk action
    public function bulk_action_notices() {

        global $pagenow, $post_type;

        if ( !( $pagenow === 'edit.php' && $post_type === 'shop_order' ) ) {
            return;
        }

        $changed = isset( $_GET['changed'] ) ? absint( $_GET['changed'] ) : 0;

        if ( $changed <= 0 ) {
            return;
        }

        $action_name = isset( $_GET['bulk_action'] ) ? wp_unslash( $_GET['bulk_action'] ) : null;

        if ( $action_name === 'onsend_wc_send_notifications' ) {
            onsend_notice( esc_html( sprintf( _n( 'Sending notification(s) for %d order.', 'Sending notification(s) for %d orders.', $changed, 'onsend' ), number_format_i18n( $changed ) ) ) );
        }

    }

    // Register metabox for notifications status
    public function register_metabox( $metaboxes ) {

        $metaboxes[] = array(
            'title'      => esc_html__( 'OnSend Notifications Status', 'onsend' ),
            'id'         => 'onsend-wc-notifications-status',
            'post_types' => 'shop_order',
            'context'    => 'side',
            'priority'   => 'low',
            'fields'     => array(
                array(
                    'type'     => 'custom_html',
                    'callback' => array( $this, 'html_notifications_status' ),
                ),
            ),
        );

        return $metaboxes;

    }

    // Returns an HTML of OnSend notifications status for the WooCommerce order
    public function html_notifications_status() {

        ob_start();
        include( ONSEND_PATH . 'includes/modules/woocommerce/admin/views/html-notifications-status.php' );
        return ob_get_clean();

    }

}
new OnSend_WC_Admin();
