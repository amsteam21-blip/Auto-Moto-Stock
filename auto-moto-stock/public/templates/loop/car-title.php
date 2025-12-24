<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<h2 class="car-title">
    <a href="<?php the_permalink($car_id); ?>" title="<?php the_title_attribute(array('post' => $car_id)); ?>"><?php echo esc_html(get_the_title($car_id))  ?></a>
</h2>
