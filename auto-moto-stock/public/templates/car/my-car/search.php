<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$car_status = isset($_REQUEST['car_status']) ? amotos_clean( wp_unslash( $_REQUEST['car_status'] ) ) : '';
$car_identity = isset($_REQUEST['car_identity']) ?  amotos_clean( wp_unslash( $_REQUEST['car_identity'] ) ) : '';
$title = isset($_REQUEST['title']) ?  amotos_clean( wp_unslash( $_REQUEST['title'] ) ) : '';
$post_status = isset($_REQUEST['post_status']) ?  sanitize_title(wp_unslash($_REQUEST['post_status'])) : '';
?>
<form class="amotos-my-cars-search amotos__my-car-search" action="<?php echo esc_url(get_page_link()) ; ?>">
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label class="sr-only" for="car_status"><?php echo esc_html__('Status', 'auto-moto-stock'); ?></label>
                <select name="car_status" id="car_status" class="form-control" title="<?php echo esc_attr__('Status', 'auto-moto-stock') ?>">
                    <?php amotos_get_car_status_search_slug($car_status); ?>
                    <option value="" <?php selected('', $car_status); ?>>
                        <?php echo esc_html__('All Status', 'auto-moto-stock') ?>
                    </option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label class="sr-only" for="car_identity"><?php echo esc_html__('Vehicle ID', 'auto-moto-stock'); ?></label>
                <input type="text" name="car_identity" id="car_identity"
                       value="<?php echo esc_attr($car_identity); ?>"
                       class="form-control"
                       placeholder="<?php echo esc_attr__('Vehicle ID', 'auto-moto-stock'); ?>">
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <label class="sr-only" for="title"><?php echo esc_html__('Title', 'auto-moto-stock'); ?></label>
                <input type="text" name="title" id="title"
                       value="<?php echo esc_attr($title); ?>"
                       class="form-control"
                       placeholder="<?php echo esc_attr__('Title', 'auto-moto-stock'); ?>">
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="form-group">
                <button type="submit" id="search_car" class="btn btn-primary"><?php echo esc_html__('Search', 'auto-moto-stock')?></button>
            </div>
        </div>
        <?php if (!empty($post_status)): ?>
            <input type="hidden" name="post_status" value="<?php echo esc_attr($post_status); ?>"/>
        <?php endif; ?>
    </div>
</form>

