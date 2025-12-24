<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $location
 */
?>
<div id="amotos__single_dealer_map" class="amotos__map-canvas amotos__single-dealer-map" data-location="<?php echo esc_attr(wp_json_encode($location)) ?>"></div>