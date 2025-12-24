<?php
/**
 * @var $cur_menu
 * @var $max_num_pages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$user_login          = $current_user->user_login;
$user_id             = $current_user->ID;
$user_custom_picture = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_custom_picture', $user_id );
$author_picture_id   = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'author_picture_id', $user_id );
$no_avatar_src       = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
$width               = get_option( 'thumbnail_size_w' );
$height              = get_option( 'thumbnail_size_h' );
$default_avatar      = amotos_get_option( 'default_user_avatar', '' );
if ( $default_avatar != '' ) {
	if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
		$resize = amotos_image_resize_url( $default_avatar['url'], $width, $height, true );
		if ( $resize != null && is_array( $resize ) ) {
			$no_avatar_src = $resize['url'];
		}
	}
}
$permalink = get_permalink();
?>
<div class="amotos-dashboard-sidebar-content">
	<div class="amotos-dashboard-welcome">
		<figure>
			<?php
			if ( ! empty( $author_picture_id ) ) {
				$author_picture_id = intval( $author_picture_id );
				if ( $author_picture_id ) {
					$avatar_src = amotos_image_resize_id( $author_picture_id, $width, $height, true );
					?>
					<img src="<?php echo esc_url( $avatar_src ); ?>"
					     onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
					     alt="<?php esc_attr_e( 'User Avatar', 'auto-moto-stock' ) ?>">
					<?php
				}
			} else {
				?>
				<img src="<?php echo esc_url( $user_custom_picture ); ?>"
				     onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
				     alt="<?php esc_attr_e( 'User Avatar', 'auto-moto-stock' ) ?>">
				<?php
			}
			?>
		</figure>
		<div class="amotos-dashboard-user-info">
			<h4 class="amotos-dashboard-title"><?php echo esc_html( $user_login ); ?></h4>
			<a class="amotos-dashboard-logout" href="<?php echo esc_url(wp_logout_url( $permalink )) ; ?>"><i class="fa fa-sign-out"></i><?php esc_html_e( 'Logout', 'auto-moto-stock' ); ?>
			</a>
		</div>
	</div>
	<nav class="navbar navbar-default" role="navigation">
		<div class="navbar-header">
			<a href="#" class="navbar-toggle" data-toggle="collapse"
			        data-target="#amotos-dashboard-sidebar-navbar-collapse">
				<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'auto-moto-stock' ); ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<span class="navbar-brand">
                <?php echo esc_html(amotos_dashboard_get_menu_title($cur_menu));?>
            </span>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="amotos-dashboard-sidebar-navbar-collapse">
            <?php $dashboard_menu = amotos_dashboard_get_menu(); ?>
			<ul class="nav navbar-nav amotos-dashboard-nav">
                <?php foreach ($dashboard_menu as $k => $v): ?>
                    <li class="<?php echo esc_attr($cur_menu == $k ? ' active' : '')  ?>">
                        <a title="<?php echo esc_attr($v['title'])?>" href="<?php echo esc_url($v['link'])?>">
                            <i class="<?php echo esc_attr($v['icon'])?>"></i> <?php echo esc_html($v['title']); ?>
                            <?php if (isset($v['count'])): ?>
                                <span class="badge"><?php echo esc_html($v['count'])?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <?php do_action( 'amotos_dashboard_navbar', $cur_menu, 'navbar_dashboard' ); ?>
			</ul>
		</div>
	</nav>
	<?php
	$paid_submission_type                = amotos_get_option( 'paid_submission_type', 'no' );
	$enable_submit_car_via_frontend = amotos_get_option( 'enable_submit_car_via_frontend', 1 );
	$user_can_submit                     = amotos_get_option( 'user_can_submit', 1 );
	$is_manager                            = amotos_is_manager();
	if ( $paid_submission_type == 'per_package' && $enable_submit_car_via_frontend == 1 && ( $is_manager || $user_can_submit == 1 ) ): ?>
        <div class="card amotos-card">
            <div class="card-header"><h5 class="card-title m-0"><?php esc_html_e( 'My Listing Package', 'auto-moto-stock' ); ?></h5></div>
            <?php amotos_get_template( 'widgets/my-package/my-package.php' ); ?>
        </div>
	<?php endif; ?>
</div>