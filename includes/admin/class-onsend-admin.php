<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_Admin {

    // Constructor
    public function __construct() {

        add_action( 'plugin_action_links_' . ONSEND_BASENAME, array( $this, 'register_settings_link' ) );

    }

    // Register plugin settings link
    public function register_settings_link( $links ) {

        $url = admin_url( 'admin.php?page=onsend' );
        $label = esc_html__( 'Settings', 'onsend' );

        $settings_link = '<a href="' . esc_url( $url ) . '">' . $label . '</a>';
        array_unshift( $links, $settings_link );

        return $links;

    }

}
new OnSend_Admin();
