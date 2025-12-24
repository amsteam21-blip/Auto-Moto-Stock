<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$number = (!empty($instance['number'])) ? absint($instance['number']) : 3;
if (!$number)
	$number = 3;

$args = array(
	'post_type'           => 'manager',
	'ignore_sticky_posts' => true,
	'post_status'         => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order'   => 'DESC',
);
$data = new WP_Query($args);
$array_manager = array();

if ($data->have_posts()):
	$amotos_car = new AMOTOS_Car();
	while ($data->have_posts()): $data->the_post();
		$manager_id = get_the_ID();
		$manager_user_id = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_user_id', true);
		$user = get_user_by('id', $manager_user_id);
		if (empty($user)) {
			$manager_user_id = 0;
		}
		$total_car = $amotos_car->get_total_cars_by_user($manager_id, $manager_user_id);
		$array_manager[] = array(
			'id' => $manager_id,
			'priority' => $total_car * -1,
			'total_car' => $total_car
		);
	endwhile;
endif;
uasort( $array_manager, 'amotos_sort_by_order_callback' );
?>
	<div class="amotos-list-top-staff-wrap">
		<div class="amotos-list-top-staff">
			<?php if (count($array_manager) > 0): ?>
				<?php
				$width = 270; $height = 340;
				$no_avatar_src= AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
				$default_avatar=amotos_get_option('default_user_avatar','');
				if($default_avatar!='')
				{
					if(is_array($default_avatar)&& $default_avatar['url']!='')
					{
						$resize = amotos_image_resize_url($default_avatar['url'], $width, $height, true);
						if ($resize != null && is_array($resize)) {
							$no_avatar_src = $resize['url'];
						}
					}
				}
				?>
				<?php $index = 1; ?>
				<?php foreach ($array_manager as $manager): ?>
					<?php
					if ($index > $number) {
						return;
					}
					$index++;
					$manager_id = $manager['id'];
					$manager_name = get_the_title($manager_id);
					$manager_link = get_the_permalink($manager_id);
					$manager_position = get_post_meta($manager_id, AMOTOS_METABOX_PREFIX . 'manager_position', true);
					$avatar_id = get_post_thumbnail_id($manager_id);
					$avatar_src = amotos_image_resize_id($avatar_id, $width, $height, true);
					$total_car = $manager['total_car'];
					?>
					<div class="manager-item">
						<div class="manager-avatar"><a title="<?php echo esc_attr($manager_name) ?>"
						                             href="<?php echo esc_url($manager_link) ?>">
								<img src="<?php echo esc_url($avatar_src) ?>"
								     onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
								     alt="<?php echo esc_attr($manager_name) ?>"
								     title="<?php echo esc_attr($manager_name) ?>"></a>
						</div>
						<div class="manager-info">
							<?php if (!empty($manager_name)): ?>
								<h4 class="manager-name"><a title="<?php echo esc_attr($manager_name) ?>"
								                          href="<?php echo esc_url($manager_link) ?>"><?php echo esc_html($manager_name) ?></a>
								</h4>
							<?php endif;
							if (!empty($manager_position)): ?>
								<span class="manager-position"><?php echo esc_html($manager_position) ?></span>
							<?php endif; ?>
							<div class="manager-total-cars">
								<?php
                                /* translators: %s: Number of vehicles. */
								echo wp_kses_post(sprintf( _n( '<span class="total-cars">%s</span> Vehicle', '<span class="total-cars">%s</span> Vehicles', $total_car, 'auto-moto-stock' ), amotos_get_format_number($total_car )));
								?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
                <?php amotos_get_template('loop/content-none.php'); ?>
			<?php endif; ?>
		</div>
	</div>

<?php
wp_reset_postdata();