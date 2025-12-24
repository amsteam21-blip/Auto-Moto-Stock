<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$request_status = isset($_GET['status']) ? amotos_clean(wp_unslash($_GET['status']))  : '';
if (is_tax('car-status')) {
    $_term = get_query_var('term');
    if (!empty($_term)) {
        $request_status = $_term;
    }
}
$current_url = sanitize_url(wp_unslash($_SERVER['REQUEST_URI']));
$car_status = amotos_get_car_status_search();
if (empty($car_status)) {
    return;
}

$all_link = remove_query_arg( 'status', $current_url );
if (is_tax('car-status')) {
    $all_link = get_post_type_archive_link('car');
}
?>
<div class="amotos__apa-item amotos__apa-status">
    <ul>
        <li class="<?php echo ($request_status === '' ? 'active' : '')?>">
            <a title="<?php echo esc_attr__('All','auto-moto-stock')?>" data-status="all" href="<?php echo esc_url($all_link)?>"><?php echo esc_html__('All','auto-moto-stock')?></a>
        </li>
        <?php foreach ($car_status as $status): ?>
            <?php $link = add_query_arg( 'status', $status->slug, $current_url );
            if (is_tax('car-status')) {
                $link = get_term_link($status);
            }
            ?>
            <li class="<?php echo esc_attr($request_status === $status->slug ? 'active' : '')?>">
                <a title="<?php echo esc_attr($status->name)?>" data-status="<?php echo esc_attr($status->slug)?>" href="<?php echo esc_url($link)?>"><?php echo esc_html($status->name)?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
