<?php
if ( !defined( 'ABSPATH' ) ) exit;

// Get WooCommerce shortcodes
function onsend_wc_get_shortcodes() {

    return apply_filters( 'onsend_wc_shortcodes', array(
        'id'                      => __( 'Order ID', 'onsend' ),
        'key'                     => __( 'Order key', 'onsend' ),
        'checkout_url'            => __( 'Order checkout page URL (for pending order)', 'onsend' ),
        'status'                  => __( 'Order status', 'onsend' ),
        'total'                   => __( 'Total amount of order', 'onsend' ),
        'discount_total'          => __( 'Total amount of discount', 'onsend' ),
        'shipping_total'          => __( 'Total amount of shipping', 'onsend' ),
        'tax_total'               => __( 'Total amount of tax', 'onsend' ),
        'total_refunded'          => __( 'Total amount of refunded item(s)', 'onsend' ),
        'total_tax_refunded'      => __( 'Total amount of tax for refunded item(s)', 'onsend' ),
        'total_shipping_refunded' => __( 'Total amount of shipping for refunded item(s)', 'onsend' ),
        'item_count_refunded'     => __( 'Total refunded item', 'onsend' ),
        'total_qty_refunded'      => __( 'Total quantity of refunded item(s)', 'onsend' ),
        'items'                   => __( 'List of items', 'onsend' ),
        'downloads'               => __( 'List of digital product download URLs', 'onsend' ),
        'shipping_method'         => __( 'Shipping method for the order', 'onsend' ),
        'date'                    => __( 'Date of order creation', 'onsend' ),
        'completed_date'          => __( 'Date of order completion', 'onsend' ),
        'paid_date'               => __( 'Date of order\'s successful payment', 'onsend' ),
        'customer_ip_address'     => __( 'Customer\'s IP address', 'onsend' ),
        'customer_note'           => __( 'Customer\'s note', 'onsend' ),
        'customer_notes'          => __( 'All customer\'s notes', 'onsend' ),
        'billing_first_name'      => __( 'Billing first name', 'onsend' ),
        'billing_last_name'       => __( 'Billing last name', 'onsend' ),
        'billing_full_name'       => __( 'Billing full name', 'onsend' ),
        'billing_company'         => __( 'Billing company name', 'onsend' ),
        'billing_email'           => __( 'Billing email', 'onsend' ),
        'billing_phone'           => __( 'Billing phone', 'onsend' ),
        'billing_address'         => __( 'Billing address', 'onsend' ),
        'shipping_first_name'     => __( 'Shipping first name', 'onsend' ),
        'shipping_last_name'      => __( 'Shipping last name', 'onsend' ),
        'shipping_full_name'      => __( 'Shipping full name', 'onsend' ),
        'shipping_company'        => __( 'Shipping company name', 'onsend' ),
        'shipping_address'        => __( 'Shipping address', 'onsend' ),
        'payment_method'          => __( 'Selected payment method', 'onsend' ),
        'transaction_id'          => __( 'Transaction ID (for successful payment)', 'onsend' ),
    ) );

}

// Get the values of WooCommerce shortcodes
function onsend_wc_get_shortcodes_value( $order ) {

    $shortcodes_value = array(
        'id'                      => $order->get_id(),
        'key'                     => $order->get_order_key(),
        'checkout_url'            => $order->get_checkout_payment_url(),
        'status'                  => wc_get_order_status_name( $order->get_status() ),
        'total'                   => $order->get_formatted_order_total(),
        'discount_total'          => $order->get_discount_to_display(),
        'shipping_total'          => wc_price( $order->get_shipping_total(), array( 'currency' => $order->get_currency() ) ),
        'tax_total'               => wc_price( $order->get_total_tax(), array( 'currency' => $order->get_currency() ) ),
        'total_refunded'          => wc_price( $order->get_total_refunded(), array( 'currency' => $order->get_currency() ) ),
        'total_tax_refunded'      => wc_price( $order->get_total_tax_refunded(), array( 'currency' => $order->get_currency() ) ),
        'total_shipping_refunded' => wc_price( $order->get_total_shipping_refunded(), array( 'currency' => $order->get_currency() ) ),
        'item_count_refunded'     => $order->get_item_count_refunded(),
        'total_qty_refunded'      => $order->get_total_qty_refunded(),
        'items'                   => onsend_wc_get_formatted_order_items( $order ),
        'downloads'               => onsend_wc_get_formatted_order_downloads( $order ),
        'shipping_method'         => $order->get_shipping_to_display(),
        'date'                    => wc_format_datetime( $order->get_date_created() ),
        'completed_date'          => wc_format_datetime( $order->get_date_completed() ),
        'paid_date'               => wc_format_datetime( $order->get_date_paid() ),
        'customer_ip_address'     => $order->get_customer_ip_address(),
        'customer_note'           => $order->get_customer_note(),
        'customer_notes'          => onsend_wc_get_customer_notes( $order->get_id() ),
        'billing_first_name'      => $order->get_billing_first_name(),
        'billing_last_name'       => $order->get_billing_last_name(),
        'billing_full_name'       => $order->get_formatted_billing_full_name(),
        'billing_company'         => $order->get_billing_company(),
        'billing_email'           => $order->get_billing_email(),
        'billing_phone'           => $order->get_billing_phone(),
        'billing_address'         => onsend_wc_get_formatted_address( $order, 'billing' ),
        'shipping_first_name'     => $order->get_shipping_first_name(),
        'shipping_last_name'      => $order->get_shipping_last_name(),
        'shipping_full_name'      => $order->get_formatted_shipping_full_name(),
        'shipping_company'        => $order->get_shipping_company(),
        'shipping_address'        => onsend_wc_get_formatted_address( $order, 'shipping' ),
        'payment_method'          => $order->get_payment_method_title(),
        'transaction_id'          => $order->get_transaction_id(),
    );

    return apply_filters( 'onsend_wc_shortcodes_value', $shortcodes_value, $order );

}

