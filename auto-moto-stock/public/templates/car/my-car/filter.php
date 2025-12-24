<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$my_cars_page_link = amotos_get_permalink('my_cars');
$post_status = isset($_REQUEST['post_status']) ?  sanitize_title(wp_unslash($_REQUEST['post_status'])) : '';
$status = array(
    '' => esc_html__('All', 'auto-moto-stock'),
    'publish' => esc_html__('Approved', 'auto-moto-stock'),
    'pending' => esc_html__('Pending', 'auto-moto-stock'),
    'expired' => esc_html__('Expired', 'auto-moto-stock'),
    'hidden' => esc_html__('Hidden', 'auto-moto-stock'),
);
?>
<ul class="amotos__my-car-filter amotos-my-cars-filter">
    <?php foreach ($status as $k => $v): ?>
        <?php
            $item_classes = array($k);
            if ($post_status === $k) {
                $item_classes[] = 'active';
            }
            $item_class = join(' ', $item_classes);
            $item_link = remove_query_arg(array('new_id', 'edit_id'), add_query_arg(array('post_status' => $k), $my_cars_page_link));
            if (empty($k)) {
                $item_link = $my_cars_page_link;
            }
            $item_count = AMOTOS_Car::getInstance()->get_total_my_cars($k);
        ?>
        <li class="amotos__my-car-filter-<?php echo esc_attr($item_class)?>">
            <a href="<?php echo esc_url($item_link)?>" title="<?php echo esc_attr($v)?>"><?php echo esc_html(sprintf('%s (%d)',$v,$item_count)) ?></a>
        </li>
    <?php endforeach; ?>
</ul>
