<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_item_status WP_Term[]
 * @var $extra_class
 */
$wrapper_classes = array(
      'amotos__loop-car-status'
);

if (isset($extra_class)) {
    $wrapper_classes[] = $extra_class;
}

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <?php foreach ($car_item_status as $status): ?>
        <?php
            $status_color = get_term_meta($status->term_id, 'car_status_color', true);
            $status_attributes = array();
            if (!empty($status_color)) {
                $status_attributes['style'] = sprintf('--amotos-loop-car-status-bg-color:%s', esc_attr($status_color));
            }
            $status_classes = array(
              'amotos__loop-car-status-item',
              $status->slug
            );
            $status_class = join(' ', $status_classes);
            $status_attributes['class'] = $status_class;

        ?>
        <span <?php amotos_render_html_attr($status_attributes); ?>>
            <?php echo esc_html($status->name)?>
        </span>
    <?php endforeach; ?>
</div>
