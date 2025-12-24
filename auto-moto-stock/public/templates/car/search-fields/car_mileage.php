<?php
/**
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $mileage_is_slider
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$request_min_mileage = isset($_GET['min-mileage']) ? amotos_clean(wp_unslash($_GET['min-mileage']))  : '';
$request_max_mileage = isset($_GET['max-mileage']) ? amotos_clean(wp_unslash($_GET['max-mileage']))  : '';
$measurement_units_mileage = amotos_get_measurement_units_mileage();
?>
<?php if (filter_var($mileage_is_slider, FILTER_VALIDATE_BOOLEAN)): ?>
    <?php
    $min_mileage = amotos_get_option('car_mileage_slider_min', 0);
    $max_mileage = amotos_get_option('car_mileage_slider_max', 1000);
    if (!empty($request_min_mileage) && !empty($request_max_mileage)) {
        $min_mileage_change = $request_min_mileage;
        $max_mileage_change = $request_max_mileage;
    } else {
        $min_mileage_change = $min_mileage;
        $max_mileage_change = $max_mileage;
    }
    ?>
    <div class="<?php echo esc_attr($css_class_field); ?> form-group">
        <div class="amotos-sliderbar-mileage amotos-sliderbar-filter"
             data-min-default="<?php echo esc_attr($min_mileage) ?>"
             data-max-default="<?php echo esc_attr($max_mileage) ?>"
             data-min="<?php echo esc_attr($min_mileage_change) ?>"
             data-max="<?php echo esc_attr($max_mileage_change); ?>">
            <div class="title-slider-filter">
                <span><?php echo esc_html__('Mileage', 'auto-moto-stock') ?> [</span><span
                        class="min-value"><?php echo esc_html(amotos_get_format_number($min_mileage_change)) ?></span> - <span
                        class="max-value"><?php echo esc_html(amotos_get_format_number($max_mileage_change)) ?></span><span>]
                    <?php echo wp_kses_post($measurement_units_mileage) . '</span>'; ?>
                    <input type="hidden" name="min-mileage" class="min-input-request" value="<?php echo esc_attr($min_mileage_change) ?>">
				<input type="hidden" name="max-mileage" class="max-input-request" value="<?php echo esc_attr($max_mileage_change) ?>">
            </div>
            <div class="sidebar-filter">
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="min-mileage" title="<?php echo esc_attr__('Min Mileage', 'auto-moto-stock') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php echo esc_html__('Min Mileage', 'auto-moto-stock') ?>
            </option>
            <?php
            $car_mileage_dropdown_min = amotos_get_option('car_mileage_dropdown_min', '0,100,300,500,700,900,1100,1300,1500,1700,1900');
            $car_mileage_array = explode(',', $car_mileage_dropdown_min);
            ?>
            <?php if (is_array($car_mileage_array) && !empty($car_mileage_array) ): ?>
                <?php foreach ($car_mileage_array as $n) : ?>
                    <option <?php selected($request_min_mileage,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf( '%s %s', amotos_get_format_number($n), $measurement_units_mileage))?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="max-mileage" title="<?php echo esc_attr__('Max Mileage', 'auto-moto-stock') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php echo esc_html__('Max Mileage', 'auto-moto-stock') ?>
            </option>
            <?php
            $car_mileage_dropdown_max = amotos_get_option('car_mileage_dropdown_max', '200,400,600,800,1000,1200,1400,1600,1800,2000');
            $car_mileage_array = explode(',', $car_mileage_dropdown_max);
            ?>
            <?php if (is_array($car_mileage_array) && !empty($car_mileage_array) ): ?>
                <?php foreach ($car_mileage_array as $n) : ?>
                    <option <?php selected($request_max_mileage,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf( '%s %s', amotos_get_format_number($n), $measurement_units_mileage))?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
<?php endif; ?>