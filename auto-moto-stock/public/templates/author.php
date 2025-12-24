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
?>
<div class="amotos-author-wrap amotos-manager-single-wrap">
    <div class="amotos-author amotos-manager-single">
        <?php
        /**
         * amotos_single_manager_before_summary hook.
         */
        do_action('amotos_author_before_summary');
        ?>
        <?php
        /**
         * amotos_author_summary hook.
         *
         * @hooked author_info - 5
         * @hooked author_car - 10
         */
        do_action('amotos_author_summary'); ?>
        <?php
        /**
         * amotos_author_after_summary hook.
         */
        do_action('amotos_author_after_summary');
        ?>
    </div>
</div>
<?php
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