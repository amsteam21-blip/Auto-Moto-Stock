<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $power_is_slider
 */
$request_min_power = isset($_GET['min-power']) ? amotos_clean(wp_unslash($_GET['min-power']))  : '';
$request_max_power = isset($_GET['max-power']) ? amotos_clean(wp_unslash($_GET['max-power']))  : '';
$measurement_units_power=amotos_get_measurement_units_power();
?>
<?php if (filter_var($power_is_slider, FILTER_VALIDATE_BOOLEAN)): ?>
    <?php
    $min_power = amotos_get_option('car_power_slider_min',0);
    $max_power = amotos_get_option('car_power_slider_max',1000);
    if (!empty($request_min_power) && !empty($request_max_power)) {
        $min_power_change = $request_min_power;
        $max_power_change = $request_max_power;
    } else {
        $min_power_change = $min_power;
        $max_power_change = $max_power;
    }
    ?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <div class="amotos-sliderbar-power amotos-sliderbar-filter"
         data-min-default="<?php echo esc_attr($min_power) ?>"
         data-max-default="<?php echo esc_attr($max_power) ?>"
         data-min="<?php echo esc_attr($min_power_change); ?>"
         data-max="<?php echo esc_attr($max_power_change); ?>">
        <div class="title-slider-filter">
            <span><?php echo esc_html__('Power', 'auto-moto-stock') ?> [</span><span
                    class="min-value"><?php echo esc_html(amotos_get_format_number($min_power_change))  ?></span>
            - <span
                    class="max-value"><?php echo esc_html(amotos_get_format_number($max_power_change))  ?></span><span>]
            <?php echo wp_kses_post($measurement_units_power).'</span>'; ?>
            <input type="hidden" name="min-power" class="min-input-request" value="<?php echo esc_attr($min_power_change) ?>">
            <input type="hidden" name="max-power" class="max-input-request" value="<?php echo esc_attr($max_power_change) ?>">
        </div>
        <div class="sidebar-filter">
        </div>
    </div>
</div>

<?php else: ?>
<div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
    <select name="min-power" title="<?php echo esc_attr__('Min Power', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php echo esc_html__('Min Power', 'auto-moto-stock') ?>
        </option>
        <?php
        $car_power_dropdown_min = amotos_get_option('car_power_dropdown_min', '0,100,300,500,700,900,1100,1300,1500,1700,1900');
        $car_power_array = explode(',', $car_power_dropdown_min);
        ?>
        <?php if (is_array($car_power_array) && !empty($car_power_array) ): ?>
            <?php foreach ($car_power_array as $n) : ?>
                <option <?php selected($request_min_power,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf('%s %s', amotos_get_format_number($n), $measurement_units_power)) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
<div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
    <select name="max-power" title="<?php echo esc_attr__('Max Power', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php echo esc_html__('Max Power', 'auto-moto-stock') ?>
        </option>
        <?php
        $car_power_dropdown_max = amotos_get_option('car_power_dropdown_max', '200,400,600,800,1000,1200,1400,1600,1800,2000');
        $car_power_array = explode(',', $car_power_dropdown_max);
        ?>
        <?php if (is_array($car_power_array) && !empty($car_power_array) ): ?>
            <?php foreach ($car_power_array as $n) : ?>
                <option <?php selected($request_max_power,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf('%s %s', amotos_get_format_number($n), $measurement_units_power)) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
<?php endif; ?>