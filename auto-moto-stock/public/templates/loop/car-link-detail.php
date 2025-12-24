<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<div class="car-link-detail">
    <a  href="<?php the_permalink($car_id); ?>" title="<?php the_title_attribute(array('post' => $car_id)); ?>"> <span><?php esc_html_e('Details', 'auto-moto-stock'); ?></span>
        <i class="fa fa-long-arrow-right"></i></a>
</div>

