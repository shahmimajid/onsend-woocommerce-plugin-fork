<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_WC_Settings {

    // Constructor
    public function __construct() {

        add_filter( 'onsend_settings_sections', array( $this, 'register_sections' ) );

    }

    // Register the module's settings sections in the plugin settings
    public function register_sections( $sections ) {

        $accepted_media_type = onsend_get_accepted_media_type();

        $wc_sections = array(
            array(
                'id'     => 'woocommerce',
                'title'  => __( 'WooCommerce', 'onsend' ),
                'icon'   => 'fas fa-shopping-cart',
            ),
            array(
                'parent' => 'woocommerce',
                'title'  => __( 'General', 'onsend' ),
                'fields' => array(
                    array(
                        'id'      => 'woocommerce_enable',
                        'type'    => 'switcher',
                        'title'   => __( 'Enable Module', 'onsend' ),
                        'desc'    => __( 'Turn ON to enable WhatsApp notification for WooCommerce.', 'onsend' ),
                        'default' => true,
                    ),
                ),
            ),
            array(
                'parent' => 'woocommerce',
                'title'  => __( 'Notifications', 'onsend' ),
                'fields' => array(
                    array(
                        'id'       => 'woocommerce_notifications',
                        'type'     => 'group',
                        'title'    => __( 'Notification Content', 'onsend' ),
                        'subtitle' => __( 'Send a notification to the customer when a new order is placed or the order status changes.', 'onsend' ),
                        'fields'   => array(
                            array(
                                'id'            => 'title',
                                'type'          => 'text',
                                'title'         => __( 'Title', 'onsend' ),
                            ),
                            array(
                                'id'            => 'recipients',
                                'type'          => 'text',
                                'title'         => __( 'Recipient(s)', 'onsend' ),
                                'desc'          => __( 'Enter recipients (comma-separated) for this notification.<br>Defaults to customer\'s billing phone number.', 'onsend' ),
                                'default'       => '{billing_phone}',
                            ),
                            array(
                                'id'            => 'order_status',
                                'type'          => 'select',
                                'title'         => __( 'Order Status', 'onsend' ),
                                'options'       => wc_get_order_statuses(),
                            ),
                            array(
                                'id'            => 'message',
                                'type'          => 'wp_editor',
                                'title'         => __( 'Message', 'onsend' ),
                                'tinymce'       => true,
                                'quicktags'     => false,
                                'media_buttons' => false,
                                'height'        => '100px',
                            ),
                            array(
                                'id'            => 'media',
                                'type'          => 'select',
                                'title'         => __( 'Media', 'onsend' ),
                                'options'       => array_merge( array(
                                    'none' => __( 'None', 'onsend' ),
                                ), $accepted_media_type ),
                            ),
                            array(
                                'id'            => 'media_url',
                                'type'          => 'text',
                                'title'         => __( 'Media URL', 'onsend' ),
                                'validate'      => 'csf_validate_url',
                                'dependency'    => array( 'media', 'any', implode( ',', array_keys( $accepted_media_type ) ) ),
                            ),
                        ),
                        'default'    => array(),
                        'dependency' => array( 'woocommerce_enable', '==', 'true', 'all', 'visible' ),
                    ),
                ),
            ),
            array(
                'parent' => 'woocommerce',
                'title'  => __( 'Shortcodes', 'onsend' ),
                'fields' => array(
                    array(
                        'id'         => 'woocommerce_shortcodes',
                        'type'       => 'content',
                        'title'      => __( 'Available Shortcodes', 'onsend' ),
                        'subtitle'   => __( 'Use shortcodes below in your notification message field.', 'onsend' ),
                        'content'    => onsend_wc_get_shortcodes_html(),
                        'dependency' => array( 'woocommerce_enable', '==', 'true', 'all', 'visible' ),
                    ),
                ),
            ),
        );

        return array_merge( $sections, $wc_sections );

    }

}
new OnSend_WC_Settings();
