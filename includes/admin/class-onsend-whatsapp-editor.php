<?php
if ( !defined( 'ABSPATH' ) ) exit;

class OnSend_Whatsapp_Editor {

    // Constructor
    public function __construct() {

        add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_args' ), 10, 2 );

    }

    // Modify WP editor - tinymce args for WhatsApp editor
    public function tiny_mce_args( $mce_init, $editor_id ) {

        if ( onsend_is_settings_page() && $editor_id === 'csf_wp_editor' ) {
            $mce_init['toolbar1'] = 'bold,italic,strikethrough,undo,redo';
            $mce_init['toolbar2'] = '';
            $mce_init['toolbar3'] = '';
            $mce_init['toolbar4'] = '';
        }

        return $mce_init;

    }

    // Converts HTML content to WhatsApp formatted content
    public static function get_formatted_content( $html ) {

        $search = array(
            '<strong>', '</strong>', '<b>', '</b>',
            '<em>', '</em>', '<i>', '</i>',
            '<del>', '</del>', '<strike>', '</strike>', '<s>', '</s>',
        );

        $replace = array_merge(
            array_fill( 0, 4, '*' ),
            array_fill( 0, 4, '_' ),
            array_fill( 0, 6, '~' ),
        );

        $formatted_content = str_replace( $search, $replace, $html );

        return $formatted_content;

    }

}
new OnSend_Whatsapp_Editor();
