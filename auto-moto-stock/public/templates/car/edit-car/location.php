<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $car_meta_data, $car_data,$hide_car_fields;
$location_dropdowns = amotos_get_option('location_dropdowns',1);
$car_location = get_post_meta( $car_data->ID, AMOTOS_METABOX_PREFIX . 'car_location', true );
$car_map_address = isset($car_location['address']) ? $car_location['address'] : '';
list( $lat, $long ) =  isset($car_location['location']) && !empty($car_location['location']) ? explode( ',', $car_location['location'] ) : array('', '');
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Location', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-location">
        <div class="row">

        <?php if (!in_array("car_map_address", $hide_car_fields)) {?>
        <div class="col-sm-4">
            <div class="form-group">
                <label
                    for="geocomplete"><?php echo esc_html__('Address', 'auto-moto-stock') . esc_html(amotos_required_field('car_map_address')); ?></label>
                <input type="text" class="form-control" name="car_map_address" id="geocomplete"
                       value="<?php echo esc_attr($car_map_address); ?>"
                       placeholder="<?php esc_attr_e('Enter Vehicle address', 'auto-moto-stock'); ?>">
            </div>
        </div>
        <?php } ?>
        <?php if (!in_array("country", $hide_car_fields)) {?>
            <div class="col-sm-4 submit_country_field">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="country"><?php echo esc_html__('Country', 'auto-moto-stock') . esc_html(amotos_required_field('country')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="car_country" id="country" class="amotos-car-country-ajax form-control">
                            <?php
                            $countries = amotos_get_selected_countries();
                            foreach ($countries as $key => $country):
                                echo '<option ' . selected($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_country'][0], $key, false) . ' value="' . esc_attr($key)  . '">' . esc_html($country)  . '</option>';
                            endforeach;
                            ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="country"
                               value="<?php echo esc_attr(amotos_get_country_by_code($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_country'][0])) ; ?>"
                               id="country">
                        <input name="country_short" type="hidden"
                               value="<?php echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_country'][0]); ?>">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("state", $hide_car_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="state"><?php echo esc_html__('Province/State', 'auto-moto-stock') . esc_html(amotos_required_field('state')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="car_state" id="state" class="amotos-car-state-ajax form-control" data-selected="<?php echo esc_attr(amotos_get_taxonomy_slug_by_post_id($car_data->ID, 'car-state')) ; ?>">
                            <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-state',true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control"
                               value="<?php echo esc_attr(amotos_get_taxonomy_name_by_post_id($car_data->ID, 'car-state')); ?>"
                               name="administrative_area_level_1" id="state">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("city", $hide_car_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="city"><?php echo esc_html__('City/Town', 'auto-moto-stock') . esc_html(amotos_required_field('city')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="car_city" id="city" class="amotos-car-city-ajax form-control" data-selected="<?php echo esc_attr(amotos_get_taxonomy_slug_by_post_id($car_data->ID, 'car-city')) ; ?>">
                            <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-city',true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control"
                               value="<?php echo esc_attr(amotos_get_taxonomy_name_by_post_id($car_data->ID, 'car-city')); ?>"
                               name="locality" id="city">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("neighborhood", $hide_car_fields)) {?>
        <div class="col-sm-4">
            <div class="form-group amotos-loading-ajax-wrap">
                <label for="neighborhood"><?php echo esc_html__('Neighborhood', 'auto-moto-stock') . esc_html(amotos_required_field('neighborhood')); ?></label>
                <?php if ($location_dropdowns == 1) { ?>
                    <select name="car_neighborhood" id="neighborhood" class="amotos-car-neighborhood-ajax form-control" data-selected="<?php echo esc_attr(amotos_get_taxonomy_slug_by_post_id($car_data->ID, 'car-neighborhood')) ; ?>">
                        <?php amotos_get_taxonomy_by_post_id($car_data->ID, 'car-neighborhood',true); ?>
                    </select>
                <?php } else { ?>
                    <input type="text" class="form-control" name="neighborhood"
                           value="<?php echo esc_attr(amotos_get_taxonomy_name_by_post_id($car_data->ID, /*'car_area'*/'car-neighborhood')); ?>"
                           id="neighborhood">
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if (!in_array("postal_code", $hide_car_fields)) {?>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="zip"><?php echo esc_html__('Postal Code/Zip', 'auto-moto-stock') . esc_html(amotos_required_field('postal_code')); ?></label>
                <input type="text" class="form-control" name="postal_code"
                       value="<?php if (isset($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_zip'][0])) {
                           echo esc_attr($car_meta_data[AMOTOS_METABOX_PREFIX . 'car_zip'][0]);
                       } ?>" id="zip">
            </div>
        </div>
        <?php } ?>
        </div>
    </div>
</div>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e( 'Google Map Location', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="car-fields car-location">
        <div class="row">

        <div class="col-sm-9">
            <div class="map_canvas" id="map" style="height: 300px">
            </div>
        </div>
        <div class="col-sm-3 xs-mg-top-30">
            <div class="form-group">
                <label for="latitude"><?php esc_html_e('Google Maps latitude', 'auto-moto-stock'); ?></label>
                <input type="text" class="form-control" name="lat" id="latitude"
                       value="<?php echo esc_attr($lat); ?>">
            </div>
            <div class="form-group">
                <label for="longitude"><?php esc_html_e('Google Maps longitude', 'auto-moto-stock'); ?></label>
                <input type="text" class="form-control" name="lng" id="longitude"
                       value="<?php echo esc_attr($long); ?>">
            </div>
            <div class="form-group">
                <input id="find" type="button" class="btn btn-primary btn-block" title="<?php esc_attr_e('Place the pin the address above', 'auto-moto-stock'); ?>" value="<?php esc_attr_e('Pin address', 'auto-moto-stock'); ?>">
                <a id="reset" href="#"
                   style="display:none;"><?php esc_html_e('Reset Marker', 'auto-moto-stock'); ?></a>
            </div>
        </div>
        </div>
    </div>
</div>