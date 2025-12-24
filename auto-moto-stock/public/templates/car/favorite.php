<?php
/**
 * Created by StockTheme.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$key = false;
$user_id = $current_user->ID;
$my_favorites = get_user_meta($user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', true);
$car_id= get_the_ID();
if (!empty($my_favorites)) {
	$key = array_search($car_id, $my_favorites);
}
$title_not_favorite = $title_favorited = '';
$icon_favorite = apply_filters('amotos_icon_favorite','fa fa-star') ;
$icon_not_favorite = apply_filters('amotos_icon_not_favorite','fa fa-star-o');

if ($key !== false) {
	$css_class = $icon_favorite;
	$title = esc_attr__('It is my favorite', 'auto-moto-stock');
} else {
	$css_class = $icon_not_favorite;
	$title =esc_attr__('Add to Favorite', 'auto-moto-stock');
}
?>
<a href="javascript:void(0)" class="car-favorite" data-car-id="<?php echo esc_attr(intval($car_id))  ?>"
   data-toggle="tooltip"
   title="<?php echo esc_attr($title) ?>" data-title-not-favorite="<?php esc_attr_e('Add to Favorite', 'auto-moto-stock') ?>" data-title-favorited="<?php esc_attr_e('It is my favorite', 'auto-moto-stock'); ?>" data-icon-not-favorite="<?php echo esc_attr($icon_not_favorite)?>" data-icon-favorited="<?php echo esc_attr($icon_favorite)?>"><i
			class="<?php echo esc_attr($css_class); ?>"></i></a>