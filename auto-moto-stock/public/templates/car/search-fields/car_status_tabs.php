<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$status_default = amotos_get_car_status_default_value();
$request_status = isset( $_GET['status'] ) ? amotos_clean( wp_unslash( $_GET['status'] ) ) : $status_default;
$car_status = amotos_get_car_status_search();
if (empty($car_status)) {
	return;
}
?>
<div class="amotos-search-status-tab">
    <div class="amotos-search-status-tab-inner">
        <input class="search-field" type='hidden' name="status" value="<?php echo esc_attr( $request_status ); ?>" data-default-value=""/>
        <?php foreach ( $car_status as $status ): ?>
            <?php
                $button_classes = ['btn-status-filter'];
                if ($request_status == $status->slug) {
                    $button_classes[] = 'active';
                }
            ?>
            <button type="button" data-value="<?php echo esc_attr( $status->slug ) ?>" class="<?php echo esc_attr(join(' ', $button_classes))?>"><?php echo esc_html( $status->name ) ?></button>
        <?php endforeach; ?>
    </div>
</div>