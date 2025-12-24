<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_item_status WP_Term[]
 */
$wrapper_classes = array('amotos__loop-car-badge','amotos__lpb-status');
$wrapper_class = implode(' ', apply_filters('amotos_loop_car_badge_term_status',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php foreach ($car_item_status as $status): ?>
        <?php
            $status_color = get_term_meta($status->term_id, 'car_status_color', true);
            $status_attributes = array();
            if (!empty($status_color)) {
                $status_attributes['style'] = sprintf('--amotos-loop-car-badge-bg-color:%s', esc_attr($status_color));
            }
            $status_classes = array(
              'amotos__loop-car-badge-item',
              'amotos__term-status',
              $status->slug
            );
            $status_class = join(' ', $status_classes);
            $status_attributes['class'] = $status_class;

        ?>
        <span <?php amotos_render_html_attr($status_attributes); ?>>
            <span class="amotos__lpbi-inner">
                <?php echo esc_html($status->name)?>
            </span>
        </span>
    <?php endforeach; ?>
</div>
