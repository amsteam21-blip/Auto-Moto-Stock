<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $data array
 */
$wrapper_classes = array(
    'single-car-element',
    'car-overview',
    'amotos__single-car-element',
    'amotos__single-car-overview'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_overview_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php echo esc_html__( 'Overview', 'auto-moto-stock' ); ?></h2>
    </div>
    <div class="amotos-car-element">
        <ul class="amotos__list-2-col amotos__list-bg-gray">
            <?php foreach ($data as $k => $v): ?>
                <li>
                    <strong><?php echo wp_kses_post($v['title'])?></strong>
                    <?php echo wp_kses_post($v['content'])?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


