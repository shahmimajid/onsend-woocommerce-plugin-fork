<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_WC {

    // Constructor
    public function __construct() {

        if ( !$this->is_woocommerce_activated() ) {
            return;
        }

        // Functions
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/functions/core.php' );
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/functions/notifications.php' );
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/functions/shortcodes.php' );

        // API
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/class-onsend-wc-api.php' );

        // Notifications
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/class-onsend-wc-notifications.php' );

        // Admin
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/admin/class-onsend-wc-admin.php' );
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/admin/class-onsend-wc-settings.php' );

    }

    // Check if WooCommerce is installed and activated
    private function is_woocommerce_activated() {
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }

}
new OnSend_WC();
