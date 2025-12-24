<?php
/**
 * @var $custom_car_image_size
 * @var $car_item_class
 * @var $car_image_class
 * @var $car_item_content_class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (!isset($car_image_class)) {
    $car_image_class = array();
}
$car_featured  = get_post_meta(get_the_ID(),AMOTOS_METABOX_PREFIX . 'car_featured' , true);
if ( $car_featured ) {
	$car_item_class[] = 'amotos-car-is-featured';
}

if ( ! isset( $car_item_content_class ) ) {
	$car_item_content_class = array();
}
$car_item_content_class[] = 'car-item-content';
?>
<div class="<?php echo esc_attr( join( ' ', $car_item_class ) ); ?>">
	<div class="car-inner">
        <?php amotos_template_loop_car_thumbnail(array(
                'extra_classes' => $car_image_class,
                'image_size' => $custom_car_image_size
        )); ?>
		<div class="<?php echo esc_attr( join( ' ', $car_item_content_class ) ); ?>">
			<div class="car-heading">
                <?php
                    /**
                     * Hook: amotos_loop_car_heading.
                     *
                     * @hooked amotos_template_loop_car_title - 5
                     * @hooked amotos_template_loop_car_price - 10
                     */
                    do_action('amotos_loop_car_heading');
                ?>
			</div>
            <?php
            /**
             * Hook: amotos_after_loop_car_heading.
             *
             * @hooked amotos_template_loop_car_location - 5
             * @hooked amotos_template_loop_car_meta - 10
             * @hooked amotos_template_loop_car_excerpt - 15
             * @hooked amotos_template_loop_car_info - 20
             */
            do_action('amotos_after_loop_car_heading');
            ?>

		</div>
	</div>
</div>