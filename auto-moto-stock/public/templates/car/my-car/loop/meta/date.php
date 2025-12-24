<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$wrapper_classes = array(
    'amotos__loop-my-car-meta-item',
    'amotos__loop-car-meta-date'
);

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>">
    <i class="fa fa-calendar"></i><span> <?php echo get_the_date(get_option('date_format')); ?></span>
</div>

