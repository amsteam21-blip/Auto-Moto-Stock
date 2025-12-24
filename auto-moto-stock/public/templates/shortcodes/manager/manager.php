<?php
/**
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$dealer = $layout_style = $item_amount = $items = $image_size = $show_paging = $dots = $nav = $nav_position = $autoplay = $autoplaytimeout = $loop =
$items_md = $items_sm = $items_xs = $items_mb = $paged = $post_not_in = $el_class = '';

extract( shortcode_atts( array(
	'dealer'          => '',
	'layout_style'    => 'manager-slider',
	'item_amount'     => '12',
	'items'           => '4',
	'image_size'      => '270x340',
	'show_paging'     => '',
	'dots'            => '',
	'nav'             => 'true',
	'nav_position'    => 'center',
	'autoplay'        => 'true',
	'autoplaytimeout' => '1000',
	'loop'            => '',
	'items_md'        => '3',
	'items_sm'        => '2',
	'items_xs'        => '2',
	'items_mb'        => '1',
	'post_not_in'     => '',
	'el_class'        => '',
	'paged'           => '1'
), $atts ) );

$wrapper_attributes = array();
$wrapper_styles     = array();

$wrapper_classes = array(
	'amotos-manager',
	$layout_style,
	$el_class
);

$sf_item_wrap = '';

if ( $layout_style == 'manager-slider' ) {
	$wrapper_classes[] = 'owl-carousel amotos__owl-carousel';
	$show_paging       = 'false';

	if ( $nav ) {
		$wrapper_classes[] = 'owl-nav-' . $nav_position;
	}

	$owl_attributes       = array(
		'dots' => (bool) $dots,
		'nav' => (bool) $nav,
		'autoplay' => (bool) $autoplay,
		'autoplayTimeout' => ($autoplaytimeout ? (int) $autoplaytimeout  : 1000),
		'loop' => (bool) $loop,
		'responsive' => array(
			'0' => array(
				'items' => (int)$items_mb,
				'margin' => 0
			),
			'481' => array(
				'items' => (int)$items_xs,
				'margin' => 30
			),
			'768' => array(
				'items' => (int)$items_sm,
				'margin' => 30
			),
			'992' => array(
				'items' => (int)$items_md,
				'margin' => 30
			),
			'1200' => array(
				'items' => (int)$items,
				'margin' => 30
			),
        )
	);

	$wrapper_attributes['data-plugin-options'] = $owl_attributes;
}
if ( $layout_style == 'manager-grid' ) {
	$sf_item_wrap      = 'amotos-item-wrap';
	$wrapper_classes[] = 'row columns-' . $items . ' columns-md-' . $items_md . ' columns-sm-' . $items_sm . ' columns-xs-' . $items_xs . ' columns-mb-' . $items_mb . '';
}
$posts_per_page = $item_amount ? $item_amount : - 1;

$args = array(
	'post_type'      => 'manager',
	'paged'          => $paged,
	'posts_per_page' => $posts_per_page,
	'orderby'        => array(
		'menu_order' => 'ASC',
		'date'       => 'DESC',
	),
	'post_status'    => 'publish',
	'post__not_in'   => array( $post_not_in )
);

if ( $dealer != '' ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'dealer',
			'field'    => 'slug',
			'terms'    => explode( ',', $dealer ),
			'operator' => 'IN'
		)
	);
}
$args = apply_filters('amotos_shortcodes_manager_query_args',$args);
$data = new WP_Query( $args );
wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'manager');

?>
	<div class="amotos-manager-wrap">
		<?php if ( $data->have_posts() ): ?>
			<div class="<?php echo esc_attr(join( ' ', $wrapper_classes ))  ?>" <?php amotos_render_html_attr($wrapper_attributes); ?>>
				<?php
				$no_avatar_src  = AMOTOS_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
				$default_avatar = amotos_get_option( 'default_user_avatar', '' );
				if ( preg_match( '/\d+x\d+/', $image_size ) ) {
					$image_sizes = explode( 'x', $image_size );
					$width       = $image_sizes[0];
					$height      = $image_sizes[1];
					if ( $default_avatar != '' ) {
						if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
							$resize = amotos_image_resize_url( $default_avatar['url'], $width, $height, true );
							if ( $resize != null && is_array( $resize ) ) {
								$no_avatar_src = $resize['url'];
							}
						}
					}
				} else {
					if ( $default_avatar != '' ) {
						if ( is_array( $default_avatar ) && $default_avatar['url'] != '' ) {
							$no_avatar_src = $default_avatar['url'];
						}
					}
				}
				while ( $data->have_posts() ): $data->the_post();
					$manager_id   = get_the_ID();
					$manager_name = get_the_title();
					$manager_link = get_the_permalink();

					$manager_post_meta_data = get_post_custom( $manager_id );

					$manager_position      = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_position' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_position' ][0] : '';
					$manager_description   = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_description' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_description' ][0] : '';
					$email               = isset( $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_email' ] ) ? $manager_post_meta_data[ AMOTOS_METABOX_PREFIX . 'manager_email' ][0] : '';
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
					$avatar_id  = get_post_thumbnail_id( $manager_id );
					$avatar_src = $default_avatar_src = '';
					$item_class = '';
					$width      = 270;
					$height     = 340;
					if ( preg_match( '/\d+x\d+/', $image_size ) ) {
						$image_sizes = explode( 'x', $image_size );
						$width       = $image_sizes[0];
						$height      = $image_sizes[1];
						$avatar_src  = amotos_image_resize_id( $avatar_id, $width, $height, true );
					} else {
						if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
							$image_size = 'full';
						}
						$avatar_src = wp_get_attachment_image_src( $avatar_id, $image_size );
						if ( $avatar_src && ! empty( $avatar_src[0] ) ) {
							$avatar_src = $avatar_src[0];
						}
						if ( ! empty( $avatar_src ) ) {
							list( $width, $height ) = getimagesize( $avatar_src );
						}
					}
					?>
					<div class="manager-item <?php echo esc_attr( $sf_item_wrap ) ?>">
						<div class="manager-item-inner">
							<div class="manager-avatar">
								<a title="<?php echo esc_attr( $manager_name ) ?>"
								   href="<?php echo esc_url( $manager_link ) ?>"><img
											width="<?php echo esc_attr( $width ) ?>"
											height="<?php echo esc_attr( $height ) ?>"
											onerror="this.src = '<?php echo esc_url( $no_avatar_src ) ?>';"
											src="<?php echo esc_url( $avatar_src ) ?>"
											alt="<?php echo esc_attr( $manager_name ) ?>"
											title="<?php echo esc_attr( $manager_name ) ?>"></a>
							</div>
							<div class="manager-content">
								<div class="manager-info">
									<?php if ( ! empty( $manager_name ) ): ?>
										<h2 class="manager-name"><a title="<?php echo esc_attr( $manager_name ) ?>"
										                          href="<?php echo esc_url( $manager_link ) ?>"><?php echo esc_html( $manager_name ) ?></a>
										</h2>
									<?php endif; ?>
									<span class="amotos__manager-count"><?php
										$amotos_car   = new AMOTOS_Car();
										$total_car = $amotos_car->get_total_cars_by_user( $manager_id, $manager_user_id );
                                        /* translators: %s: Number of Vehicle Manager. */
										echo esc_html(sprintf( _n( '%s Vehicle', '%s Vehicles', $total_car, 'auto-moto-stock' ), amotos_get_format_number( $total_car ) )) ;
										?></span>
									<?php if ( ! empty( $manager_description ) && ( $layout_style == 'manager-list' ) ): ?>
										<p class="amotos__manager-excerpt"><?php echo wp_kses_post( $manager_description ) ?></p>
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
				<?php
				endwhile;

				if ( $show_paging == 'true' ) {
					?>

				<?php } ?>
			</div>
            <?php if ($show_paging == 'true'):  ?>
                <div class="manager-paging-wrap"
                     data-admin-url="<?php echo esc_url(wp_nonce_url( AMOTOS_AJAX_URL, 'amotos_manager_paging_ajax_action', 'amotos_manager_paging_ajax_nonce' ))   ?>"
                     data-layout="<?php echo esc_attr( $layout_style ); ?>"
                     data-item-amount="<?php echo esc_attr( $item_amount ); ?>"
                     data-image-size="<?php echo esc_attr( $image_size ); ?>"
                     data-items="<?php echo esc_attr( $items ); ?>"
                     data-show-paging="<?php echo esc_attr( $show_paging ); ?>"
                     data-post-not-in="<?php echo esc_attr( $post_not_in ); ?>">
                    <?php $max_num_pages = $data->max_num_pages;
                    set_query_var( 'paged', $paged );
                    amotos_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
                    ?>
                </div>
            <?php endif; ?>
		<?php else: ?>
            <?php amotos_get_template('loop/content-none.php'); ?>
		<?php endif; ?>
	</div>
<?php
wp_reset_postdata();