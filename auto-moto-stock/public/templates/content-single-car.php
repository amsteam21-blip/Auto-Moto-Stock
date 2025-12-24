<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
?>
<div id="car-<?php the_ID(); ?>" <?php post_class('amotos-car-wrap single-car-area content-single-car amotos__single-car'); ?>>
	<?php
	/**
	 * amotos_single_car_before_summary hook.
	 */
	do_action( 'amotos_single_car_before_summary' );
	?>
	<?php
	/**
	* amotos_single_car_summary hook.
	*
	* @hooked amotos_template_single_car_header - 5
	* @hooked amotos_template_single_car_gallery - 10
	* @hooked amotos_template_single_car_description - 15
	* @hooked amotos_template_single_car_address - 20
	* @hooked amotos_template_single_car_stylings - 25
	* @hooked amotos_template_single_car_attachments - 35
	* @hooked amotos_template_single_car_map - 40
	* @hooked amotos_template_single_car_nearby_places - 45
	* @hooked amotos_template_single_car_walk_score - 50
	* @hooked amotos_template_single_car_contact_manager - 55
	* @hooked amotos_template_single_car_footer - 90
	* @hooked amotos_template_single_car_reviews - 95
	*/
	do_action( 'amotos_single_car_summary' ); ?>
	<?php
	/**
	 * amotos_single_car_after_summary hook.
	 *
	 * * @hooked comments_template - 90
	 */
	do_action( 'amotos_single_car_after_summary' );
	?>
</div>