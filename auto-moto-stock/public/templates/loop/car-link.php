<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<a class="car-link" href="<?php the_permalink($car_id); ?>" title="<?php the_title_attribute(array('post' => $car_id)); ?>"></a>
