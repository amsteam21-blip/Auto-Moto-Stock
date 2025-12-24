<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $status
 */
?>
<div class="amotos__loop-my-car-badge amotos__status amotos__status-<?php echo esc_attr($status)?>">
    <?php
    switch ($status) {
        case 'publish':
            echo esc_html__('Published', 'auto-moto-stock');
            break;
        case 'expired':
            echo esc_html__('Expired', 'auto-moto-stock');
            break;
        case 'pending':
            echo esc_html__('Pending', 'auto-moto-stock');
            break;
        case 'hidden':
            echo esc_html__('Hidden', 'auto-moto-stock');
            break;
        default:
            echo esc_html($status);
    }?>
</div>
