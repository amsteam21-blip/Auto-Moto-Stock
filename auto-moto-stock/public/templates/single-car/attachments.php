<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * @var $attachments array
 */
$wrapper_classes = array(
    'single-car-element',
    'car-attachments',
    'amotos__single-car-element',
    'amotos__single-car-attachments'
);
$wrapper_class = join(' ', apply_filters('amotos_single_car_attachments_wrapper_classes',$wrapper_classes));
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <div class="amotos-heading-style2">
        <h2><?php esc_html_e('File Attachments', 'auto-moto-stock'); ?></h2>
    </div>
    <div class="amotos-car-element row">
        <?php foreach ($attachments as $attach): ?>
            <div class="col-md-4 col-sm-6 mb-3 mt-0 media amotos__car-attachment">
                <img class="mr-3" alt="<?php echo esc_attr($attach['name'])?>" src="<?php echo esc_url($attach['thumb']); ?>">
                <div class="media-body">
                    <h5 class="amotos__car-attachment-title mb-1"><?php echo esc_html($attach['name']) ?></h5>
                    <a class="amotos__car-attachment-download" target="_blank" href="<?php echo esc_url($attach['url']); ?>"><?php esc_html_e('Download', 'auto-moto-stock'); ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
