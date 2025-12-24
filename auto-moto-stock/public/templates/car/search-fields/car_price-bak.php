<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $css_class_field
 * @var $css_class_half_field
 * @var $request_min_price
 * @var $request_max_price
 * @var $request_status
 * @var $price_is_slider
 */
if ($price_is_slider=='true'):
$min_price = amotos_get_option('car_price_slider_min',200);
$max_price = amotos_get_option('car_price_slider_max',2500000);
if ($request_status !== '') {
	$car_price_slider_search_field = amotos_get_option('car_price_slider_search_field','');
	if ($car_price_slider_search_field != '') {
		foreach ($car_price_slider_search_field as $data) {
			$term_id =(isset($data['car_price_slider_car_status']) ? $data['car_price_slider_car_status'] : '');
			$term = get_term_by('id', $term_id, 'car-status');
			if($term)
			{
				if($term->slug==$request_status)
				{
					$min_price = (isset($data['car_price_slider_min']) ? $data['car_price_slider_min'] : $min_price);
					$max_price = (isset($data['car_price_slider_max']) ? $data['car_price_slider_max'] : $max_price);
					break;
				}
			}
		}
	}
}

?>
<div class="amotos-sliderbar-price-wrap <?php echo esc_attr($css_class_field); ?> form-group">
	<?php
		$min_price_change = ($request_min_price === '') ? $min_price : $request_min_price;
		$max_price_change = ($request_max_price === '') ? $max_price : $request_max_price;
	?>
    <div class="amotos-sliderbar-price amotos-sliderbar-filter"
         data-min-default="<?php echo esc_attr($min_price) ?>"
         data-max-default="<?php echo esc_attr($max_price); ?>"
         data-min="<?php echo esc_attr($min_price_change) ?>"
         data-max="<?php echo esc_attr($max_price_change); ?>">
        <div class="title-slider-filter">
            <?php esc_html_e('Price', 'auto-moto-stock') ?> [<span
                class="min-value"><?php echo wp_kses_post(amotos_get_format_money($min_price_change))  ?></span> - <span
                class="max-value"><?php echo wp_kses_post(amotos_get_format_money($max_price_change))  ?></span>]
            <input type="hidden" name="min-price" class="min-input-request"
                   value="<?php echo esc_attr($min_price_change) ?>">
            <input type="hidden" name="max-price" class="max-input-request"
                   value="<?php echo esc_attr($max_price_change) ?>">
        </div>
        <div class="sidebar-filter">
        </div>
    </div>
</div>
<?php else:
	$car_price_dropdown_min= apply_filters('amotos_price_dropdown_min_default', amotos_get_option('car_price_dropdown_min','0,100,300,500,700,900,1100,1300,1500,1700,1900')) ;
	$car_price_dropdown_max= apply_filters('amotos_price_dropdown_max_default', amotos_get_option('car_price_dropdown_max','200,400,600,800,1000,1200,1400,1600,1800,2000')) ;
    $car_price_dropdown_search_field = amotos_get_option('car_price_dropdown_search_field','');
    if ($car_price_dropdown_search_field != '') {
        foreach ($car_price_dropdown_search_field as $data) {
            $term_id =(isset($data['car_price_dropdown_car_status']) ? $data['car_price_dropdown_car_status'] : '');
            $term = get_term_by('id', $term_id, 'car-status');
            if($term)
            {
                if($term->slug==$request_status)
                {
                    $car_price_dropdown_min = (isset($data['car_price_dropdown_min']) ? $data['car_price_dropdown_min'] : $car_price_dropdown_min);
                    $car_price_dropdown_max = (isset($data['car_price_dropdown_max']) ? $data['car_price_dropdown_max'] : $car_price_dropdown_max);
                    break;
                }
            }
        }
    }
    ?>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="min-price" title="<?php esc_attr_e('Min Price', 'auto-moto-stock') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php esc_html_e('Min Price', 'auto-moto-stock') ?>
            </option>
            <?php
            $car_price_array = explode(',', $car_price_dropdown_min);
            ?>
	        <?php if (is_array($car_price_array) && !empty($car_price_array) ): ?>
		        <?php foreach ($car_price_array as $n) : ?>
			        <option <?php selected($request_min_price,$n) ?> value="<?php echo esc_attr($n)?>">
                    <?php echo esc_html(amotos_get_format_money_search_field($n)) ?></option>
		        <?php endforeach; ?>
	        <?php endif; ?>

        </select>
    </div>
    <div class="<?php echo esc_attr($css_class_half_field); ?> form-group">
        <select name="max-price" title="<?php esc_attr_e('Max Price', 'auto-moto-stock') ?>"
                class="search-field form-control" data-default-value="">
            <option value="">
                <?php esc_html_e('Max Price', 'auto-moto-stock') ?>
            </option>
            <?php
            $car_price_array = explode(',', $car_price_dropdown_max);
            ?>
	        <?php if (is_array($car_price_array) && !empty($car_price_array) ): ?>
		        <?php foreach ($car_price_array as $n) : ?>
			        <option <?php selected($request_max_price,$n) ?> value="<?php echo esc_attr($n)?>">
                    <?php echo esc_html(amotos_get_format_money_search_field($n)) ?></option>
		        <?php endforeach; ?>
	        <?php endif; ?>
        </select>
    </div>
<?php endif; ?>