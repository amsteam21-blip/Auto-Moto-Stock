<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $volume_is_slider
 */
$request_min_volume = isset($_GET['min-volume']) ? amotos_clean(wp_unslash($_GET['min-volume']))  : '';
$request_max_volume = isset($_GET['max-volume']) ? amotos_clean(wp_unslash($_GET['max-volume']))  : '';
$measurement_units_volume=amotos_get_measurement_units_volume();
?>
<?php if (filter_var($volume_is_slider, FILTER_VALIDATE_BOOLEAN)): ?>
    <?php
    $min_volume = amotos_get_option('car_volume_slider_min',0);
    $max_volume = amotos_get_option('car_volume_slider_max',1000);
    if (!empty($request_min_volume) && !empty($request_max_volume)) {
        $min_volume_change = $request_min_volume;
        $max_volume_change = $request_max_volume;
    } else {
        $min_volume_change = $min_volume;
        $max_volume_change = $max_volume;
    }
    ?>
<div class="<?php echo esc_attr($css_class_field); ?> form-group">
    <div class="amotos-sliderbar-volume amotos-sliderbar-filter"
         data-min-default="<?php echo esc_attr($min_volume) ?>"
         data-max-default="<?php echo esc_attr($max_volume) ?>"
         data-min="<?php echo esc_attr($min_volume_change); ?>"
         data-max="<?php echo esc_attr($max_volume_change); ?>">
        <div class="title-slider-filter">
            <span><?php echo esc_html__('Cubic Capacity', 'auto-moto-stock') ?> [</span><span
                    class="min-value"><?php echo esc_html(amotos_get_format_number($min_volume_change))  ?></span>
            - <span
                    class="max-value"><?php echo esc_html(amotos_get_format_number($max_volume_change))  ?></span><span>]
            <?php echo wp_kses_post($measurement_units_volume).'</span>'; ?>
            <input type="hidden" name="min-volume" class="min-input-request" value="<?php echo esc_attr($min_volume_change) ?>">
            <input type="hidden" name="max-volume" class="max-input-request" value="<?php echo esc_attr($max_volume_change) ?>">
        </div>
        <div class="sidebar-filter">
        </div>
    </div>
</div>

<?php else: ?>
<div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
    <select name="min-volume" title="<?php echo esc_attr__('Min Cubic Capacity', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php echo esc_html__('Min Cubic Capacity', 'auto-moto-stock') ?>
        </option>
        <?php
        $car_volume_dropdown_min = amotos_get_option('car_volume_dropdown_min', '0,100,300,500,700,900,1100,1300,1500,1700,1900');
        $car_volume_array = explode(',', $car_volume_dropdown_min);
        ?>
        <?php if (is_array($car_volume_array) && !empty($car_volume_array) ): ?>
            <?php foreach ($car_volume_array as $n) : ?>
                <option <?php selected($request_min_volume,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf('%s %s', amotos_get_format_number($n), $measurement_units_volume)) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
<div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
    <select name="max-volume" title="<?php echo esc_attr__('Max Cubic Capacity', 'auto-moto-stock') ?>"
            class="search-field form-control" data-default-value="">
        <option value="">
            <?php echo esc_html__('Max Cubic Capacity', 'auto-moto-stock') ?>
        </option>
        <?php
        $car_volume_dropdown_max = amotos_get_option('car_volume_dropdown_max', '200,400,600,800,1000,1200,1400,1600,1800,2000');
        $car_volume_array = explode(',', $car_volume_dropdown_max);
        ?>
        <?php if (is_array($car_volume_array) && !empty($car_volume_array) ): ?>
            <?php foreach ($car_volume_array as $n) : ?>
                <option <?php selected($request_max_volume,$n) ?> value="<?php echo esc_attr($n)?>"><?php echo wp_kses_post(sprintf('%s %s', amotos_get_format_number($n), $measurement_units_volume)) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
<?php endif; ?>