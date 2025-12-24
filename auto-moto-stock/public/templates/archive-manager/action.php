<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="archive-manager-action amotos__archive-actions amotos__archive-manager-actions">
    <?php
    /**
     * Hook: amotos_archive_manager_actions.
     *
     * @hooked amotos_template_archive_manager_action_search - 5
     * @hooked amotos_template_archive_manager_action_orderby - 10
     * @hooked amotos_template_archive_manager_action_switch_layout - 15
     */
    do_action('amotos_archive_manager_actions');
    ?>
</div>

