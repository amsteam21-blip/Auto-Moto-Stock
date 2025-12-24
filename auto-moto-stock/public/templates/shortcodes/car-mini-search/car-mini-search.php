<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * @var $atts
 */
$status_enable = $el_class ='';
extract(shortcode_atts(array(
    'status_enable' => 'true',
    'el_class' => '',
), $atts));
$wrapper_classes = array(
    'amotos-mini-search-cars',
    $el_class,
);
$advanced_search = amotos_get_permalink('advanced_search');
$wrapper_class = join(' ', apply_filters('amotos_sc_mini_search_car_wrapper_classes',$wrapper_classes) );
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div data-href="<?php echo esc_url($advanced_search) ?>" class="amotos-mini-search-cars-form form-search-wrap">
        <?php
            if (filter_var($status_enable, FILTER_VALIDATE_BOOLEAN)) {
                amotos_get_template('car/search-fields/car_status.php', array(
                    'css_class_field' => 'status'
                ));
            }
            amotos_get_template('car/search-fields/keyword.php', array(
                'css_class_field' => 'keyword'
            ));
        ?>
        <button type="button" id="mini-search-btn"><i class="fa fa-search"></i></button>
    </div>
</div>