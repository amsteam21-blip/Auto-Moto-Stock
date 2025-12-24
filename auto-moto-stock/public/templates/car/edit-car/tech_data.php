<?php
/**
 * Created by StockTheme.

 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $car_data, $car_meta_data, $hide_car_fields;
$auto_car_id = amotos_get_option('auto_car_id',0);
$measurement_units_mileage = amotos_get_measurement_units_mileage();
$measurement_units_power = amotos_get_measurement_units_power();
$measurement_units_volume = amotos_get_measurement_units_volume();
$additional_stylings = absint(get_post_meta($car_data->ID, AMOTOS_METABOX_PREFIX . 'additional_stylings', true));
$additional_styling_title = get_post_meta($car_data->ID, AMOTOS_METABOX_PREFIX . 'additional_styling_title', true);
$additional_styling_value = get_post_meta($car_data->ID, AMOTOS_METABOX_PREFIX . 'additional_styling_value', true);
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Technical Data', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-detail">
        <div class="row">

        <?php if (!in_array("car_mileage", $hide_car_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="car_mileage"><?php /* translators: %1$s: measurement units; %2$s: string required field (*). */ echo wp_kses_post(sprintf(__('Mileage (%1$s) %2$s', 'auto-moto-stock'),$measurement_units_mileage, amotos_required_field('car_mileage'))); ?></label>
                    <input type="number" id="car_mileage" class="form-control" name="car_mileage"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_mileage'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_mileage'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_power", $hide_car_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="car_power"><?php /* translators: %1$s: measurement units; %2$s: string required field (*). */  echo wp_kses_post(sprintf(esc_html__('Power (%1$s) %2$s', 'auto-moto-stock'),$measurement_units_power, amotos_required_field('car_power'))); ?></label>
                    <input type="number" id="car_power" class="form-control" name="car_power"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_power'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_power'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
        
        <?php if (!in_array("car_volume", $hide_car_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="car_volume"><?php /* translators: %1$s: measurement units; %2$s: string required field (*). */  echo wp_kses_post(sprintf(esc_html__('Cubic Capacity (%1$s) %2$s', 'auto-moto-stock'),$measurement_units_volume, amotos_required_field('car_volume'))); ?></label>
                    <input type="number" id="car_volume" class="form-control" name="car_volume"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_volume'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_volume'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_doors", $hide_car_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label
                        for="car_doors"><?php echo esc_html__('Doors', 'auto-moto-stock') . esc_html(amotos_required_field('car_doors')); ?></label>
                    <input type="number" id="car_doors" class="form-control" name="car_doors"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_doors'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_doors'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("car_seats", $hide_car_fields)) {?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label
                        for="car_seats"><?php echo esc_html__('Seats', 'auto-moto-stock') . esc_html(amotos_required_field('car_seats')); ?></label>
                    <input type="number" id="car_seats" class="form-control" name="car_seats"
                           value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_seats'])) {
                               echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_seats'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php
        $additional_fields = amotos_render_additional_fields();
        if(count($additional_fields)>0) {
	        $required_fields = amotos_get_option('required_fields', array('car_title', 'car_type', 'car_price', 'car_map_address'));
            foreach ($additional_fields as $key => $field) {
	            $field_attributes = array();
	            if (is_array($required_fields) && in_array($field['id'], $required_fields)) {
		            $field_attributes['data-toggle'] = 'amotos-car-additional-field-required';
	            }
                switch ($field['type']) {
                    case 'text':
                        ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label
                                    for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . amotos_required_field($field['id'])); ?></label>
                                <input <?php amotos_render_html_attr($field_attributes); ?> type="text" id="<?php echo esc_attr($field['id']); ?>" class="form-control"
                                       name="<?php echo esc_attr($field['id']); ?>"
                                       value="<?php if (isset($car_meta_data[$field['id']])) {
                                           echo esc_attr($car_meta_data[$field['id']][0]);
                                       } ?>">
                            </div>
                        </div>
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . amotos_required_field($field['id'])); ?></label>
                                <textarea <?php amotos_render_html_attr($field_attributes); ?>
                                    name="<?php echo esc_attr($field['id']); ?>"
                                    rows="3"
                                    id="<?php echo esc_attr($field['id']); ?>"
                                    class="form-control"><?php if (isset($car_meta_data[$field['id']])) {
                                        echo esc_attr($car_meta_data[$field['id']][0]);
                                    } ?></textarea>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'select':
                        ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title'] . amotos_required_field($field['id'])); ?></label>
                                <select <?php amotos_render_html_attr($field_attributes); ?> name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>"
                                        class="form-control">
                                    <?php
                                    foreach ($field['options'] as $opt_value):?>
                                        <option value="<?php echo esc_attr($opt_value); ?>" <?php if( isset( $car_meta_data[$field['id']] ) &&  $car_meta_data[$field['id']][0]==$opt_value) { echo 'selected';} ?>><?php echo esc_html($opt_value); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'checkbox_list':
	                    $field_attributes['data-name'] = $field['id'].'[]';
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo esc_html($field['title'] . amotos_required_field($field['id'])); ?></label>
                                <div <?php amotos_render_html_attr($field_attributes); ?> class="amotos-field-<?php echo esc_attr($field['id']); ?>">
                                <?php
                                $car_field= get_post_meta($car_data->ID, $field['id'], true);
                                if(empty($car_field))
                                {
                                    $car_field=array();
                                }
                                foreach ($field['options'] as $opt_value):
                                    if ( in_array( $opt_value, $car_field ) ):?>
                                        <label class="checkbox-inline"><input type="checkbox"
                                                                              name="<?php echo esc_attr($field['id']); ?>[]"
                                                                              value="<?php echo esc_attr($opt_value); ?>" checked><?php echo esc_html($opt_value); ?>
                                        </label>
                                    <?php else:?>
                                        <label class="checkbox-inline"><input type="checkbox"
                                                                              name="<?php echo esc_attr($field['id']); ?>[]"
                                                                              value="<?php echo esc_attr($opt_value); ?>"><?php echo esc_html($opt_value); ?>
                                        </label>
                                    <?php endif;
                                endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;
                    case 'radio':
	                    $field_attributes['data-name'] = $field['id'];
                        ?>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo esc_html($field['title'] . amotos_required_field($field['id'])); ?></label>
                                <div <?php amotos_render_html_attr($field_attributes); ?> class="amotos-field-<?php echo esc_attr($field['id']); ?>">
                                <?php
                                foreach ($field['options'] as $opt_value):?>
                                    <label class="radio-inline"><input type="radio" name="<?php echo esc_attr($field['id']); ?>"
                                                                       value="<?php echo esc_attr($opt_value); ?>" <?php if( isset( $car_meta_data[$field['id']] ) &&  $car_meta_data[$field['id']][0]==$opt_value) { echo 'checked';} ?>><?php echo esc_html($opt_value); ?>
                                    </label>
                                <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;
                }
            }
        }
        ?>
        </div>
        <?php if (!in_array("additional_details", $hide_car_fields)) { ?>
            <div class="add-tab-row">
                <h4><?php esc_html_e('Additional Fields', 'auto-moto-stock'); ?></h4>
                <table class="additional-block">
                    <thead>
                    <tr>
                        <td class="amotos-column-action"></td>
                        <td><label><?php esc_html_e('Title', 'auto-moto-stock'); ?></label></td>
                        <td><label><?php esc_html_e('Value', 'auto-moto-stock'); ?></label></td>
                        <td class="amotos-column-action"></td>
                    </tr>
                    </thead>
                    <tbody id="amotos_additional_details">
                    <?php
                    if (!empty($additional_stylings)) {
                        for ($i = 0; $i < $additional_stylings; $i++) { ?>
                            <tr>
                                <td>
                                    <span class="sort-additional-row"><i class="fa fa-navicon"></i></span>
                                </td>
                                <td>
                                    <input class="form-control" type="text"
                                           name="additional_styling_title[<?php echo esc_attr($i); ?>]"
                                           id="additional_styling_title_<?php echo esc_attr($i); ?>"
                                           value="<?php echo esc_attr($additional_styling_title[$i]); ?>">
                                </td>
                                <td>
                                    <input class="form-control" type="text"
                                           name="additional_styling_value[<?php echo esc_attr($i); ?>]"
                                           id="additional_styling_value_<?php echo esc_attr($i); ?>"
                                           value="<?php echo esc_attr($additional_styling_value[$i]); ?>">
                                </td>

                                <td>
                                    <span data-remove="<?php echo esc_attr($i); ?>" class="remove-additional-styling"><i
                                                class="fa fa-remove"></i></span>
                                </td>
                            </tr>
                        <?php }; ?>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="3">
                            <button type="button" data-increment="<?php echo esc_attr($additional_stylings - 1); ?>"
                                    class="add-additional-styling"><i
                                        class="fa fa-plus"></i> <?php esc_html_e('Add New', 'auto-moto-stock'); ?></button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        <?php } ?>
    </div>
</div>