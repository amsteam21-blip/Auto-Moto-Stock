<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$paid_submission_type = amotos_get_option( 'paid_submission_type', 'no' );
$allow_submit         = amotos_allow_submit();

if ( ( ! $allow_submit ) || ( $paid_submission_type != 'per_package' ) ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_permission' ) );

	return;
}
?>
<div class="amotos-package-wrap">
	<div class="amotos-heading">
		<h2><?php esc_html_e( 'Listing Packages', 'auto-moto-stock' ) ?></h2>
		<p><?php esc_html_e( 'Please select a listing package', 'auto-moto-stock' ) ?></p>
	</div>
	<div class="row">
		<?php
		$args          = array(
			'post_type'      => 'package',
			'posts_per_page' => - 1,
			'orderby'        => 'meta_value_num',
			'meta_key'       => AMOTOS_METABOX_PREFIX . 'package_order_display',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => AMOTOS_METABOX_PREFIX . 'package_visible',
					'value'   => '1',
					'compare' => '=',
				)
			)
		);
		$data          = new WP_Query( $args );
		$total_records = $data->found_posts;
		if ( $total_records == 4 ) {
			$css_class = 'col-md-3 col-sm-6';
		} else if ( $total_records == 3 ) {
			$css_class = 'col-md-4 col-sm-6';
		} else if ( $total_records == 2 ) {
			$css_class = 'col-md-4 col-sm-6';
		} else if ( $total_records == 1 ) {
			$css_class = 'col-md-4 col-sm-12';
		} else {
			$css_class = 'col-md-3 col-sm-6';
		}
		while ( $data->have_posts() ): $data->the_post();
			$package_id             = get_the_ID();
			$package_time_unit      = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_time_unit', true );
			$package_period         = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_period', true );
			$package_num_cars = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true );
			$package_free           = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_free', true );
			if ( $package_free == 1 ) {
				$package_price = 0;
			} else {
				$package_price = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_price', true );
			}
			$package_unlimited_listing     = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_listing', true );
			$package_unlimited_time        = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_unlimited_time', true );
			$package_num_featured_listings = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', true );
			$package_featured              = get_post_meta( $package_id, AMOTOS_METABOX_PREFIX . 'package_featured', true );

			if ( $package_period > 1 ) {
				$package_time_unit .= 's';
			}
			if ( $package_featured == 1 ) {
				$is_featured = ' active';
			} else {
				$is_featured = '';
			}
			$payment_link         = amotos_get_permalink( 'payment' );
			$payment_process_link = add_query_arg( 'package_id', $package_id, $payment_link );
			?>
			<div class="<?php echo esc_attr( $css_class ); ?>">
				<div class="amotos-package-item card amotos-card <?php echo esc_attr( $is_featured ); ?>">
                    <div class="card-header"><h5 class="card-title m-0"><?php the_title(); ?></h5></div>
					<ul class="list-group p-0">
						<li class="list-group-item d-flex justify-content-center align-items-center">
							<h2 class="amotos-package-price">
								<?php
								if ( $package_price > 0 ) {
									echo wp_kses_post(amotos_get_format_money( $package_price, '', 0, true )) ;
								} else {
									esc_html_e( 'Free', 'auto-moto-stock' );
								}
								?>
							</h2>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php esc_html_e( 'Expiration Date', 'auto-moto-stock' ); ?>
                            <span class="badge">
                                <?php if ( $package_unlimited_time == 1 ) {
	                                esc_html_e( 'Never Expires', 'auto-moto-stock' );
                                } else {
	                                echo esc_html( $package_period ) . ' ' . esc_html(AMOTOS_Package::get_time_unit( $package_time_unit ));
                                }
                                ?>
                            </span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php esc_html_e( 'Vehicle Listing', 'auto-moto-stock' ); ?>
                            <span class="badge">
                                    <?php if ( $package_unlimited_listing == 1 ) {
	                                    esc_html_e( 'Unlimited', 'auto-moto-stock' );
                                    } else {
	                                    echo esc_html( $package_num_cars );
                                    } ?>
                                </span>

                        </li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php esc_html_e( 'Featured Listings', 'auto-moto-stock' ) ?>
                            <span class="badge"><?php echo esc_html( $package_num_featured_listings ); ?></span>
						</li>
						<li class="list-group-item d-flex justify-content-center align-items-center amotos-package-choose">
							<a href="<?php echo esc_url( $payment_process_link ); ?>"
							   class="btn btn-primary"><?php esc_html_e( 'Choose', 'auto-moto-stock' ); ?></a>
						</li>
					</ul>
				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>