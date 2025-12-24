<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Compare' ) ) {
	/**
	 * Class AMOTOS_Compare
	 */
	class AMOTOS_Compare {
		/*
		 * loader instances
		 */
		private static $_instance;

		public static function getInstance()
		{
			if (self::$_instance == null) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Add Vehicle to comapre
		 */
		public function compare_add_remove_car_ajax() {
			$car_id    = isset($_POST['car_id']) ? absint(amotos_clean(wp_unslash($_POST['car_id']))) : 0;
			if ($car_id > 0) {
				$max_items      = 4;
				$this::open_session();
				$current_number = ( isset( $_SESSION['amotos_compare_cars'] ) && is_array( $_SESSION['amotos_compare_cars'] ) ) ? count(amotos_clean(wp_unslash($_SESSION['amotos_compare_cars']))  ) : 0;

				if ( is_array( $_SESSION['amotos_compare_cars'] ) && in_array( $car_id, $_SESSION['amotos_compare_cars'] ) ) {
					unset( $_SESSION['amotos_compare_cars'][ array_search( $car_id, $_SESSION['amotos_compare_cars'] ) ] );
				} elseif ( $current_number < $max_items ) {

					$_SESSION['amotos_compare_cars'][] = $car_id;
				}

				$_SESSION['amotos_compare_cars'] = array_unique( $_SESSION['amotos_compare_cars'] );

				$this->show_compare_listings();
			}
			wp_die();
		}

		/*
		 * Open new session
		 */
		public static function open_session() {
			if ( ( function_exists( 'session_status' ) && session_status() !== PHP_SESSION_ACTIVE )
			     || ! session_id() ) {
				session_start();
				if ( ! isset( $_SESSION['amotos_compare_starttime'] ) ) {
					$_SESSION['amotos_compare_starttime'] = time();
				}
				if ( ! isset( $_SESSION['amotos_compare_cars'] ) ) {
					$_SESSION['amotos_compare_cars'] = array();
				}
			}
			if ( isset( $_SESSION['amotos_compare_starttime'] ) ) {
				if ( (int) $_SESSION['amotos_compare_starttime'] > time() + 86400 ) {
					unset( $_SESSION['amotos_compare_cars'] );
				}
			}
		}

		/**
		 * output compare basket
		 */
		public function show_compare_listings() {
			$this::open_session();
			$ss_amotos_compare_cars = isset($_SESSION['amotos_compare_cars']) ? amotos_clean(wp_unslash($_SESSION['amotos_compare_cars'])) : '';
			?>
			<div id="compare-cars-listings">
				<?php if (is_array($ss_amotos_compare_cars) && count($ss_amotos_compare_cars) > 0 ): ?>
					<div class="compare-listing-body">
						<div class="compare-thumb-main row">
							<?php
							$width             = get_option( 'thumbnail_size_w' );
							$height            = get_option( 'thumbnail_size_h' );
							$no_image_src      = AMOTOS_PLUGIN_URL . 'public/assets/images/no-image.jpg';
							$default_image     = amotos_get_option( 'default_car_image', '' );
							if ( $default_image != '' ) {
								if ( is_array( $default_image ) && $default_image['url'] != '' ) {
									$resize = amotos_image_resize_url( $default_image['url'], $width, $height, true );
									if ( $resize != null && is_array( $resize ) ) {
										$no_image_src = $resize['url'];
									}
								}
							}
							foreach ( $ss_amotos_compare_cars as $key ) : ?>
								<?php if ( $key != 0 ) :
									$attach_id = get_post_thumbnail_id( (double) $key );
									$image_src = amotos_image_resize_id( $attach_id, $width, $height, true ); ?>
									<div class="compare-thumb compare-car"
									     data-car-id="<?php echo esc_attr( $key ); ?>">
										<img class="compare-car-img" width="<?php echo esc_attr( $width ) ?>"
										     height="<?php echo esc_attr( $height ) ?>"
										     src="<?php echo esc_url( $image_src ) ?>"
										     onerror="this.src = '<?php echo esc_url( $no_image_src ) ?>';">
										<button type="button" class="compare-car-remove"><i
													class="fa fa-times"></i></button>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<button type="button"
						        class="btn btn-primary btn-xs compare-cars-button"><?php esc_html_e( 'Compare', 'auto-moto-stock' ); ?></button>
					</div>
					<button type="button" class="btn btn-primary listing-btn"><i class="fa fa-angle-left"></i></button>
				<?php endif; ?>
			</div>
			<?php
		}

		/*
		 * Close session
		 * */
		public function close_session() {
			if ( isset( $_SESSION ) ) {
				session_destroy();
			}
		}

		/**
		 * Compare template
		 */
		public function template_compare_listing() {
			amotos_get_template( 'car/compare-listing.php' );
		}
	}
}