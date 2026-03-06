<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Get the global shortcodes
function onsend_get_shortcodes() {

    $shortcodes = array(
        'site_title' => __( 'Site title', 'onsend' ),
        'site_url'   => __( 'Site URL', 'onsend' ),
    );

    return apply_filters( 'onsend_shortcodes', $shortcodes );

}

// Get the values of the global shortcodes
function onsend_get_shortcodes_value() {

    $shortcodes_value = array(
        'site_title' => get_bloginfo(),
        'site_url'   => get_site_url(),
    );

    return apply_filters( 'onsend_shortcodes_value', $shortcodes_value );

}

// Convert shortcodes to corresponding values
function onsend_do_shortcode( $content, array $shortcodes = array() ) {

    $global_shortcodes = onsend_get_shortcodes_value();
    $shortcodes = array_merge( $global_shortcodes, $shortcodes );

    preg_match_all( '/{(.*?)}/', $content, $matches );
    preg_match_all( '/\((.*?)\)/', $content, $text_spinner_matches );

    // Check for shortcode tags in the content
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
            $content = str_replace( $shortcode_tag, $shortcode_value, $content );
        }
    }

    // Format unicode
    $content = html_entity_decode( $content, ENT_COMPAT, 'UTF-8' );

    // Format br tag to new line
    $content = str_replace(
        array( '<br>', '<br/>' ),
        "\n",
        $content
    );

    // Limit new lines to maximum of two (2)
    $content = preg_replace( '/(\r?\n){2,}/', "\n\n", $content );

    // Remove HTML tags
    $content = wp_strip_all_tags( $content );

    return $content;

}

// Returns an HTML table of shortcodes
function onsend_get_shortcodes_html( array $shortcodes ) {

    ob_start();
    include( ONSEND_PATH . 'includes/views/html-shortcodes-table.php' );
    return ob_get_clean();

}
