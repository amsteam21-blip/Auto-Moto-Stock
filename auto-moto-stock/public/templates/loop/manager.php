<?php
/**
 * @var $sf_item_wrap
 * @var $manager_layout_style
 * @var $custom_manager_image_size
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$manager_id   = get_the_ID();
$manager_name = get_the_title();
$manager_link = get_the_permalink();

$manager_post_meta_data = get_post_custom( $manager_id );

$manager_description = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_description' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_description' ][0] : '';
$email             = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_email' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_email' ][0] : '';

$manager_facebook_url  = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_facebook_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_facebook_url' ][0] : '';
$manager_twitter_url   = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_twitter_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_twitter_url' ][0] : '';
$manager_linkedin_url  = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_linkedin_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_linkedin_url' ][0] : '';
$manager_pinterest_url = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_pinterest_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_pinterest_url' ][0] : '';
$manager_instagram_url = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_instagram_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_instagram_url' ][0] : '';
$manager_skype         = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_skype' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_skype' ][0] : '';
$manager_youtube_url   = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_youtube_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_youtube_url' ][0] : '';
$manager_vimeo_url     = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_vimeo_url' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_vimeo_url' ][0] : '';
$manager_user_id       = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_user_id' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_user_id' ][0] : '';
$user                = get_user_by( 'id', $manager_user_id );
if ( empty( $user ) ) {
	$manager_user_id = 0;
}
$amotos_car   = new AMOTOS_Car();
$avatar_id      = get_post_thumbnail_id( $manager_id );
$width          = 270;
$height         = 340;
$no_avatar_src  = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
$default_avatar = amotos_get_option( 'default_user_avatar', '' );

if ( preg_match( '/\d+x\d+/', $custom_manager_image_size ) ) {
	$image_sizes = explode( 'x', $custom_manager_image_size );
	$width       = $image_sizes[0];
	$height      = $image_sizes[1];
	$avatar_src  = amotos_image_resize_id( $avatar_id, $width, $height, true );
	if ( $default_avatar != '' ) {
		if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
			$resize = amotos_image_resize_url( $default_avatar['url'], $width, $height, true );
			if ( $resize != null && is_array( $resize ) ) {
				$no_avatar_src = $resize['url'];
			}
		}
	}
} else {
	if ( ! in_array( $custom_manager_image_size, array( 'full', 'thumbnail' ) ) ) {
		$custom_manager_image_size = 'full';
	}
	$avatar_src = wp_get_attachment_image_src( $avatar_id, $custom_manager_image_size );
	if ( $avatar_src && ! empty( $avatar_src[0] ) ) {
		$avatar_src = $avatar_src[0];
	}
	if ( ! empty( $avatar_src ) ) {
		list( $width, $height ) = getimagesize( $avatar_src );
	}
	if ( $default_avatar != '' ) {
		if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
			$no_avatar_src = $default_avatar['url'];
		}
	}
}
?>
<div class="manager-item <?php echo esc_attr( $sf_item_wrap ) ?>">
	<div class="manager-item-inner">
		<div class="manager-avatar">
			<a
					title="<?php echo esc_attr( $manager_name ) ?>"
					href="<?php echo esc_url( $manager_link ) ?>"><img width="<?php echo esc_attr( $width ) ?>"
			                                                         height="<?php echo esc_attr( $height ) ?>"
			                                                         src="<?php echo esc_url( $avatar_src ) ?>"
			                                                         onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
			                                                         alt="<?php echo esc_attr( $manager_name ) ?>"
			                                                         title="<?php echo esc_attr( $manager_name ) ?>"></a>
		</div>
		<div class="manager-content">
			<div class="manager-info">
				<?php if ( ! empty( $manager_name ) ): ?>
					<h2 class="manager-name"><a
								title="<?php echo esc_attr( $manager_name ) ?>"
								href="<?php echo esc_url( $manager_link ) ?>"><?php echo esc_attr( $manager_name ) ?></a>
					</h2>
				<?php endif; ?>
				<span class="manager-total-cars"><?php
					$total_car = $amotos_car->get_total_cars_by_user( $manager_id, $manager_user_id );
                    /* translators: %s: Number of Vehicle. */
					echo esc_html(sprintf( _n( '%s Vehicle', '%s Vehicles', $total_car, 'auto-moto-stock' ), amotos_get_format_number( $total_car ) ));
					?></span>
				<?php if ( ! empty( $manager_description ) ): ?>
					<p class="manager-description"><?php echo wp_kses_post( $manager_description ) ?></p>
				<?php endif; ?>
			</div>
			<div class="manager-social">
				<?php if ( ! empty( $manager_facebook_url ) ): ?>
					<a title="Facebook" href="<?php echo esc_url( $manager_facebook_url ); ?>">
						<i class="fa fa-facebook"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_twitter_url ) ): ?>
					<a title="Twitter" href="<?php echo esc_url( $manager_twitter_url ); ?>">
						<i class="fa fa-twitter"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $email ) ): ?>
					<a title="Email" href="mailto:<?php echo esc_attr( $email ); ?>">
						<i class="fa fa-envelope"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_skype ) ): ?>
					<a title="Skype" href="skype:<?php echo esc_url( $manager_skype ); ?>?call">
						<i class="fa fa-skype"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_linkedin_url ) ): ?>
					<a title="Linkedin" href="<?php echo esc_url( $manager_linkedin_url ); ?>">
						<i class="fa fa-linkedin"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_pinterest_url ) ): ?>
					<a title="Pinterest" href="<?php echo esc_url( $manager_pinterest_url ); ?>">
						<i class="fa fa-pinterest"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_instagram_url ) ): ?>
					<a title="Instagram" href="<?php echo esc_url( $manager_instagram_url ); ?>">
						<i class="fa fa-instagram"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_youtube_url ) ): ?>
					<a title="Youtube" href="<?php echo esc_url( $manager_youtube_url ); ?>">
						<i class="fa fa-youtube-play"></i>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $manager_vimeo_url ) ): ?>
					<a title="Vimeo" href="<?php echo esc_url( $manager_vimeo_url ); ?>">
						<i class="fa fa-vimeo"></i>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>