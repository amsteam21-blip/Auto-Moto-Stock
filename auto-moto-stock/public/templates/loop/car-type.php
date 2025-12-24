<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $car_id
 */
?>
<?php echo get_the_term_list($car_id,'car-type','<div class="car-type-list"><i class="fa fa-car"></i>',', ','</div>') ?>
