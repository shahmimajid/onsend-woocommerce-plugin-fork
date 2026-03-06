<?php
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$notifications_status = onsend_wc_get_notifications_status( $post->ID );

if ( $notifications_status ) :
    ?>
    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=onsend_wc_send_notifications&order_id=' . $post->ID ), 'onsend_wc_send_notifications' ) ); ?>" class="button onsend-wc-notification-send-btn"><?php esc_html_e( 'Resend Notification(s)', 'onsend' ); ?></a>
    <ul class="onsend-wc-notifications-status-list">
        <?php foreach ( $notifications_status as $notification_status ) : ?>
            <li class="notification-status status-<?php echo ( $notification_status['success'] == true ? 'success' : 'failed' ); ?>">
                <div class="notification-status-message">
                    <?php
                    if ( empty( $notification_status['title'] ) ) {
                        $notification_status['title'] = '(untitled)';
                    }

                    if ( $notification_status['success'] == true ) {
                        printf( __( 'Successfully send <strong>%1$s</strong> notification to <strong>%2$s</strong>.', 'onsend' ), $notification_status['title'], $notification_status['phone'] );
                    } else {
                        if ( $notification_status['message'] ) {
                            printf( __( 'Failed to send <strong>%1$s</strong> notification to <strong>%2$s</strong>: %3$s' ), $notification_status['title'], $notification_status['phone'], $notification_status['message'] );
                        } else {
                            printf( __( 'Failed to send <strong>%1$s</strong> notification to <strong>%2$s</strong>' ), $notification_status['title'], $notification_status['phone'] );
                        }
                    }
                    ?>
                </div>
                <span class="notification-status-timestamp">
                    <?php
                    echo sprintf(
                        esc_html__( '%1$s at %2$s', 'onsend' ),
                        wp_date( wc_date_format(), $notification_status['timestamp'] ),
                        wp_date( wc_time_format(), $notification_status['timestamp'] )
                    );
                    ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <span class="onsend-wc-notifications-status-alert"><?php esc_html_e( 'No notifications sent yet.', 'onsend' ); ?></span>
    <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=onsend_wc_send_notifications&order_id=' . $post->ID ), 'onsend_wc_send_notifications' ) ); ?>" class="button onsend-wc-notification-send-btn"><?php esc_html_e( 'Send Notification(s)', 'onsend' ); ?></a>
<?php endif; ?>
