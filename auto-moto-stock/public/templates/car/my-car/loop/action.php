<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $actions
 * @var $car_id
 */
$my_cars_page_link = amotos_get_permalink('my_cars');
?>
<?php do_action('amotos_my_car_before_actions',$car_id) ?>
<ul class="amotos__loop-my-car-action">
    <?php foreach ($actions as $k => $v): ?>
        <?php
        $action_url = add_query_arg(array('action' => $k, 'car_id' => $car_id), $my_cars_page_link);
        if ($v['nonce']) {
            $action_url = wp_nonce_url($action_url, 'amotos_my_cars_actions');
        }
        $item_attributes = array();
        $item_attributes['href'] = $action_url;
        if (!empty($v['confirm'])) {
            $item_attributes['onclick'] = sprintf("return confirm('%s')",$v['confirm']);
        }
        $item_attributes['data-toggle'] = "tooltip";
        $item_attributes['data-placement'] = "bottom";
        $item_attributes['title'] = $v['tooltip'];
        $item_attributes['class'] = sprintf("amotos__btn-dashboard-action amotos__btn-dashboard-action-%s",$k);
        ?>
        <li class="amotos__loop-my-car-action-<?php echo esc_attr($k)?>">
            <a <?php amotos_render_html_attr($item_attributes) ?>><?php echo esc_html($v['label'])?></a>
        </li>
    <?php endforeach; ?>
</ul>
