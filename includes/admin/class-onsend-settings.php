<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_Settings {

    private $id = 'onsend';

    // Constructor
    public function __construct() {

        add_action( 'plugins_loaded', array( $this, 'register_page' ), 20 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );

    }

    // Enqueue styles and scripts
    public function enqueue_scripts() {

        wp_enqueue_style( 'onsend-settings', ONSEND_URL . 'assets/css/admin.css', array(), ONSEND_VERSION );

    }

    // Register settings page
    public function register_page() {

        if ( !class_exists( 'CSF' ) ) {
            return;
        }

        CSF::createOptions( $this->id, $this->get_args() );

        foreach ( $this->get_sections() as $section ) {
            CSF::createSection( $this->id, $section );
        }

    }

    // Settings page configuration
    private function get_args() {

        $title = __( 'OnSend', 'onsend' );

        $logo_url = ONSEND_URL . 'assets/images/onsend-logo.png';
        $logo_img = '<img class="onsend-logo" src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $title ) . '">';

        return array(
            'framework_title' => $logo_img,
            'framework_class' => 'onsend-settings',
            'menu_title'      => $title,
            'menu_slug'       => $this->id,
            'menu_icon'       => ONSEND_URL . 'assets/images/onsend-icon.png',
            'menu_position'   => 58,
            'show_bar_menu'   => false,
            'show_search'     => false,
            'theme'           => 'onsend',
            'footer_text'     => '',
        );

    }

    // Settings sections
    private function get_sections() {

        $sections = array(
            array(
                'title'            => __( 'API Credentials', 'onsend' ),
                'description'      => __( 'API credentials can be obtained from OnSend dashboard.', 'onsend' ),
                'icon'             => 'fas fa-sign-in-alt',
                'fields'           => array(
                    array(
                        'id'      => 'token',
                        'type'    => 'text',
                        'title'   => __( 'Token', 'onsend' ),
                    ),
                    array(
                        'id'      => 'debug',
                        'type'    => 'switcher',
                        'title'   => __( 'Debug Mode', 'onsend' ),
                        'desc'    => sprintf( __( 'Turn ON to enable debug logging. Logs can be viewed on WooCommerce > Status > <a href="%s">Logs</a>.', 'onsend-wc' ), esc_url( admin_url( 'admin.php?page=wc-status&tab=logs' ) ) ),
                    ),
                ),
            ),
        );

        $sections = apply_filters( 'onsend_settings_sections', $sections );

        $sections[] = array(
            'title'  => __( 'Backup', 'onsend-wc' ),
            'icon'   => 'fas fa-shield-alt',
            'fields' => array(
                array(
                    'type' => 'backup',
                ),
            ),
        );

        return $sections;

    }

}
new OnSend_Settings();
