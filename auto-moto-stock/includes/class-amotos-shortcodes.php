<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AMOTOS_Shortcodes class.
 */
class AMOTOS_Shortcodes {

	/*
		 * loader instances
		 */
	private static $_instance;

	public static function getInstance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	private static $amotos_message;

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'amotos_login'               => __CLASS__ . '::login',
			'amotos_register'            => __CLASS__ . '::register',
			'amotos_profile'             => __CLASS__ . '::profile',
			'amotos_reset_password'      => __CLASS__ . '::reset_password',
			'amotos_package'             => __CLASS__ . '::package',
			'amotos_my_invoices'         => __CLASS__ . '::my_invoices',
			'amotos_payment'             => __CLASS__ . '::payment',
			'amotos_payment_completed'   => __CLASS__ . '::payment_completed',
			'amotos_my_cars'             => __CLASS__ . '::my_cars',
			'amotos_submit_car'          => __CLASS__ . '::submit_car',
			'amotos_my_favorites'        => __CLASS__ . '::my_favorites',
			'amotos_advanced_search'     => __CLASS__ . '::advanced_search',
			'amotos_my_save_search'      => __CLASS__ . '::my_save_search',
			'amotos_compare'             => __CLASS__ . '::compare',
			/////////////////////////////////////////////////////////
			'amotos_car'                 => __CLASS__ . '::car',
			'amotos_car_carousel'        => __CLASS__ . '::car_carousel',
			'amotos_car_slider'          => __CLASS__ . '::car_slider',
			'amotos_car_gallery'         => __CLASS__ . '::car_gallery',
			'amotos_car_featured'        => __CLASS__ . '::car_featured',
			'amotos_car_type'            => __CLASS__ . '::car_type',
			'amotos_car_search'          => __CLASS__ . '::car_search',
			'amotos_car_search_map'      => __CLASS__ . '::car_search_map',
			'amotos_car_advanced_search' => __CLASS__ . '::car_advanced_search',
			'amotos_car_mini_search'     => __CLASS__ . '::car_mini_search',
			'amotos_car_map'             => __CLASS__ . '::car_map',
			'amotos_manager'             => __CLASS__ . '::manager',
			'amotos_dealer'              => __CLASS__ . '::dealer',
            'amotos_nearby_places'       => __CLASS__ . '::nearby_places',
		);
		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	public function set_message($message) {
		self::$amotos_message = $message;
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array $atts Attributes. Default to empty array.
	 * @param array $wrapper Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();
		// @codingStandardsIgnoreStart
		echo empty( $wrapper['before'] ) ? '' : $wrapper['before'];
		echo call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '' : $wrapper['after'];
		// @codingStandardsIgnoreEnd

		return ob_get_clean();
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function login( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Login', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function register( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Register', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function profile( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Profile', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function reset_password( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Reset_Password', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function package( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Package', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function my_invoices( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_My_Invoice', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function payment( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Payment', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function payment_completed( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Payment_Completed', 'output' ), $atts );
	}

	/**
	 * Action handler for vehicles
	 */
	public function shortcode_car_action_handler() {
		global $post;
		if ( is_page() && strstr( $post->post_content, '[amotos_my_cars' ) ) {
			$this->my_cars_handler();
		}
		if ( is_page() && strstr( $post->post_content, '[amotos_my_save_search' ) ) {
			$this->my_save_search_handler();
		}
	}

	/**
	 * My vehicles
	 *
	 * @param $atts
	 *
	 * @return null|string
	 */
	public static function my_cars( $atts ) {
		if ( ! is_user_logged_in() ) {
			return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

			return null;
		}
		$posts_per_page = '25';
		$post_status    = $title = $car_status = $car_identity = '';
		$tax_query      = $meta_query = array();
		extract( shortcode_atts( array(
			'posts_per_page' => '25',
			'post_status'    => ''
		), $atts ) );
		global $current_user;
		wp_get_current_user();
		$user_id = $current_user->ID;
		ob_start();

		// If doing an action, show conditional content if needed....
		if ( ! empty( $_REQUEST['action'] ) ) {
			$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );
			if ( has_action( 'amotos_my_cars_content_' . $action ) ) {
				do_action( 'amotos_my_cars_content_' . $action, $atts );

				return ob_get_clean();
			}
		}
		if ( empty( $post_status ) ) {
			$post_status = array( 'publish', 'expired', 'pending', 'hidden' );
		}
		if ( ! empty( $_REQUEST['post_status'] ) ) {
			$post_status = sanitize_text_field( wp_unslash( $_REQUEST['post_status'] ) );
		}
		if ( ! empty( $_REQUEST['car_status'] ) ) {
			$car_status = sanitize_text_field( wp_unslash( $_REQUEST['car_status'] ) );
			$tax_query[]     = array(
				'taxonomy' => 'car-status',
				'field'    => 'slug',
				'terms'    => $car_status
			);
		}
		if ( ! empty( $_REQUEST['car_identity'] ) ) {
			$car_identity = amotos_clean( wp_unslash( $_REQUEST['car_identity'] ) );
			$meta_query[]      = array(
				'key'     => AMOTOS_METABOX_PREFIX . 'car_identity',
				'value'   => $car_identity,
				'type'    => 'CHAR',
				'compare' => '=',
			);
		}

		if ( ! empty( $_REQUEST['title'] ) ) {
			$title = amotos_clean( wp_unslash( $_REQUEST['title'] ) );
		}
		$query_args = array(
			'post_type'           => 'car',
			'post_status'         => $post_status,
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'offset'              => ( max( 1, get_query_var( 'paged' ) ) - 1 ) * $posts_per_page,
			'orderby'             => 'date',
			'order'               => 'desc',
			'author'              => $user_id,
			's'                   => $title
		);
		$meta_count = count( $meta_query );
		if ( $meta_count > 0 ) {
			$query_args['meta_query'] = array(
				'relation' => 'AND',
				$meta_query
			);
		}
		$tax_count = count( $tax_query );
		if ( $tax_count > 0 ) {
			$query_args['tax_query'] = array(
				'relation' => 'AND',
				$tax_query
			);
		}
		$args = apply_filters( 'amotos_my_cars_query_args', $query_args );
        $the_query = new WP_Query($args);
        echo wp_kses_post(self::$amotos_message);
        amotos_get_template( 'car/my-cars.php', array(
            'cars'              => $the_query->posts,
            'max_num_pages'     => $the_query->max_num_pages,
            'post_status'       => $post_status,
            'title'             => $title,
            'car_identity'      => $car_identity,
            'car_status'        => $car_status,
            'the_query'         => $the_query
        ) );
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Vehicle Handler
	 */
	public function my_cars_handler() {
		if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST['_wpnonce'])) , 'amotos_my_cars_actions' ) ) {
			$amotos_profile = new AMOTOS_Profile();
			$action      = isset( $_REQUEST['action'] ) ? amotos_clean( wp_unslash( $_REQUEST['action'] ) ) : '';
			$car_id = isset( $_REQUEST['car_id'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['car_id'] ))  ) : '';
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			try {
				$car     = get_post( $car_id );
				$amotos_car = new AMOTOS_Car();
				if ( ! $amotos_car->user_can_edit_car( $car_id ) ) {
					throw new Exception( esc_html__( 'Invalid ID', 'auto-moto-stock' ) );
				}
				switch ( $action ) {
					case 'delete' :
						// Trash it
						wp_trash_post( $car_id );
                        /* translators: %s: vehicle title */
						self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been deleted', 'auto-moto-stock' )), $car->post_title ) . '</div>';

						break;
					case 'mark_featured' :
						$veh_featured = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true );

						if ( $veh_featured == 1 ) {
							throw new Exception( __( 'This position has already been filled', 'auto-moto-stock' ) );
						}
						$paid_submission_type = amotos_get_option( 'paid_submission_type', 'no' );
						if ( $paid_submission_type == 'per_package' ) {
							$package_num_featured_listings = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'package_number_featured', $user_id );

							$check_package = $amotos_profile->user_package_available( $user_id );

							if ( $package_num_featured_listings > 0 && ( $check_package != - 1 ) && ( $check_package != 0 ) ) {
								if ( $package_num_featured_listings - 1 >= 0 ) {
									update_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'package_number_featured', $package_num_featured_listings - 1 );
								}
								update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 1 );
								update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time( 'mysql' ) );
                                /* translators: %s: vehicle title */
								self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf(wp_kses_post( __( '<strong>Success!</strong> %s has been featured', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							} else {
                                /* translators: %s: vehicle title */
								self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . sprintf(wp_kses_post( __( '<strong>Warning!</strong> %s Cannot be marked as featured. Either your package does not support featured listings, or you have use all featured listing available under your plan.', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							}
						} elseif ( $paid_submission_type == 'per_listing' ) {
							$price_featured_listing = apply_filters( 'amotos_price_featured_listing_for_check_mark_featured', amotos_get_option( 'price_featured_listing', 0 ) );
							if ( $price_featured_listing > 0 ) {
								$payment_page_link = amotos_get_permalink( 'payment' );
								$return_link       = add_query_arg( array(
									'car_id' => $car_id,
									'is_upgrade'  => 1
								), $payment_page_link );
								wp_safe_redirect( esc_url_raw( $return_link ) );
								exit;
							} else {
								update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 1 );
								update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date', current_time( 'mysql' ) );
							}
						}
						break;
					case 'allow_edit' :
						$listing_avl   = get_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true );
						$check_package = $amotos_profile->user_package_available( $user_id );
						if ( ( $listing_avl > 0 || $listing_avl == - 1 ) && ( $check_package == 1 ) ) {
							if ( $listing_avl != - 1 ) {
								update_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', $listing_avl - 1 );
							}
							$package_key = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'package_key', $user_id );
							update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'package_key', $package_key );
                            /* translators: %s: vehicle title */
							self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf(wp_kses_post( __( '<strong>Success!</strong> %s has been allow edit', 'auto-moto-stock' )), $car->post_title ) . '</div>';
						} else {
							self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post( __( '<strong>Warning!</strong> Can not make "Allow Edit" this vehicle', 'auto-moto-stock' ) ) . '</div>';
						}
						break;
					case 'relist_per_package' :
						$listing_avl   = get_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', true );
						$check_package = $amotos_profile->user_package_available( $user_id );
						if ( ( $listing_avl > 0 || $listing_avl == - 1 ) && ( $check_package == 1 ) ) {
							$auto_approve_request_publish = amotos_get_option( 'auto_approve_request_publish', 0 );
							if ( $auto_approve_request_publish == 1 ) {
								$data = array(
									'ID'          => $car_id,
									'post_type'   => 'car',
									'post_status' => 'publish'
								);
							} else {
								$data = array(
									'ID'          => $car_id,
									'post_type'   => 'car',
									'post_status' => 'pending'
								);
							}

							wp_update_post( $data );
							update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', 0 );
							$package_key = get_the_author_meta( AMOTOS_METABOX_PREFIX . 'package_key', $user_id );
							update_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'package_key', $package_key );
							if ( $listing_avl != - 1 ) {
								update_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'package_number_listings', $listing_avl - 1 );
							}
                            /* translators: %s: vehicle reactivate*/
							self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf(wp_kses_post( __( '<strong>Success!</strong> %s has been reactivate', 'auto-moto-stock' )), $car->post_title ) . '</div>';
                            do_action('amotos_relist_per_package_done', $car_id, $user_id);

						} else {
							self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post( __( '<strong>Warning!</strong> Can not relist this vehicle', 'auto-moto-stock' ) ) . '</div>';
						}
						break;
					case 'relist_per_listing' :
						$auto_approve_request_publish = amotos_get_option( 'auto_approve_request_publish', 0 );
						if ( $auto_approve_request_publish == 1 ) {
							$data = array(
								'ID'          => $car_id,
								'post_type'   => 'car',
								'post_status' => 'publish'
							);
						} else {
							$data = array(
								'ID'          => $car_id,
								'post_type'   => 'car',
								'post_status' => 'pending'
							);
						}
						wp_update_post( $data );
						$submit_title = get_the_title( $car_id );
						$args         = array(
							'submission_title' => $submit_title,
							'submission_url'   => get_permalink( $car_id )
						);
						amotos_send_email( get_option( 'admin_email' ), 'admin_mail_relist_listing', $args );

                        /* translators: %s: vehicle approval */
						self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf(wp_kses_post( __( '<strong>Success!</strong> %s has been resend for approval', 'auto-moto-stock' )), $car->post_title ) . '</div>';
						break;
					case 'payment_listing' :
						$payment_page_link = amotos_get_permalink( 'payment' );
						$return_link       = add_query_arg( array( 'car_id' => $car_id ), $payment_page_link );
						wp_safe_redirect( $return_link );
						exit;
					case 'hidden' :
						$data = array(
							'ID'          => $car_id,
							'post_type'   => 'car',
							'post_status' => 'hidden'
						);
						wp_update_post( $data );
                        /* translators: %s: vehicle hidden */
						self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' .  sprintf(wp_kses_post(__( '<strong>Success!</strong> %s has been hidden', 'auto-moto-stock' )) , $car->post_title )  . '</div>';
						break;
					case 'show' :
						if ( $car->post_status == 'hidden' ) {
							$data = array(
								'ID'          => $car_id,
								'post_type'   => 'car',
								'post_status' => 'publish'
							);
							wp_update_post( $data );
                            /* translators: %s: vehicle publish */
							self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' .  sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been publish', 'auto-moto-stock' )) , $car->post_title ) . '</div>';
						} else {
							self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post( __( '<strong>Warning!</strong> Can not publish this vehicle', 'auto-moto-stock' ) ) . '</div>';
						}
						break;
					case 'remove_featured':
						delete_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured');
						delete_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured_date');
						break;
					default :
						do_action( 'amotos_my_cars_do_action_' . $action );
						break;
				}

				do_action( 'amotos_my_cars_do_action', $action, $car_id );

			} catch ( Exception $e ) {
				self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . esc_html($e->getMessage())  . '</div>';
			}
		}
	}

	/**
	 * @param $atts
	 *
	 * @return null|string
	 */
	public static function my_save_search( $atts ) {
		if ( ! is_user_logged_in() ) {
			return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

			return null;
		}
		extract( shortcode_atts( array(), $atts ) );
		ob_start();
		global $current_user;
		wp_get_current_user();
		$user_id = $current_user->ID;
		global $wpdb;
		$results    = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}amotos_save_search WHERE user_id = %d", $user_id), OBJECT );
		echo wp_kses_post(self::$amotos_message);
		amotos_get_template( 'car/my-save-search.php', array( 'save_seach' => $results ) );

		return ob_get_clean();
	}

	/**
	 * Saved Search Handler
	 */
	public function my_save_search_handler() {
		if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce(amotos_clean(wp_unslash($_REQUEST['_wpnonce'])) , 'amotos_my_save_search_actions' ) ) {
			$action  = isset( $_REQUEST['action'] ) ? amotos_clean( wp_unslash( $_REQUEST['action'] ) ) : '';
			$save_id = isset( $_REQUEST['save_id'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['save_id'] ) ) ) : '';
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			try {
				switch ( $action ) {
					case 'delete' :
						global $wpdb;
						$results = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}amotos_save_search WHERE id = %d", $save_id));
						if ( $user_id == $results->user_id ) {
							$wpdb->delete( $wpdb->prefix . 'amotos_save_search', array( 'id' => $save_id ), array( '%d' ) );
                            /* translators: %s: title of save search */
							self::$amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been deleted', 'auto-moto-stock' )) , $results->title ) . '</div>';
						}
						break;
					default :
						do_action( 'amotos_my_save_search_do_action_' . $action );
						break;
				}

				do_action( 'amotos_my_save_search_do_action', $action, $save_id );

			} catch ( Exception $e ) {
				self::$amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . esc_html($e->getMessage())  . '</div>';
			}
		}
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function submit_car( $atts = array() ) {
		return AMOTOS()->get_forms()->get_form( 'submit-car', $atts );
	}

	/**
	 * Edit vehicle
	 * @return mixed
	 */
	public function edit_car() {
		return AMOTOS()->get_forms()->get_form( 'edit-car' );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function my_favorites( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_My_Favorites', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function advanced_search( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Advanced_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function compare( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Compare', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_carousel( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Carousel', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_slider( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Slider', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_gallery( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Gallery', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_featured( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Featured', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_type( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Type', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_search( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_search_map( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Search_Map', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_advanced_search( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Advanced_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_mini_search( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Mini_Search', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function car_map( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Car_Map', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function manager( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Manager', 'output' ), $atts );
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	public static function dealer( $atts ) {
		return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_Dealer', 'output' ), $atts );
	}

    public static function nearby_places($atts) {
        return self::shortcode_wrapper( array( 'AMOTOS_Shortcode_NearBy_Places', 'output' ), $atts );
    }

	/**
	 * Filter Ajax callback
	 */
	public function car_gallery_fillter_ajax() {
		if ( ! isset( $_GET['amotos_car_gallery_fillter_ajax_nonce'] )
		     || ! wp_verify_nonce( amotos_clean(wp_unslash($_GET['amotos_car_gallery_fillter_ajax_nonce'])) , 'amotos_car_gallery_fillter_ajax_action' ) ) {
			wp_send_json_error( esc_html__( 'Access Deny!', 'auto-moto-stock' ) );
		}

		$car_type = isset( $_REQUEST['car_type'] ) ? str_replace( '.', '', amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) ) : '';
		$is_carousel   = isset( $_REQUEST['is_carousel'] ) ? amotos_clean( wp_unslash( $_REQUEST['is_carousel'] ) ) : '';
		$columns_gap   = isset( $_REQUEST['columns_gap'] ) ? amotos_clean (wp_unslash( $_REQUEST['columns_gap'] )) : 'col-gap-30';
		$columns       = isset( $_REQUEST['columns'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['columns'] ))) : 4;
		$item_amount   = isset( $_REQUEST['item_amount'] ) ? absint( amotos_clean(wp_unslash( $_REQUEST['item_amount'] ))  ) : 10;
		$image_size    = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
		$color_scheme  = isset( $_REQUEST['color_scheme'] ) ? amotos_clean( wp_unslash( $_REQUEST['color_scheme'] ) ) : '';

		return amotos_do_shortcode( 'amotos_car_gallery', array(
			'is_carousel'     => $is_carousel,
			'color_scheme'    => $color_scheme,
			'columns'         => $columns,
			'item_amount'     => $item_amount,
			'image_size'      => $image_size,
			'columns_gap'     => $columns_gap,
			'category_filter' => "true",
			'car_type'        => $car_type
		) );

		wp_die();
	}

	/**
	 * Filter City Ajax callback
	 */
	public function car_featured_fillter_city_ajax() {
		if ( ! isset( $_GET['amotos_car_featured_fillter_city_ajax_nonce'] )
		     || ! wp_verify_nonce( amotos_clean(wp_unslash($_GET['amotos_car_featured_fillter_city_ajax_nonce'])) , 'amotos_car_featured_fillter_city_ajax_action' ) ) {
			wp_send_json_error( esc_html__( 'Access Deny!', 'auto-moto-stock' ) );
		}

		$car_city         = isset( $_REQUEST['car_city'] ) ? str_replace( '.', '', amotos_clean( wp_unslash( $_REQUEST['car_city'] ) ) ) : '';
		$layout_style          = isset( $_REQUEST['layout_style'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout_style'] ) ) : '';
		$car_type         = isset( $_REQUEST['car_type'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) : '';
		$car_status       = isset( $_REQUEST['car_status'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_status'] ) ) : '';
		$car_styling      = isset( $_REQUEST['car_styling'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_styling'] ) ) : '';
		$car_cities       = isset( $_REQUEST['car_cities'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_cities'] ) ) : '';
		$car_state        = isset( $_REQUEST['car_state'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_state'] ) ) : '';
		$car_neighborhood = isset( $_REQUEST['car_neighborhood'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_neighborhood'] ) ) : '';
		$car_label        = isset( $_REQUEST['car_label'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_label'] ) ) : '';
		$color_scheme          = isset( $_REQUEST['color_scheme'] ) ? amotos_clean( wp_unslash( $_REQUEST['color_scheme'] ) ) : '';
		$item_amount           = isset( $_REQUEST['item_amount'] ) ? absint(sanitize_text_field(wp_unslash( $_REQUEST['item_amount'] )) ) : 10;
		$image_size            = isset( $_REQUEST['image_size'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['image_size'] ) ) : '';
		$include_heading       = isset( $_REQUEST['include_heading'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['include_heading'] ) ) : '';
		$heading_sub_title     = isset( $_REQUEST['heading_sub_title'] ) ? amotos_clean( wp_unslash( $_REQUEST['heading_sub_title'] ) ) : '';
		$heading_title         = isset( $_REQUEST['heading_title'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['heading_title'] ) ) : '';
		$heading_text_align    = isset( $_REQUEST['heading_text_align'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['heading_text_align'] ) ) : '';

		return amotos_do_shortcode( 'amotos_car_featured', array(
			'layout_style'          => $layout_style,
			'car_type'              => $car_type,
			'car_status'            => $car_status,
			'car_styling'           => $car_styling,
			'car_cities'            => $car_cities,
			'car_state'             => $car_state,
			'car_neighborhood'      => $car_neighborhood,
			'car_label'             => $car_label,
			'color_scheme'          => $color_scheme,
			'item_amount'           => $item_amount,
			'image_size2'           => $image_size,
			'include_heading'       => $include_heading,
			'heading_sub_title'     => $heading_sub_title,
			'heading_title'         => $heading_title,
			'heading_text_align'    => $heading_text_align,
			'car_city'              => $car_city,
		) );
		wp_die();
	}

	/**
	 * Vehicle paging
	 */
	public function car_paging_ajax() {
		if ( ! isset( $_GET['amotos_car_paging_ajax_nonce'] )
		     || ! wp_verify_nonce(amotos_clean(wp_unslash($_GET['amotos_car_paging_ajax_nonce'])) , 'amotos_car_paging_ajax_action' ) ) {
			wp_send_json_error( esc_html__( 'Access Deny!', 'auto-moto-stock' ) );
		}

		$paged         = isset( $_REQUEST['paged'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['paged'] )) ) : 1;
		$layout        = isset( $_REQUEST['layout'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout'] ) ) : '';
		$items_amount  = isset( $_REQUEST['items_amount'] ) ? absint( wp_unslash( $_REQUEST['items_amount'] ) ) : 10;
		$columns       = isset( $_REQUEST['columns'] ) ? absint( wp_unslash( $_REQUEST['columns'] ) ) : 4;
		$image_size    = isset( $_REQUEST['image_size'] ) ? amotos_clean( amotos_clean(wp_unslash( $_REQUEST['image_size'] )) ) : '';
		$columns_gap   = isset( $_REQUEST['columns_gap'] ) ? amotos_clean(wp_unslash( $_REQUEST['columns_gap'] ))  : 'col-gap-30';
		$view_all_link = isset( $_REQUEST['view_all_link'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_all_link'] ) ) : '';

		$car_type         = isset( $_REQUEST['car_type'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_type'] ) ) : '';
		$car_status       = isset( $_REQUEST['car_status'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_status'] ) ) : '';
		$car_styling      = isset( $_REQUEST['car_styling'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_styling'] ) ) : '';
		$car_city         = isset( $_REQUEST['car_city'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_city'] ) ) : '';
		$car_state        = isset( $_REQUEST['car_state'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_state'] ) ) : '';
		$car_neighborhood = isset( $_REQUEST['car_neighborhood'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_neighborhood'] ) ) : '';
		$car_label        = isset( $_REQUEST['car_label'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_label'] ) ) : '';
		$car_featured     = isset( $_REQUEST['car_featured'] ) ? amotos_clean( wp_unslash( $_REQUEST['car_featured'] ) ) : '';

		$author_id = isset( $_REQUEST['author_id'] ) ? amotos_clean( wp_unslash( $_REQUEST['author_id'] ) ) : '';
		$manager_id  = isset( $_REQUEST['manager_id'] ) ? amotos_clean( wp_unslash( $_REQUEST['manager_id'] ) ) : '';
		return amotos_do_shortcode( 'amotos_car', array(
			'item_amount'           => $items_amount,
			'layout_style'          => $layout,
			'view_all_link'         => $view_all_link,
			'show_paging'           => "true",
			'columns'               => $columns,
			'image_size'            => $image_size,
			'columns_gap'           => $columns_gap,
			'paged'                 => $paged,
			'car_type'              => $car_type,
			'car_status'            => $car_status,
			'car_styling'           => $car_styling,
			'car_city'              => $car_city,
			'car_state'             => $car_state,
			'car_neighborhood'      => $car_neighborhood,
			'car_label'             => $car_label,
			'car_featured'          => $car_featured,
			'author_id'             => $author_id,
			'manager_id'            => $manager_id
		) );
		wp_die();
	}

	/**
	 * Manager paging
	 */
	public function manager_paging_ajax() {

		if ( ! isset( $_GET['amotos_manager_paging_ajax_nonce'] )
		     || ! wp_verify_nonce( amotos_clean(wp_unslash($_GET['amotos_manager_paging_ajax_nonce'])) , 'amotos_manager_paging_ajax_action' ) ) {
			wp_send_json_error( esc_html__( 'Access Deny!', 'auto-moto-stock' ) );
		}

		$paged       = isset( $_REQUEST['paged'] ) ? absint( amotos_clean(wp_unslash( $_REQUEST['paged'] ))  ) : 1;
		$layout      = isset( $_REQUEST['layout'] ) ? amotos_clean( wp_unslash( $_REQUEST['layout'] ) ) : '';
		$item_amount = isset( $_REQUEST['item_amount'] ) ? absint(amotos_clean(wp_unslash( $_REQUEST['item_amount'] ))  ) : 10;
		$items       = isset( $_REQUEST['items'] ) ? amotos_clean( wp_unslash( $_REQUEST['items'] ) ) : '';
		$image_size  = isset( $_REQUEST['image_size'] ) ? amotos_clean( wp_unslash( $_REQUEST['image_size'] ) ) : '';
		$show_paging = isset( $_REQUEST['show_paging'] ) ? amotos_clean( wp_unslash( $_REQUEST['show_paging'] ) ) : '';
		$post_not_in = isset( $_REQUEST['post_not_in'] ) ? amotos_clean( wp_unslash( $_REQUEST['post_not_in'] ) ) : '';

		return amotos_do_shortcode( 'amotos_manager', array(
			'layout_style' => $layout,
			'item_amount'  => $item_amount,
			'items'        => $items,
			'image_size'   => $image_size,
			'paged'        => $paged,
			'show_paging'  => $show_paging,
			'post_not_in'  => $post_not_in
		) );
		wp_die();
	}

	public function car_set_session_view_as_ajax() {
		AMOTOS_Compare::open_session();
		$view_as = isset( $_REQUEST['view_as'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_as'] ) ) : '';
		if ( ! empty( $view_as ) && in_array( $view_as, array( 'car-list', 'car-grid' ) ) ) {
			$_SESSION['car_view_as'] = $view_as;
		}
	}

	public function manager_set_session_view_as_ajax() {
		AMOTOS_Compare::open_session();
		$view_as = isset( $_REQUEST['view_as'] ) ? amotos_clean( wp_unslash( $_REQUEST['view_as'] ) ) : '';
		if ( ! empty( $view_as ) && in_array( $view_as, array( 'manager-list', 'manager-grid' ) ) ) {
			$_SESSION['manager_view_as'] = $view_as;
		}
	}
}