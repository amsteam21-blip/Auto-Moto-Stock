<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_term_label WP_Term[]
 */
?>
<?php foreach ($car_term_label as $item): ?>
    <?php
        $label_color = get_term_meta( $item->term_id, 'car_label_color', true );
        $label_attributes = array();
        if (!empty($label_color)) {
            $label_attributes['style'] = sprintf('--amotos-loop-car-badge-bg-color:%s', esc_attr($label_color));
        }
        $label_classes = array(
            'amotos__loop-car-badge-item',
            'amotos__term-label',
            $item->slug
        );
        $label_class = join(' ', $label_classes);
        $label_attributes['class'] = $label_class;
    ?>
    <span <?php amotos_render_html_attr($label_attributes); ?>>
        <span class="amotos__lpbi-inner">
            <?php echo esc_html($item->name)?>
        </span>
    </span>
<?php endforeach; ?>
