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
do_action( 'amotos_taxonomy_dealer_before_main_content' );
$dealer        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
?>
<div class="amotos-dealer-single-wrap amotos__single-dealer-wrap">
    <?php
    /**
     * amotos_taxonomy_dealer_before_summary hook.
     */
    do_action( 'amotos_taxonomy_dealer_before_summary',$dealer );
    ?>
    <?php
    /**
     * Hook: amotos_taxonomy_dealer_summary.
     *
     * @hooked amotos_template_single_dealer_header - 5
     */
    do_action( 'amotos_taxonomy_dealer_summary', $dealer ); ?>
    <?php
    /**
     * Hook: amotos_taxonomy_dealer_after_summary.
     *
     * @hooked amotos_template_single_dealer_tabs - 5
     * @hooked amotos_template_single_dealer_manager - 10
     */
    do_action( 'amotos_taxonomy_dealer_after_summary',$dealer );
    ?>
</div>
<?php
do_action( 'amotos_taxonomy_dealer_after_main_content' );
/**
 * amotos_after_main_content hook.
 *
 * @hooked amotos_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'amotos_after_main_content' );
/**
 * amotos_sidebar_manager hook.
 *
 * @hooked amotos_sidebar_manager - 10
 */
do_action('amotos_sidebar_manager');
get_footer('amotos');