// Convert shortcodes to corresponding values
function onsend_wc_do_shortcode( $content, $order ) {

    $shortcodes_value = onsend_wc_get_shortcodes_value( $order );

    return onsend_do_shortcode( $content, $shortcodes_value );

}

// Returns an HTML of WooCommerce shortcodes
function onsend_wc_get_shortcodes_html() {

    $global_shortcodes = onsend_get_shortcodes();
    $wc_shortcodes = onsend_wc_get_shortcodes();

    $shortcodes = array_merge( $global_shortcodes, $wc_shortcodes );

    return onsend_get_shortcodes_html( $shortcodes );

}

// Get the formatted shipping or billing address
function onsend_wc_get_formatted_address( $order, $address_type ) {

    if ( !in_array( $address_type, array( 'shipping', 'billing' ) ) ) {
        return;
    }

    switch ( $address_type ) {
        case 'shipping':
            $formatted_address   = $order->get_formatted_shipping_address();
            $formatted_full_name = $order->get_formatted_shipping_full_name();
            break;

        case 'billing':
            $formatted_address   = $order->get_formatted_billing_address();
            $formatted_full_name = $order->get_formatted_billing_full_name();
            break;
    }

    // Exclude the customer's name from the address
    return str_replace(
        array(
            $formatted_full_name . '<br/>',
            $formatted_full_name . ', ',
            '<br/>'
        ),
        array(
            '',
            '',
            ', '
        ),
        $formatted_address
    );

}

// Get the formatted WooCommerce order items
function onsend_wc_get_formatted_order_items( $order ) {

    $items = $order->get_items( 'line_item' );

    $i = 0;
    $formatted_items = array();

    foreach ( $items as $item_id => $item ) {
        $i++;

        $item_total = wc_price( $item->get_total(), array( 'currency' => $order->get_currency() ) );

        $formatted_items[] = sprintf(
            '%d. %s (%d) = %s',
            $i,
            $item->get_name(),
            $item->get_quantity(),
            $item_total
        );
    }

    return implode( "\n", $formatted_items );

}

// Get the formatted WooCommerce order downloads
function onsend_wc_get_formatted_order_downloads( $order ) {

    $items = $order->get_downloadable_items();

    if ( !$items ) {
        return false;
    }

    $i = 0;
    $formatted_items = array();

    foreach ( $items as $item_id => $item ) {
        $i++;
        $title = implode( ' – ', array( $item['product_name'], $item['download_name'] ) );
        $formatted_items[] = sprintf( '%d. %s [%s]', $i, $title, $item['download_url'] );
    }

    return implode( "\n\n", $formatted_items );

}

// Get all customer notes
function onsend_wc_get_customer_notes( $order_id ) {

    $order_notes = wc_get_order_notes( array(
        'order_id' => $order_id,
        'type'     => 'customer',
    ) );

    $contents = array();

    foreach ( $order_notes as $key => $order_note ) {
        if ( isset( $order_note->content ) && $order_note->content ) {
            $contents[] = ($key+1) . ') ' . $order_note->content;
        }
    }

    return implode( "\n", $contents );

}
