<?php
/**
 * @var $total_post
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="amotos-heading">
    <h2><?php esc_html_e('Staff', 'auto-moto-stock') ?>
        <sub>(<?php echo esc_html(amotos_get_format_number($total_post)) ; ?>)</sub></h2>
</div>