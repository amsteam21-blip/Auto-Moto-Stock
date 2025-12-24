<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
?>
<div id="manager-<?php the_ID(); ?>" <?php post_class('amotos-manager-single-wrap amotos-manager-single'); ?>>
	<?php
	/**
	 * amotos_single_manager_before_summary hook.
	 */
	do_action( 'amotos_single_manager_before_summary' );
	?>
	<?php
	/**
	 * amotos_single_manager_summary hook.
	 *
	 * @hooked single_manager_info - 5
	 * @hooked comments_template - 10
	 * @hooked single_manager_reviews - 10
	 * @hooked single_manager_car - 20
	 * @hooked single_manager_other - 30
	 */
	do_action( 'amotos_single_manager_summary' ); ?>
	<?php
	/**
	 * amotos_single_manager_after_summary hook.
	 */
	do_action( 'amotos_single_manager_after_summary' );
	?>
</div>