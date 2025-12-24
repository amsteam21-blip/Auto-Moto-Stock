<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$wrapper_classes = array(
    'single-car-element',
    'car-description',
    'amotos__single-car-element',
    'amotos__single-car-description'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_description_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Description', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <?php the_content(); ?>
    </div>
</div>
