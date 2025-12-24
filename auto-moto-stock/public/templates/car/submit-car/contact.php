<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_car_fields;
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Contact Information', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-contact">
        <div class="row">
        <div class="col-sm-6">
            <p><?php esc_html_e('What to display in contact information box?', 'auto-moto-stock'); ?></p>
            <?php if (!in_array("author_info", $hide_car_fields)) : ?>
            <div class="form-check form-group">
                <input class="form-check-input" type="radio" name="manager_display_option" id="manager_display_option_1" value="author_info" checked>
                <label class="form-check-label" for="manager_display_option_1">
                    <?php esc_html_e('My profile information', 'auto-moto-stock'); ?>
                </label>
            </div>
            <?php endif;?>
            <?php if (!in_array("other_info", $hide_car_fields)) : ?>
            <div class="form-check form-group">
                <input class="form-check-input" type="radio" name="manager_display_option" id="manager_display_option_2" value="other_info">
                <label class="form-check-label" for="manager_display_option_2">
                    <?php esc_html_e('Other contact', 'auto-moto-stock'); ?>
                </label>
            </div>

            <div id="car_other_contact" style="display: none">
                <div class="form-group">
                    <label
                        for="car_other_contact_name"><?php esc_html_e('Name', 'auto-moto-stock'); ?></label>
                    <input type="text" id="car_other_contact_name" class="form-control" name="car_other_contact_name" value="">
                </div>
                <div class="form-group">
                    <label
                        for="car_other_contact_mail"><?php esc_html_e('Email', 'auto-moto-stock'); ?></label>
                    <input type="text" id="car_other_contact_mail" class="form-control" name="car_other_contact_mail" value="">
                </div>
                <div class="form-group">
                    <label
                        for="car_other_contact_phone"><?php esc_html_e('Phone', 'auto-moto-stock'); ?></label>
                    <input type="text" id="car_other_contact_phone" class="form-control" name="car_other_contact_phone" value="">
                </div>
                <div class="form-group">
                    <label
                        for="car_other_contact_description"><?php esc_html_e('More info', 'auto-moto-stock'); ?></label>
                    <textarea rows="3" id="car_other_contact_description" class="form-control" name="car_other_contact_description"></textarea>
                </div>
            </div>
            <?php endif;?>
        </div>
        </div>
    </div>
</div>
