<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend {

    // Constructor
    public function __construct() {

        // Libraries
        require_once( ONSEND_PATH . 'libraries/codestar-framework/codestar-framework.php' );
        require_once( ONSEND_PATH . 'libraries/meta-box/meta-box.php' );

        // Functions
        require_once( ONSEND_PATH . 'includes/functions/core.php' );
        require_once( ONSEND_PATH . 'includes/functions/notifications.php' );
        require_once( ONSEND_PATH . 'includes/functions/shortcodes.php' );

        // API
        require_once( ONSEND_PATH . 'includes/abstracts/abstract-onsend-client.php' );
        require_once( ONSEND_PATH . 'includes/class-onsend-api.php' );

        // Admin
        require_once( ONSEND_PATH . 'includes/admin/class-onsend-admin.php' );
        require_once( ONSEND_PATH . 'includes/admin/class-onsend-settings.php' );
        require_once( ONSEND_PATH . 'includes/admin/class-onsend-test-notification.php' );

        // WhatsApp editor
        require_once( ONSEND_PATH . 'includes/admin/class-onsend-whatsapp-editor.php' );

        // WooCommerce module
        require_once( ONSEND_PATH . 'includes/modules/woocommerce/class-onsend-wc.php' );

    }

}
new OnSend();
