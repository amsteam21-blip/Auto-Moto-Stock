<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
$status = get_post_status($car_id);
?>
<h4 class="amotos__loop-my-car-title">
    <?php if ($status === 'publish'): ?>
        <a target="_blank" href="<?php the_permalink($car_id); ?>" title="<?php the_title_attribute(array('post' => $car_id)); ?>"><?php echo esc_html(get_the_title($car_id))  ?></a>
    <?php else: ?>
        <?php echo esc_html(get_the_title($car_id))  ?>
    <?php endif; ?>
</h4>
