<?php
/**
 * @var $taxonomy_name
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$current_url = sanitize_url($_SERVER['REQUEST_URI']);
?>
<div class="archive-car-action amotos__archive-actions amotos__archive-car-actions">
    <?php
    /**
     * Hook: amotos_archive_car_actions.
     *
     * @hooked amotos_template_archive_car_action_status - 5
     * @hooked amotos_template_archive_car_action_orderby - 10
     * @hooked amotos_template_archive_car_action_switch_layout - 15
     */
    do_action('amotos_archive_car_actions');
    ?>
</div>
