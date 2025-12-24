<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://auto-moto-stock.com
 * @since      1.0.0
 *
 * @package    Auto_Moto_Stock
 * @subpackage Auto_Moto_Stock/includes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'AMOTOS_Template_Hooks' ) ) {
	/**
	 * Class AMOTOS_Template_Hooks
	 */
	require_once AMOTOS_PLUGIN_DIR . 'includes/class-amotos-loader.php';

	class AMOTOS_Template_Hooks {
		protected $loader;
		/**
		 * Instance variable for singleton pattern
		 */
		private static $instance = null;

		/**
		 * Return class instance
		 * @return AMOTOS_Template_Hooks|null
		 */
		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			$this->loader = new AMOTOS_Loader();
			$this->loader->add_action( 'amotos_before_main_content', $this, 'output_content_wrapper_start', 10 );
			$this->loader->add_action( 'amotos_after_main_content', $this, 'output_content_wrapper_end', 10 );
			// Vehicle sidebar
			$this->loader->add_action( 'amotos_sidebar_car', $this, 'sidebar_car', 10 );
			$this->loader->add_action( 'amotos_sidebar_manager', $this, 'sidebar_manager', 10 );
			$this->loader->add_action( 'amotos_sidebar_invoice', $this, 'sidebar_invoice', 10 );

			// Archive Vehicle
			$this->loader->add_action( 'amotos_archive_car_before_main_content', $this, 'archive_car_search', 10 );
			//$this->loader->add_action( 'amotos_archive_car_heading', $this, 'archive_car_heading', 10, 4 );
			//$this->loader->add_action( 'amotos_archive_car_action', $this, 'archive_car_action', 10, 1 );
			$this->loader->add_action( 'amotos_loop_car', $this, 'loop_car', 10, 4 );

			// Archive Manager
			$this->loader->add_action( 'amotos_archive_manager_heading', $this, 'archive_manager_heading', 10, 1 );
			$this->loader->add_action( 'amotos_archive_manager_action', $this, 'archive_manager_action', 10, 1 );
			$this->loader->add_action( 'amotos_loop_manager', $this, 'loop_manager', 10, 3 );

		

			$enable_comments_reviews_car = amotos_get_option( 'enable_comments_reviews_car', 1 );
			if ( $enable_comments_reviews_car == 1 ) {
				$this->loader->add_action( 'amotos_single_car_after_summary', $this, 'comments_template', 95 );
			}

			// Single Manager
			$this->loader->add_action( 'amotos_single_manager_summary', $this, 'single_manager_info', 5 );

			$enable_comments_reviews_manager = amotos_get_option( 'enable_comments_reviews_manager', 0 );
			if ( $enable_comments_reviews_manager == 1 ) {
				$this->loader->add_action( 'amotos_single_manager_summary', $this, 'comments_template', 15 );
			}

			$this->loader->add_action( 'amotos_single_manager_summary', $this, 'single_manager_car', 20 );
			$this->loader->add_action( 'amotos_single_manager_summary', $this, 'single_manager_other', 30 );

			// Author
			$this->loader->add_action( 'amotos_author_summary', $this, 'author_info', 5 );
			$this->loader->add_action( 'amotos_author_summary', $this, 'author_car', 10 );

			// Single Invoice
			$this->loader->add_action( 'amotos_single_invoice_summary', $this, 'single_invoice', 10 );

			// Vehicle Action
			$this->loader->add_action( 'amotos_car_action', $this, 'car_view_gallery', 5 );
			$this->loader->add_action( 'amotos_car_action', $this, 'car_favorite', 10 );
			$this->loader->add_action( 'amotos_car_action', $this, 'car_compare', 15 );

			add_action( 'pre_get_posts', array( $this, 'order_by_featured_pre_get_posts' ), 99 );
			add_action( 'pre_get_posts', array( $this, 'order_by_viewed_pre_get_posts' ), 99 );
			$this->loader->run();
		}

		/**
		 * output_content_wrapper
		 */
		public function output_content_wrapper_start() {
			amotos_get_template( 'global/wrapper-start.php' );
		}

		/**
		 * output_content_wrapper
		 */
		public function output_content_wrapper_end() {
			amotos_get_template( 'global/wrapper-end.php' );
		}

		/**
		 * archive_car_search
		 */
		public function archive_car_search() {
			$enable_archive_search_form = amotos_get_option( 'enable_archive_search_form', '0' );
			if ( $enable_archive_search_form == '1' ) {
				amotos_get_template( 'archive-car/search-form.php' );
			}
		}

		/**
		 * Vehicle sidebar
		 */
		public function sidebar_car() {
			amotos_get_template( 'global/sidebar-car.php' );
		}

		/**
		 * Manager sidebar
		 */
		public function sidebar_manager() {
			amotos_get_template( 'global/sidebar-manager.php' );
		}

		/**
		 * Invoice sidebar
		 */
		public function sidebar_invoice() {
			amotos_get_template( 'global/sidebar-invoice.php' );
		}

		/**
		 * archive_car_heading
		 *
		 * @param $total_post
		 * @param $taxonomy_title
		 * @param $manager_id
		 * @param $author_id
		 */
		public function archive_car_heading( $total_post, $taxonomy_title, $manager_id, $author_id ) {
			amotos_get_template( 'archive-car/heading.php', array(
				'total_post'     => $total_post,
				'taxonomy_title' => $taxonomy_title,
				'manager_id'       => $manager_id,
				'author_id'      => $author_id
			) );
		}

		/**
		 * archive_car_action
		 *
		 * @param $taxonomy_name
		 */
		public function archive_car_action( $taxonomy_name ) {
			amotos_get_template( 'archive-car/action.php', array( 'taxonomy_name' => $taxonomy_name ) );
		}

		/**
		 * archive_manager_heading
		 *
		 * @param $total_post
		 */
		public function archive_manager_heading( $total_post ) {
			amotos_get_template( 'archive-manager/bak/heading.php', array( 'total_post' => $total_post ) );
		}

		/**
		 * archive_manager_action
		 *
		 * @param $keyword
		 */
		public function archive_manager_action( $keyword ) {
			amotos_get_template( 'archive-manager/bak/action.php', array( 'keyword' => $keyword ) );
		}

		/**
		 * Loop Vehicle
		 *
		 * @param $car_item_class
		 * @param $custom_car_image_size
		 * @param $car_image_class
		 * @param $car_item_content_class
		 */
		public function loop_car( $car_item_class, $custom_car_image_size, $car_image_class, $car_item_content_class ) {
			amotos_get_template( 'loop/car.php', array(
				'car_item_class'         => $car_item_class,
				'custom_car_image_size'  => $custom_car_image_size,
				'car_image_class'        => $car_image_class,
				'car_item_content_class' => $car_item_content_class
			) );
		}

		/**
		 * loop_manager
		 *
		 * @param $sf_item_wrap
		 * @param $manager_layout_style
		 */
		public function loop_manager( $sf_item_wrap, $manager_layout_style, $custom_manager_image_size ) {
			amotos_get_template( 'loop/manager.php', array(
				'sf_item_wrap'            => $sf_item_wrap,
				'manager_layout_style'      => $manager_layout_style,
				'custom_manager_image_size' => $custom_manager_image_size
			) );
		}

		/**
		 * Single Vehicle header
		 */
		public function single_car_header() {
			amotos_get_template( 'single-car/header.php' );
		}

		/**
		 * Single Vehicle footer
		 */
		public function single_car_footer() {
			amotos_get_template( 'single-car/bak/footer.php' );
		}

        /**
         * Single Vehicle gallery
         */
        public function single_car_gallery()
        {
            amotos_get_template('single-car/gallery.php');
        }

		/**
		 * Single Vehicle description
		 */
		public function single_car_description() {
			amotos_get_template( 'single-car/description.php' );
		}

		/**
		 * Single Vehicle attachments
		 */
		public function single_car_attachments() {
			amotos_get_template( 'single-car/attachments.php' );
		}

		/**
		 * Single Vehicle location
		 */
		public function single_car_location() {
			amotos_get_template( 'single-car/location.php' );
		}

		/**
		 * Single Vehicle map directions
		 */
		public function single_car_map_directions() {
			global $post;
			$enable_map_directions = amotos_get_option( 'enable_map_directions', 1 );
			if ( $enable_map_directions == 1 ) {
				amotos_get_template( 'single-car/google-map-directions.php', array( 'car_id' => $post->ID ) );
			}
		}

		/**
		 * Single Vehicle nearby places
		 */
		public function single_car_nearby_places() {
			global $post;
			$enable_nearby_places = amotos_get_option( 'enable_nearby_places', 1 );
			if ( $enable_nearby_places == 1 ) {
				amotos_get_template( 'single-car/bak/nearby-places.php', array( 'car_id' => $post->ID ) );
			}
		}

		/**
		 * Single Vehicle walk score
		 */
		public function single_car_walk_score() {
			global $post;
			$enable_walk_score = amotos_get_option( 'enable_walk_score', 0 );
			if ( $enable_walk_score == 1 ) {
				amotos_get_template( 'single-car/bak/walk-score.php', array( 'car_id' => $post->ID ) );
			}
		}

		/**
		 * Single Vehicle contact manager
		 */
		public function single_car_contact_manager() {
			$car_form_sections = amotos_get_option( 'car_form_sections', array(
				//'title_des',
				'basic_info',
				'tech_data',
				'stylings',
				'location',
				//'type',
				'price',				
				//'details',
				'media',
				'manager'
			) );
			if ( in_array( 'contact', $car_form_sections ) ) {
				$hide_contact_information_if_not_login = amotos_get_option( 'hide_contact_information_if_not_login', 0 );
				if ( $hide_contact_information_if_not_login == 0 ) {
					amotos_get_template( 'single-car/bak/contact-manager.php' );
				} else {
					if ( is_user_logged_in() ) {
						amotos_get_template( 'single-car/bak/contact-manager.php' );
					} else {
						amotos_get_template( 'single-car/bak/contact-manager-not-login.php' );
					}
				}
			}
		}

		/**
		 * Single manager info
		 */
		public function single_manager_info() {
			amotos_get_template( 'single-manager/manager-info.php' );
		}

		/**
		 * Single manager Vehicle
		 */
		public function single_manager_car() {
			$enable_car_of_manager = amotos_get_option( 'enable_car_of_manager' );
			if ( $enable_car_of_manager == 1 ) {
				amotos_get_template( 'single-manager/manager-car.php' );
			}
		}

		/**
		 * Author info
		 */
		public function author_info() {
			amotos_get_template( 'author/author-info.php' );
		}

		/**
		 * Author Vehicle
		 */
		public function author_car() {
			amotos_get_template( 'author/author-car.php' );
		}

		/**
		 * Single other manager
		 */
		public function single_manager_other() {
			$enable_other_manager = amotos_get_option( 'enable_other_manager' );
			if ( $enable_other_manager == 1 ) {
				amotos_get_template( 'single-manager/other-manager.php' );
			}
		}

		/**
		 * Single invoice
		 */
		public function single_invoice() {
			amotos_get_template( 'single-invoice/invoice.php' );
		}

		/**
		 * Social Share
		 */
		public function car_view_gallery() {
			amotos_get_template( 'car/view-galley.php' );
		}

		/**
		 * Favorite
		 */
		public function car_favorite() {
			if ( amotos_get_option( 'enable_favorite_car', '1' ) == '1' ) {
				amotos_get_template( 'car/favorite.php' );
			}
		}

		/**
		 * Compare
		 */
		public function car_compare() {
			if ( amotos_get_option( 'enable_compare_cars', '1' ) == '1' ) {
				amotos_get_template( 'car/compare-button.php' );
			}
		}

		/**
		 * comments_template
		 */
		public function comments_template() {
			if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
		}

		public function order_by_featured_pre_get_posts( $q ) {
			$amotos_orderby_featured = $q->get( 'amotos_orderby_featured', false );
			if ( $amotos_orderby_featured == true ) {
				add_filter( 'posts_clauses', array( $this, 'order_by_featured_post_clauses' ), 10, 2 );
				add_filter( 'the_posts', array( $this, 'remove_car_query_featured_filters' ) );
			}
		}

		public function order_by_featured_post_clauses( $args, $wp_query ) {
			global $wpdb;
			$amotos_prefix      = AMOTOS_METABOX_PREFIX;
			$args['join']    .= " LEFT JOIN {$wpdb->prefix}postmeta as amotos_mt1 ON ( {$wpdb->prefix}posts.ID = amotos_mt1.post_id AND amotos_mt1.meta_key = '{$amotos_prefix}car_featured')";
			$args['join']    .= " LEFT JOIN {$wpdb->prefix}postmeta as amotos_mt2 ON ( {$wpdb->prefix}posts.ID = amotos_mt2.post_id AND amotos_mt2.meta_key = '{$amotos_prefix}car_featured_date')";
			$args['orderby'] = " CAST(amotos_mt1.meta_value AS CHAR) DESC, CAST(amotos_mt2.meta_value AS CHAR) DESC, {$wpdb->prefix}posts.menu_order DESC, {$wpdb->prefix}posts.post_date DESC ";

			return $args;
		}

		public function remove_car_query_featured_filters( $posts ) {
			remove_filter( 'posts_clauses', array( $this, 'order_by_featured_post_clauses' ) );

			return $posts;
		}

		public function order_by_viewed_pre_get_posts( $q ) {
			$amotos_orderby_viewed = $q->get( 'amotos_orderby_viewed', false );
			if ( $amotos_orderby_viewed == true ) {
				add_filter( 'posts_clauses', array( $this, 'order_by_viewed_post_clauses' ), 10, 2 );
				add_filter( 'the_posts', array( $this, 'remove_car_query_viewed_filters' ) );
			}
		}

		public function order_by_viewed_post_clauses( $args, $wp_query ) {
			global $wpdb;
			$amotos_prefix      = AMOTOS_METABOX_PREFIX;
			$args['join']    .= " LEFT JOIN {$wpdb->prefix}postmeta as amotos_mt3 ON ( {$wpdb->prefix}posts.ID = amotos_mt3.post_id AND amotos_mt3.meta_key = '{$amotos_prefix}car_views_count')";
			$args['orderby'] = " (amotos_mt3.meta_value+0) DESC, {$wpdb->prefix}posts.menu_order DESC, {$wpdb->prefix}posts.post_date DESC ";

			return $args;
		}

		public function remove_car_query_viewed_filters( $posts ) {
			remove_filter( 'posts_clauses', array( $this, 'order_by_viewed_post_clauses' ) );

			return $posts;
		}

	}
}
if ( ! function_exists( 'amotos_template_hooks' ) ) {
	function amotos_template_hooks() {
		return AMOTOS_Template_Hooks::get_instance();
	}
}
// Global for backwards compatibility.
$GLOBALS['amotos_template_hooks'] = amotos_template_hooks();