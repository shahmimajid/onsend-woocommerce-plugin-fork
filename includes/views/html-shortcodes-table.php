<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<table class="onsend-shortcodes-table wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th><strong><?php esc_html_e( 'Shortcode', 'onsend' ); ?></strong></th>
            <th><strong><?php esc_html_e( 'Description', 'onsend' ); ?></strong></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $shortcodes as $shortcode => $description ) : ?>
            <tr>
                <td><?php echo esc_html( '{' . $shortcode . '}' ); ?></td>
                <td><?php echo esc_html( $description ); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
