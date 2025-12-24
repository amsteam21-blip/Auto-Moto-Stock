<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Shortcode attributes
 * @var $atts
 */
$car_type = $type_image = $image_size = $el_class = '';
extract( shortcode_atts( array(
	'car_type' => '',
	'type_image' => '',
	'image_size' => 'full',
	'el_class' => ''
), $atts ) );

$car_item_class = array();

$wrapper_classes = array(
	'amotos-car-type',
	$el_class
);
$car_type = get_term_by( 'slug', $car_type, 'car-type', 'OBJECT' );
if(! is_a($car_type,'WP_Term')) {
    return;
}

$type_name = $car_type->name;
$type_count = $car_type->count;

$image_src = '';
$width = '';
$height = '';

if(!empty( $type_image )) {
	if ( preg_match( '/\d+x\d+/', $image_size ) ) {
		$image_size = explode( 'x', $image_size );
		$image_src  = amotos_image_resize_id( $type_image, $image_size[0], $image_size[1], true );
	} else {
		if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
			$image_size = 'full';
		}
		$image_src = wp_get_attachment_image_src( $type_image, $image_size );
		if ( $image_src && ! empty( $image_src ) ) {
			$image_src = $image_src[0];
		}
	}
	if(!empty( $image_src )) {
		list( $width, $height ) = getimagesize( $image_src );
	}
}
?>
<div class="<?php echo esc_attr(join(' ', $wrapper_classes))  ?>">
	<div class="car-type-inner">
		<div class="car-type-image">
			<?php if (!empty($type_image)):?>
				<a href="<?php echo esc_url( get_term_link( $car_type, 'car-type' ) ); ?>" title="<?php echo esc_attr( $type_name ) ?>">
					<img width="<?php echo esc_attr( $width )?>" height="<?php echo esc_attr( $height )?>"
				     src="<?php echo esc_url($image_src) ?>" alt="<?php echo esc_attr( $type_name ) ?>"
				     title="<?php echo esc_attr( $type_name ) ?>">
				</a>
			<?php endif;?>
		</div>
		<div class="car-type-info">
			<div class="car-title">
				<a href="<?php echo esc_url( get_term_link( $car_type, 'car-type' ) ); ?>" title="<?php echo esc_attr( $type_name ) ?>">
					<?php echo esc_html( $type_name ); ?>
				</a>
			</div>
			<div class="car-count"><span><?php echo esc_attr( $type_count ); ?></span>
                <?php echo esc_html__('Vehicles','auto-moto-stock'); ?>
            </div>
		</div>
	</div>
</div>
