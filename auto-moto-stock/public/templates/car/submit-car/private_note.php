<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Private Note', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-private-note">
        <div class="form-group">
            <label for="private_note"><?php esc_html_e('Write a private note for this Vehicle, it will not be displayed to public', 'auto-moto-stock'); ?></label>
            <textarea
                name="private_note"
                rows="4"
                id="private_note"
                class="form-control"></textarea>
        </div>
    </div>
</div>