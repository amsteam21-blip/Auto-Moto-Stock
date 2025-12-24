<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_image_360
 * @var $car_virtual_360
 * @var $car_virtual_360_type
 */
$wrapper_classes = array(
    'single-car-element',
    'car-virtual-360',
    'amotos__single-car-element',
    'amotos__single-car-virtual-360'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_virtual_360_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Virtual 360', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <?php if (!empty($car_image_360) && $car_virtual_360_type == '0') : ?>
            <iframe width="100%" height="600" scrolling="no" allowfullscreen
                    src="<?php echo esc_url(AMOTOS_PLUGIN_URL . "public/assets/packages/vr-view/index.html?image=" . $car_image_360) ; ?>"></iframe>
        <?php  elseif (!empty($car_virtual_360) && $car_virtual_360_type == '1'): ?>
            <?php echo(!empty($car_virtual_360) ? do_shortcode($car_virtual_360) : '') ?>
        <?php endif; ?>
    </div>
</div>

