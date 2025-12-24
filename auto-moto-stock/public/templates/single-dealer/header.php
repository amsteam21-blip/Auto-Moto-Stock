<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $dealer
 */
$wrapper_classes = array(
  'amotos__single-dealer-element',
  'amotos__single-dealer-header',
);

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php
    /**
     * Hook: amotos_before_single_dealer_summary.
     *
     * @hooked amotos_template_single_dealer_image - 5
     */
    do_action('amotos_before_single_dealer_summary',$dealer);
    ?>
    <div class="amotos__summary">
        <?php
        /**
         * Hook: amotos_single_dealer_summary.
         *
         * @hooked amotos_template_single_dealer_title - 5
         * @hooked amotos_template_single_dealer_address - 10
         * @hooked amotos_template_single_dealer_meta - 15
         * @hooked amotos_template_single_dealer_contact_info -20
         * @hooked amotos_template_single_dealer_social - 25
         */
        do_action('amotos_single_dealer_summary',$dealer);
        ?>
    </div>
    <?php
    /**
     * Hook: amotos_after_single_dealer_summary.
     *
     * @hooked amotos_template_single_dealer_contact_form - 5
     */
    do_action('amotos_after_single_dealer_summary',$dealer);
    ?>
</div>

