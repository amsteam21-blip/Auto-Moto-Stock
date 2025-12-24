<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Shortcode_Car' ) ) {
	/**
	 * Class AMOTOS_Shortcode_Car
	 */
	class AMOTOS_Shortcode_Car {
		private $amotos_message = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_shortcode( 'amotos_my_cars', array( $this, 'my_cars' ) );
			add_shortcode( 'amotos_submit_car', array( $this, 'submit_car' ) );
			add_shortcode( 'amotos_my_favorites', array( $this, 'my_favorites' ) );
			add_shortcode( 'amotos_advanced_search', array( $this, 'advanced_search_shortcode' ) );
			add_shortcode( 'amotos_compare', array( $this, 'compare_shortcode' ) );
			add_shortcode( 'amotos_my_save_search', array( $this, 'my_save_search' ) );
		}

		/**
		 * Handle actions which need to be run before the shortcode
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
		 * New vehicle
		 *
		 * @param array $atts
		 *
		 * @return mixed
		 */
		public function submit_car( $atts = array() ) {
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
		 * Vehicle Handler
		 */
		public function my_cars_handler() {
			if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( amotos_clean( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'amotos_my_cars_actions' ) ) {
				$amotos_profile = new AMOTOS_Profile();
				$action      = amotos_clean( wp_unslash( $_REQUEST['action'] ) );
				$car_id = absint( amotos_clean( wp_unslash( $_REQUEST['car_id'] ) ) );
				global $current_user;
				wp_get_current_user();
				$user_id = $current_user->ID;
				try {
					$car     = get_post( $car_id );
					$amotos_car = new AMOTOS_Car();
					if ( ! $amotos_car->user_can_edit_car( $car_id ) ) {
						throw new Exception( __( 'Invalid ID', 'auto-moto-stock' ) );
					}
					switch ( $action ) {
						case 'delete' :
							// Trash it
							wp_trash_post( $car_id );
                            /* translators: %s: vehicle title */
							$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been deleted', 'auto-moto-stock' )) , $car->post_title ) . '</div>';

							break;
						case 'mark_featured' :
							$veh_featured = get_post_meta( $car_id, AMOTOS_METABOX_PREFIX . 'car_featured', true );

							if ( $veh_featured == 1 ) {
								throw new Exception( esc_html__( 'This position has already been filled', 'auto-moto-stock' ) );
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
									$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post( __( '<strong>Success!</strong> %s has been featured', 'auto-moto-stock' )), $car->post_title ) . '</div>';
								} else {
                                    /* translators: %s: vehicle title */
									$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . sprintf( wp_kses_post(__( '<strong>Warning!</strong> %s Cannot be marked as featured. Either your package does not support featured listings, or you have use all featured listing available under your plan.', 'auto-moto-stock' )) , $car->post_title ) . '</div>';
								}
							} elseif ( $paid_submission_type == 'per_listing' ) {
								$price_featured_listing = amotos_get_option( 'price_featured_listing', 0 );
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
								$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been allow edit', 'auto-moto-stock' )) , $car->post_title ) . '</div>';
							} else {
								$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . __( '<strong>Warning!</strong> Can not make "Allow Edit" this vehicle', 'auto-moto-stock' ) . '</div>';
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
                                /* translators: %s: vehicle title */
								$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post( __( '<strong>Success!</strong> %s has been reactivate', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							} else {
								$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post( __( '<strong>Warning!</strong> Can not relist this vehicle', 'auto-moto-stock' )) . '</div>';
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
                            /* translators: %s: vehicle title */
							$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post( __( '<strong>Success!</strong> %s has been resend for approval', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							break;
						case 'payment_listing' :
							$payment_page_link = amotos_get_permalink( 'payment' );
							$return_link       = add_query_arg( array( 'car_id' => $car_id ), $payment_page_link );
							wp_safe_redirect( esc_url_raw( $return_link ) );
							exit;
						case 'hidden' :
							$data = array(
								'ID'          => $car_id,
								'post_type'   => 'car',
								'post_status' => 'hidden'
							);
							wp_update_post( $data );
                            /* translators: %s: vehicle title */
							$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' .  sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been hidden', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							break;
						case 'show' :
							if ( $car->post_status == 'hidden' ) {
								$data = array(
									'ID'          => $car_id,
									'post_type'   => 'car',
									'post_status' => 'publish'
								);
								wp_update_post( $data );
                                /* translators: %s: vehicle title */
								$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been publish', 'auto-moto-stock' )), $car->post_title ) . '</div>';
							} else {
								$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . wp_kses_post(__( '<strong>Warning!</strong> Can not publish this vehicle', 'auto-moto-stock' ))  . '</div>';
							}
							break;
						default :
							do_action( 'amotos_my_cars_do_action_' . $action );
							break;
					}

					do_action( 'amotos_my_cars_do_action', $action, $car_id );

				} catch ( Exception $e ) {
					$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . esc_html($e->getMessage())  . '</div>';
				}
			}
		}

		/**
		 * My vehicles
		 *
		 * @param $atts
		 *
		 * @return null|string
		 */
		public function my_cars( $atts ) {
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
				$action = amotos_clean( wp_unslash( $_REQUEST['action'] ) );
				if ( has_action( 'amotos_my_cars_content_' . $action ) ) {
					do_action( 'amotos_my_cars_content_' . $action, $atts );

					return ob_get_clean();
				}
			}
			if ( empty( $post_status ) ) {
				$post_status = array( 'publish', 'expired', 'pending', 'hidden' );
			}
			if ( ! empty( $_REQUEST['post_status'] ) ) {
				$post_status = amotos_clean( wp_unslash( $_REQUEST['post_status'] ) );
			}
			if ( ! empty( $_REQUEST['car_status'] ) ) {
				$car_status = amotos_clean( wp_unslash( $_REQUEST['car_status'] ) );
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
			echo wp_kses_post( $this->amotos_message );
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
		 * My favorites
		 *
		 * @param $atts
		 *
		 * @return null|string
		 */
		public function my_favorites( $atts ) {
			if ( ! is_user_logged_in() ) {
				return amotos_get_template_html( 'global/access-denied.php', array( 'type' => 'not_login' ) );

				return null;
			}
			$posts_per_page = 8;
			extract( shortcode_atts( array(
				'posts_per_page' => '9',
			), $atts ) );
			ob_start();
			global $current_user;
			wp_get_current_user();
			$user_id      = $current_user->ID;
			$my_favorites = get_user_meta( $user_id, AMOTOS_METABOX_PREFIX . 'favorites_car', true );
			if ( empty( $my_favorites ) ) {
				$my_favorites = array( 0 );
			}
			$args = apply_filters( 'amotos_my_cars_query_args', array(
				'post_type'           => 'car',
				'post__in'            => $my_favorites,
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $posts_per_page,
				'offset'              => ( max( 1, get_query_var( 'paged' ) ) - 1 ) * $posts_per_page,
			) );

			$favorites = new WP_Query( $args );
			amotos_get_template( 'car/my-favorites.php', array(
				'favorites'     => $favorites,
				'max_num_pages' => $favorites->max_num_pages
			) );

			return ob_get_clean();
		}
		
		/**
		 * Advanced Search shortcode
		 * 
		 * My Saved Search
		 *
		 * @param $atts
		 *
		 * @return null|string
		 */
		public function my_save_search( $atts ) {
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
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}amotos_save_search WHERE user_id = %d", $user_id ), OBJECT );
			echo wp_kses_post( $this->amotos_message );
			amotos_get_template( 'car/my-save-search.php', array( 'save_seach' => $results ) );

			return ob_get_clean();
		}

		/**
		 * Saved Search Handler
		 */
		public function my_save_search_handler() {
			if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( amotos_clean( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'amotos_my_save_search_actions' ) ) {
				$action  = amotos_clean( wp_unslash( $_REQUEST['action'] ) );
				$save_id = absint( amotos_clean( wp_unslash( $_REQUEST['save_id'] ) ) );
				global $current_user;
				wp_get_current_user();
				$user_id = $current_user->ID;
				try {
					switch ( $action ) {
						case 'delete' :
							global $wpdb;
							$results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}amotos_save_search WHERE id = %d", $save_id ) );
							if ( $user_id == $results->user_id ) {
								$wpdb->delete( "{$wpdb->prefix}amotos_save_search", array( 'id' => $save_id ), array( '%d' ) );
                                /* translators: %s: title of save search */
								$this->amotos_message = '<div class="amotos-message alert alert-success" role="alert">' . sprintf( wp_kses_post(__( '<strong>Success!</strong> %s has been deleted', 'auto-moto-stock' )) , $results->title ) . '</div>';
							}
							break;
						default :
							do_action( 'amotos_my_save_search_do_action_' . $action );
							break;
					}

					do_action( 'amotos_my_save_search_do_action', $action, $save_id );

				} catch ( Exception $e ) {
					$this->amotos_message = '<div class="amotos-message alert alert-danger" role="alert">' . esc_html($e->getMessage())  . '</div>';
				}
			}
		}

		public function advanced_search_shortcode() {
			return amotos_get_template_html( 'car/advanced-search.php' );
		}

		/**
		 * Compare shortcode
		 */
		public function compare_shortcode() {
			return amotos_get_template_html( 'car/compare.php' );
		}
	}
}