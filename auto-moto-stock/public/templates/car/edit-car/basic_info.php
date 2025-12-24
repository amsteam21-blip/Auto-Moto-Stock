<?php
/**
 * Created by StockTheme.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $car_data, $car_meta_data, $hide_car_fields;
?>
    <div class="car-fields-wrap">
        <div class="amotos-heading-style2 car-fields-title">
            <h2><?php esc_html_e('Vehicle Title', 'auto-moto-stock');
                echo esc_html(amotos_required_field('car_title') ); ?></h2>
        </div>
        <div class="car-fields car-title">
            <div class="form-group">
                <input type="text" id="car_title" class="form-control" name="car_title"
                       value="<?php echo esc_attr($car_data->post_title); ?>"/>
            </div>
        </div>
    </div>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Vehicle Type', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-type">
        <div class="row">

        <?php if (!in_array("car_type", $hide_car_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="car_type"><?php esc_html_e('Type', 'auto-moto-stock');
                        echo esc_html(amotos_required_field('car_type')); ?></label>
                    <select name="car_type[]" id="car_type" class="form-control" multiple>
                        <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-type',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_status", $hide_car_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="car_status"><?php esc_html_e('Status', 'auto-moto-stock');?></label>
                    <select  name="car_status[]" id="car_status" class="form-control" multiple>
                        <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-status',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php
        if (!in_array("car_label", $hide_car_fields)) { ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="car_label"><?php esc_html_e('Label', 'auto-moto-stock');
                        echo esc_html(amotos_required_field('car_label')); ?></label>
                    <select name="car_label[]" id="car_label" class="form-control" multiple>
                        <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-label',false,false,true); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_owners", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="car_owners"><?php echo esc_html__('Owners', 'auto-moto-stock') . esc_html(amotos_required_field('car_owners')); ?></label>
                    <input type="number" id="car_owners" class="form-control" name="car_owners"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_owners'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_owners'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_year", $hide_car_fields)) { ?>
        <div class="col-sm-4">
            <div class="form-group">
                <label
                    for="car_year"><?php echo esc_html__('Year Vehicle', 'auto-moto-stock') . esc_html(amotos_required_field('car_year')); ?></label>
                <input type="number" id="car_year" class="form-control" name="car_year"
                       value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_year'])) {
                           echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_year'][0]);
                       } ?>">
            </div>
        </div>
        <?php } ?>
        <?php if (!in_array("car_identity", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="car_identity"><?php esc_html_e('Vehicle ID', 'auto-moto-stock'); ?></label>
                    <input type="text" class="form-control" name="car_identity" id="car_identity" value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_identity'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_identity'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
</div>
<?php if (!in_array("car_des", $hide_car_fields)) { ?>
    <div class="car-fields-wrap">
        <div class="amotos-heading-style2 car-fields-title">
            <h2><?php esc_html_e('Description', 'auto-moto-stock'); ?></h2>
        </div>
        <div class="car-fields car-description">
            <?php
            $content = $car_data->post_content;
            $editor_id = 'car_des';
            $settings = array(
                'wpautop' => true,
                'media_buttons' => false,
                'textarea_name' => $editor_id,
                'textarea_rows' => get_option('default_post_edit_rows', 10),
                'tabindex' => '',
                'editor_css' => '',
                'editor_class' => '',
                'teeny' => false,
                'dfw' => false,
                'tinymce' => true,
                'quicktags' => true
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
    </div>
<?php } ?>