<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $hide_car_fields;
$location_dropdowns = amotos_get_option('location_dropdowns', 1);
$default_country = amotos_get_option('default_country', 'US');
$default_city = amotos_get_option('default_city', '');
$googlemap_coordinate_default = amotos_get_option('googlemap_coordinate_default', '37.773972, -122.431297');
list( $lat, $long ) =  (!empty($googlemap_coordinate_default) && strpos($googlemap_coordinate_default,',')) ? explode( ',', $googlemap_coordinate_default ) : array('', '');
?>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e('Location', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="car-fields car-location">
        <div class="row">
        <?php if (!in_array("car_map_address", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label
                        for="geocomplete"><?php echo esc_html__('Address', 'auto-moto-stock') . esc_html(amotos_required_field('car_map_address')); ?></label>
                    <input type="text" class="form-control" name="car_map_address" id="geocomplete"
                           value=""
                           placeholder="<?php esc_attr_e('Enter Vehicle address', 'auto-moto-stock'); ?>">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("country", $hide_car_fields)) { ?>
            <div class="col-sm-4 submit_country_field">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="country"><?php echo esc_html__('Country', 'auto-moto-stock') . esc_html(amotos_required_field('country')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="car_country" id="country" class="amotos-car-country-ajax form-control">
                            <?php
                            $countries = amotos_get_selected_countries();
                            foreach ($countries as $key => $country):
                                echo '<option ' . selected($default_country, $key, false) . ' value="' . esc_attr($key)  . '">' . esc_html($country)  . '</option>';
                            endforeach;
                            ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="country" id="country">
                        <input name="country_short" type="hidden" value="">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("state", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="administrative_area_level_1"><?php echo  esc_html__('Province/State', 'auto-moto-stock') . esc_html(amotos_required_field('state')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="administrative_area_level_1" id="administrative_area_level_1" class="amotos-car-state-ajax form-control">
                            <?php amotos_get_taxonomy('car-state', true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="administrative_area_level_1" id="administrative_area_level_1">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("city", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="city"><?php echo esc_html__('City/Town', 'auto-moto-stock') . esc_html(amotos_required_field('city')); ?></label>
                    <?php if ($location_dropdowns == 1) {?>
                        <select name="locality" id="city" class="amotos-car-city-ajax form-control">
                            <?php amotos_get_taxonomy_slug('car-city',$default_city); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="locality" id="city">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("neighborhood", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group amotos-loading-ajax-wrap">
                    <label for="neighborhood"><?php echo esc_html__('Neighborhood', 'auto-moto-stock') . esc_html(amotos_required_field('neighborhood')); ?></label>
                    <?php if ($location_dropdowns == 1) { ?>
                        <select name="neighborhood" id="neighborhood" class="amotos-car-neighborhood-ajax form-control">
                            <?php amotos_get_taxonomy('car-neighborhood', true); ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" class="form-control" name="neighborhood" id="neighborhood">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("postal_code", $hide_car_fields)) { ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="zip"><?php echo  esc_html__('Postal Code/Zip', 'auto-moto-stock') . esc_html(amotos_required_field('postal_code')); ?></label>
                    <input type="text" class="form-control" name="postal_code" id="zip">
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
</div>
<div class="car-fields-wrap">
    <div class="amotos-heading-style2 car-fields-title">
        <h2><?php esc_html_e('Google Map Location', 'auto-moto-stock'); ?></h2>
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
                <input type="text" class="form-control" name="lat" id="latitude" value="<?php echo esc_attr($lat); ?>">
            </div>
            <div class="form-group">
                <label for="longitude"><?php esc_html_e('Google Maps longitude', 'auto-moto-stock'); ?></label>
                <input type="text" class="form-control" name="lng" id="longitude" value="<?php echo esc_attr($long); ?>">
            </div>
            <div class="form-group">
                <input id="find" type="button" class="btn btn-primary btn-block" title="<?php esc_attr_e('Place the pin the address above', 'auto-moto-stock'); ?>"
                       value="<?php esc_attr_e('Pin address', 'auto-moto-stock'); ?>">
                <a id="reset" href="#"
                   style="display:none;"><?php esc_html_e('Reset Marker', 'auto-moto-stock'); ?></a>
            </div>
        </div>
        </div>
    </div>
</div>