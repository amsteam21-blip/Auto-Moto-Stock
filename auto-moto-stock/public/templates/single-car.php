<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header('amotos');
/**
 * amotos_before_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_start - 10 (outputs opening divs for the content)
 */
do_action( 'amotos_before_main_content' );
do_action('amotos_single_car_before_main_content');
if (have_posts()):
    while (have_posts()): the_post(); ?>
        <?php amotos_get_template_part('content', 'single-car'); ?>
    <?php endwhile;
endif;
do_action('amotos_single_car_after_main_content');
/**
 * amotos_after_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'amotos_after_main_content' );
/**
 * amotos_sidebar_car hook.
 *
 * @hooked amotos_sidebar_car - 10
 */

do_action('amotos_sidebar_car');
get_footer('amotos');
