<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<a class="compare-car" href="javascript:void(0)"
   data-car-id="<?php the_ID() ?>" data-toggle="tooltip"
   title="<?php esc_attr_e('Compare', 'auto-moto-stock') ?>">
	<i class="fa fa-plus"></i>
</a>