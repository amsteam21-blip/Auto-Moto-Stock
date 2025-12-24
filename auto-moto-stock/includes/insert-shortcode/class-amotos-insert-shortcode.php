<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'AMOTOS_Insert_Shortcode' ) ) {
	/**
	 * Support insert shortcode for editor
	 * Class AMOTOS_Insert_Shortcode
	 */
	class AMOTOS_Insert_Shortcode {
		/*
		 * loader instances
		 */
		public static $instance;

		/**
		 * Init SP_Loader
		 * 
		 */
		public static function init() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Construct
		 */
		public function __construct() {
			global $pagenow;
			if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
				add_action( 'init', array( $this, 'add_action' ) );
			}
		}

		/**
		 * Add action
		 */
		public function add_action() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_generator_scripts' ), 5 );
			add_action( 'admin_footer', array( $this, 'content_display' ), 12 );
			add_filter( 'media_buttons', array( $this, 'register_button' ) );
		}

		/**
		 * Enqueue generator scripts
		 */
		public function enqueue_generator_scripts() {
			wp_enqueue_style('selectize');
			wp_enqueue_script( 'selectize');

			wp_enqueue_style(AMOTOS_PLUGIN_PREFIX . 'st-utils', AMOTOS_PLUGIN_URL . 'quick-framework/assets/vendors/st-utils/st-utils.min.css', array(), AMOTOS_PLUGIN_VER, 'all');
			wp_enqueue_script(AMOTOS_PLUGIN_PREFIX . 'st-utils', AMOTOS_PLUGIN_URL . 'quick-framework/assets/vendors/st-utils/st-utils.min.js', array( 'jquery' ), AMOTOS_PLUGIN_VER, true);

			wp_enqueue_style( AMOTOS_PLUGIN_PREFIX . 'insert-shortcode', AMOTOS_PLUGIN_URL . 'includes/insert-shortcode/assets/css/insert-shortcode.min.css', array(), AMOTOS_PLUGIN_VER, 'all' );
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'insert-shortcode-popup', AMOTOS_PLUGIN_URL . 'includes/insert-shortcode/assets/js/popup.min.js', array( 'jquery' ), AMOTOS_PLUGIN_VER, true );
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'insert-shortcode-upload', AMOTOS_PLUGIN_URL . 'includes/insert-shortcode/assets/js/upload.min.js', array( 'jquery' ), AMOTOS_PLUGIN_VER, true );
			wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'insert-shortcode', AMOTOS_PLUGIN_URL . 'includes/insert-shortcode/assets/js/insert-shortcode.min.js', array( 'jquery' ), AMOTOS_PLUGIN_VER, true );
		}

		/**
		 * Register button
		 */
		public function register_button() {
			echo '<a class="button amotos-insert-shortcode-button" href="javascript:void(0)">' . esc_html__( "Add AMS Shortcodes", 'auto-moto-stock' ) . '</a>';
		}

		/**
		 * Add narrow taxonomy
		 *
		 * @param $taxonomy
		 * @param $title
		 *
		 * @return array
		 */
		private function add_narrow_taxonomy( $taxonomy, $title ) {
			$taxonomies   = array();
			$taxonomy_arr = get_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'orderby' => 'ASC' ) );
			if ( is_array( $taxonomy_arr ) ) {
				foreach ( $taxonomy_arr as $tx ) {
					$taxonomies[ $tx->slug ] = $tx->name;
				}
			}

			return array(
				'type'   => 'amotos_selectize',
                /* translators: %s: taxonomy title */
				'title'  => sprintf( esc_html__( 'Narrow %s', 'auto-moto-stock' ), esc_html( $title ) ),
                /* translators: %s: taxonomy title */
				'desc'   => sprintf( esc_html__( 'Enter %s by names to narrow output.', 'auto-moto-stock' ), esc_html( $title ) ),
				'values' => $taxonomies
			);
		}

		/**
		 * Add narrow Vehicle type
		 * @return array
		 */
		private function add_narrow_car_type() {
			$type  = array();
			$types = get_categories( array( 'taxonomy' => 'car-type', 'hide_empty' => 0, 'orderby' => 'ASC' ) );
			if ( is_array( $types ) ) {
				foreach ( $types as $st ) {
					$type[ $st->slug ] = $st->name;
				}
			}

			return array(
				'type'   => 'select',
				'title'  => esc_html__( 'Narrow Vehicle Type', 'auto-moto-stock' ),
				'values' => $type,
				'desc'   => esc_html__( 'Enter type by names to narrow output.', 'auto-moto-stock' )
			);
		}

		/**
		 * Content display
		 */
		public function content_display() {
			//Image with Animation
			$amotos_shortcodes['amotos_car']                 = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicles', 'auto-moto-stock' ),
				'attr'  => array(
					'layout_style'          => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'values'  => array(
							'car-grid'     => esc_html__( 'Grid', 'auto-moto-stock' ),
							'car-list'     => esc_html__( 'List', 'auto-moto-stock' ),
							'car-zigzac'   => esc_html__( 'Zigzac', 'auto-moto-stock' ),
							'car-carousel' => esc_html__( 'Carousel', 'auto-moto-stock' )
						),
						'default' => 'car-grid'
					),
					'item_amount'           => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'columns'               => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Columns', 'auto-moto-stock' ),
						'values'   => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'default'  => '3',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'items_md'              => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Desktop Small', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 1199', 'auto-moto-stock' ),
						'values'   => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '3',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'items_sm'              => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Tablet', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 992', 'auto-moto-stock' ),
						'values'   => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '2',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'items_xs'              => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Tablet Small', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 768', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '1',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'items_mb'              => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Mobile', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 480', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '1',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'image_size'            => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 , Zic Zac: 290x270 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default' => amotos_get_loop_car_image_size_default()
					),
					'columns_gap'           => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'values'   => array(
							'col-gap-0'  => '0px',
							'col-gap-10' => '10px',
							'col-gap-20' => '20px',
							'col-gap-30' => '30px',
						),
						'default'  => 'col-gap-30',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					'view_all_link'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'View All Link', 'auto-moto-stock' ),
						'value' => ''
					),
					'show_paging'           => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Paging', 'auto-moto-stock' ),
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-list', 'car-zigzac' )
						),
					),
					'include_heading'       => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Include title', 'auto-moto-stock' )
					),
					'heading_title'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_sub_title'     => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'dots'                  => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Pagination Control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'car-carousel' )
					),
					'nav'                   => array(
						'type'     => 'checkbox',
						'default'  => 'true',
						'title'    => esc_html__( 'Show Navigation Control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'car-carousel' )
					),
					'move_nav'              => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Move Navigation Par With Top title', 'auto-moto-stock' ),
						'required' => array( 'element' => 'nav', 'value' => 'true' )
					),
					'nav_position'          => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Navigation Position', 'auto-moto-stock' ),
						'values'   => array(
							''              => esc_html__( 'Middle Center', 'auto-moto-stock' ),
							'top-right'     => esc_html__( 'Top Right', 'auto-moto-stock' ),
							'bottom-center' => esc_html__( 'Bottom Center', 'auto-moto-stock' ),
						),
						'default'  => '',
						'required' => array( 'element' => 'move_nav', 'value' => 'false' )
					),
					'autoplay'              => array(
						'type'     => 'checkbox',
						'default'  => 'false',
						'title'    => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'car-carousel' )
					),
					'autoplaytimeout'       => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Autoplay interval timeout.', 'auto-moto-stock' ),
						'default'  => 1000,
						'required' => array( 'element' => 'autoplay', 'value' => 'true' )
					),
					'car_type'         => $this->add_narrow_taxonomy( 'car-type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ),
					'car_status'       => $this->add_narrow_taxonomy( 'car-status', esc_html__( 'Status', 'auto-moto-stock' ) ),
					'car_styling'      => $this->add_narrow_taxonomy( 'car-styling', esc_html__( 'Styling', 'auto-moto-stock' ) ),
					'car_city'         => $this->add_narrow_taxonomy( 'car-city', esc_html__( 'City/Town', 'auto-moto-stock' ) ),
					'car_state'        => $this->add_narrow_taxonomy( 'car-state', esc_html__( 'Province/State', 'auto-moto-stock' ) ),
					'car_neighborhood' => $this->add_narrow_taxonomy( 'car-neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ),
					'car_label'        => $this->add_narrow_taxonomy( 'car-label', esc_html__( 'Label', 'auto-moto-stock' ) ),
					'car_featured'     => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'default' => 'false'
					),
					'el_class'              => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_carousel']        = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Carousel', 'auto-moto-stock' ),
				'attr'  => array(
					'item_amount'           => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'image_size'            => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default' => amotos_get_loop_car_image_size_default()
					),
					'columns_gap'           => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'values'  => array(
							'col-gap-0'  => '0px',
							'col-gap-10' => '10px',
							'col-gap-20' => '20px',
							'col-gap-30' => '30px',
						),
						'default' => 'col-gap-0'
					),
					'color_scheme'          => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'values'  => array(
							'color-dark'  => esc_html__( 'Dark', 'auto-moto-stock' ),
							'color-light' => esc_html__( 'Light', 'auto-moto-stock' )
						),
						'default' => 'color-dark',
					),
					'include_heading'       => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Include title', 'auto-moto-stock' )
					),
					'heading_title'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_sub_title'     => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'car_type'         => $this->add_narrow_taxonomy( 'car-type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ),
					'car_status'       => $this->add_narrow_taxonomy( 'car-status', esc_html__( 'Status', 'auto-moto-stock' ) ),
					'car_styling'      => $this->add_narrow_taxonomy( 'car-styling', esc_html__( 'Styling', 'auto-moto-stock' ) ),
					'car_city'         => $this->add_narrow_taxonomy( 'car-city', esc_html__( 'City/Town', 'auto-moto-stock' ) ),
					'car_state'        => $this->add_narrow_taxonomy( 'car-state', esc_html__( 'Province/State', 'auto-moto-stock' ) ),
					'car_neighborhood' => $this->add_narrow_taxonomy( 'car-neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ),
					'car_label'        => $this->add_narrow_taxonomy( 'car-label', esc_html__( 'Label', 'auto-moto-stock' ) ),
					'car_featured'     => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'default' => 'false'
					),
					'el_class'              => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_slider']          = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Slider', 'auto-moto-stock' ),
				'attr'  => array(
					'layout_style'          => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'values'  => array(
							'navigation-middle' => esc_html__( 'Navigation Middle', 'auto-moto-stock' ),
							'pagination-image'  => esc_html__( 'Pagination as Image', 'auto-moto-stock' )
						),
						'default' => 'navigation-middle'
					),
					'item_amount'           => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'image_size'            => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 1200x600 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default' => '1200x600'
					),
					'car_type'         => $this->add_narrow_taxonomy( 'car-type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ),
					'car_status'       => $this->add_narrow_taxonomy( 'car-status', esc_html__( 'Status', 'auto-moto-stock' ) ),
					'car_styling'      => $this->add_narrow_taxonomy( 'car-styling', esc_html__( 'Styling', 'auto-moto-stock' ) ),
					'car_city'         => $this->add_narrow_taxonomy( 'car-city', esc_html__( 'City/Town', 'auto-moto-stock' ) ),
					'car_state'        => $this->add_narrow_taxonomy( 'car-state', esc_html__( 'Province/State', 'auto-moto-stock' ) ),
					'car_neighborhood' => $this->add_narrow_taxonomy( 'car-neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ),
					'car_label'        => $this->add_narrow_taxonomy( 'car-label', esc_html__( 'Label', 'auto-moto-stock' ) ),
					'car_featured'     => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'default' => 'false'
					),
					'el_class'              => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_gallery']         = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Gallery', 'auto-moto-stock' ),
				'attr'  => array(
					'is_carousel'           => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Display Carousel?', 'auto-moto-stock' ),
					),
					'color_scheme'          => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'values' => array(
							'color-dark'  => esc_html__( 'Dark', 'auto-moto-stock' ),
							'color-light' => esc_html__( 'Light', 'auto-moto-stock' )
						)
					),
					'category_filter'       => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Category Filter', 'auto-moto-stock' )
					),
					'filter_style'          => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Filter Style', 'auto-moto-stock' ),
						'values'   => array(
							'filter-isotope' => esc_html__( 'Isotope', 'auto-moto-stock' ),
							'filter-ajax'    => esc_html__( 'Ajax', 'auto-moto-stock' )
						),
						'desc'     => esc_html__( 'Not applicable for carousel', 'auto-moto-stock' ),
						'required' => array( 'element' => 'category_filter', 'value' => 'true' ),
						'default'  => 'filter-isotope'
					),
					'include_heading'       => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Include title', 'auto-moto-stock' ),
						'required' => array( 'element' => 'category_filter', 'value' => 'true' )
					),
					'heading_title'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_sub_title'     => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'item_amount'           => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'image_size'            => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 290x270 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default' => '290x270'
					),
					'columns'               => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Columns', 'auto-moto-stock' ),
						'values'  => array(
							'2' => '2',
							'3' => '3',
							'4' => '4'
						),
						'default' => '4'
					),
					'columns_gap'           => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'values'  => array(
							'col-gap-0'  => '0px',
							'col-gap-10' => '10px',
							'col-gap-20' => '20px',
							'col-gap-30' => '30px',
						),
						'default' => 'col-gap-0'
					),
					'dots'                  => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Pagination Control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'is_carousel', 'value' => 'true' )
					),
					'nav'                   => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Navigation Control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'is_carousel', 'value' => 'true' )
					),
					'autoplay'              => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'required' => array( 'element' => 'is_carousel', 'value' => 'true' ),
						'default'  => 'false'
					),
					'autoplaytimeout'       => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'default'  => 1000,
						'required' => array( 'element' => 'autoplay', 'value' => 'true' )
					),
					'car_type'         => $this->add_narrow_taxonomy( 'car-type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ),
					'car_status'       => $this->add_narrow_taxonomy( 'car-status', esc_html__( 'Status', 'auto-moto-stock' ) ),
					'car_styling'      => $this->add_narrow_taxonomy( 'car-styling', esc_html__( 'Styling', 'auto-moto-stock' ) ),
					'car_city'         => $this->add_narrow_taxonomy( 'car-city', esc_html__( 'City/Town', 'auto-moto-stock' ) ),
					'car_state'        => $this->add_narrow_taxonomy( 'car-state', esc_html__( 'Province/State', 'auto-moto-stock' ) ),
					'car_neighborhood' => $this->add_narrow_taxonomy( 'car-neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ),
					'car_label'        => $this->add_narrow_taxonomy( 'car-label', esc_html__( 'Label', 'auto-moto-stock' ) ),
					'car_featured'     => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'default' => 'false'
					),
					'el_class'              => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_featured']        = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
				'attr'  => array(
					'layout_style'          => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'values'  => array(
							'car-list-two-columns' => esc_html__( 'List Two Columns', 'auto-moto-stock' ),
							'car-cities-filter'    => esc_html__( 'Cities Filter', 'auto-moto-stock' ),
							'car-single-carousel'  => esc_html__( 'Single Carousel', 'auto-moto-stock' ),
							'car-sync-carousel'    => esc_html__( 'Sync Carousel', 'auto-moto-stock' )
						),
						'default' => 'car-list-two-columns',
						'desc'    => esc_html__( 'Select Layout Style.', 'auto-moto-stock' )
					),
					'color_scheme'          => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'values'  => array(
							'color-dark'  => esc_html__( 'Dark', 'auto-moto-stock' ),
							'color-light' => esc_html__( 'Light', 'auto-moto-stock' )
						),
						'default' => 'color-dark'
					),
					'item_amount'           => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'image_size1'           => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 240x180 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default'  => '240x180',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-list-two-columns' )
						)
					),
					'image_size2'           => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 835x320 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default'  => '835x320',
						'required' => array( 'element' => 'layout_style', 'value' => array( 'car-cities-filter' ) )
					),
					'image_size3'           => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 570x320 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default'  => '570x320',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'car-single-carousel' )
						)
					),
					'image_size4'           => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 945x605 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default'  => '945x605',
						'required' => array( 'element' => 'layout_style', 'value' => array( 'car-sync-carousel' ) )
					),
					'include_heading'       => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Include title', 'auto-moto-stock' )
					),
					'heading_title'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_sub_title'     => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_text_align'    => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Text Align', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Select title alignment.', 'auto-moto-stock' ),
						'values'   => array(
							'text-left'   => esc_html__( 'Left', 'auto-moto-stock' ),
							'text-center' => esc_html__( 'Center', 'auto-moto-stock' ),
							'text-right'  => esc_html__( 'Right', 'auto-moto-stock' ),
						),
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'car_type'         => $this->add_narrow_taxonomy( 'car-type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ),
					'car_status'       => $this->add_narrow_taxonomy( 'car-status', esc_html__( 'Status', 'auto-moto-stock' ) ),
					'car_styling'      => $this->add_narrow_taxonomy( 'car-styling', esc_html__( 'Styling', 'auto-moto-stock' ) ),
					'car_city'         => $this->add_narrow_taxonomy( 'car-city', esc_html__( 'City/Town', 'auto-moto-stock' ) ),
					'car_state'        => $this->add_narrow_taxonomy( 'car-state', esc_html__( 'Province/State', 'auto-moto-stock' ) ),
					'car_neighborhood' => $this->add_narrow_taxonomy( 'car-neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ),
					'car_label'        => $this->add_narrow_taxonomy( 'car-label', esc_html__( 'Label', 'auto-moto-stock' ) ),
					'el_class'              => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_type']            = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
				'attr'  => array(
					'car-type' => $this->add_narrow_car_type(),
					'type_image'    => array(
						'type'  => 'image',
						'title' => esc_html__( 'Upload Type Image', 'auto-moto-stock' ),
						'value' => '',
						'desc'  => esc_html__( 'Upload the custom image.', 'auto-moto-stock' )
					),
					'image_size'    => array(
						'type'  => 'text',
						'title' => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'value' => 'full',
						'desc'  => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 200x100 (Not Include Unit, Space)).', 'auto-moto-stock' )
					),
					'el_class'      => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_map']             = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Map', 'auto-moto-stock' ),
				'attr'  => array(
					'map_style'   => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Map Style', 'auto-moto-stock' ),
						'values'  => array(
							'normal'   => esc_html__( 'Normal', 'auto-moto-stock' ),
							'car' => esc_html__( 'Single Vehicle', 'auto-moto-stock' )
						),
						'default' => 'car'
					),
					'icon'        => array(
						'type'  => 'image',
						'title' => esc_html__( 'Marker Icon', 'auto-moto-stock' ),
						'value' => '',
						'desc'  => esc_html__( 'Choose an image from media library.', 'auto-moto-stock' ),
					),
					'lat'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Latitude ', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'map_style', 'value' => 'normal' )
					),
					'lng'         => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Longitude ', 'auto-moto-stock' ),
						'value'    => '',
						'required' => array( 'element' => 'map_style', 'value' => 'normal' )
					),
					'car_id' => array(
						'title'    => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
						'type'     => 'text',
						'value'    => '',
						'required' => array( 'element' => 'map_style', 'value' => 'car' )
					),
					'map_height'  => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Map height (px or %)', 'auto-moto-stock' ),
						'default' => '500px'
					),
					'el_class'    => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_car_advanced_search'] = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Advanced Search', 'auto-moto-stock' ),
				'attr'  => array(
					'layout'                   => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'values' => array(
							'tab'     => esc_html__( 'Tab With Vehicle Status', 'auto-moto-stock' ),
							'default' => esc_html__( 'Form', 'auto-moto-stock' ),
						),
					),
					'column'                   => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Column', 'auto-moto-stock' ),
						'values' => array(
							'1' => esc_html__( '1', 'auto-moto-stock' ),
							'2' => esc_html__( '2', 'auto-moto-stock' ),
							'3' => esc_html__( '3', 'auto-moto-stock' ),
							'4' => esc_html__( '4', 'auto-moto-stock' )
						),
					),
					'status_enable'            => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Status', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'type_enable'              => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'title_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Title', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'address_enable'           => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Address', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'country_enable'           => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Country', 'auto-moto-stock' ),
					),
					'state_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Province/State', 'auto-moto-stock' ),
					),
					'city_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'City/Town', 'auto-moto-stock' ),
					),
					'neighborhood_enable'      => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Neighborhood', 'auto-moto-stock' ),
					),
					'doors_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Doors', 'auto-moto-stock' ),
					),
					'seats_enable'          => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Seats', 'auto-moto-stock' ),
					),
					'owners_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Owners', 'auto-moto-stock' ),
					),
					'price_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Price', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'price_is_slider'          => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'price_enable', 'value' => 'true' )
					),
					'mileage_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Mileage', 'auto-moto-stock' ),
					),
					'mileage_is_slider'           => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'mileage_enable', 'value' => 'true' )
					),
					'power_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Power', 'auto-moto-stock' ),
					),
					'power_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'power_enable', 'value' => 'true' )
					),
					'volume_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Cubic Capacity', 'auto-moto-stock' ),
					),
					'volume_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'volume_enable', 'value' => 'true' )
					),
					'label_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Label', 'auto-moto-stock' ),
					),
					'car_identity_enable' => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
					),
					'other_stylings_enable'    => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Other Styling', 'auto-moto-stock' ),
					),
					'color_scheme'             => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'desc'   => esc_html__( 'Select color scheme for form search', 'auto-moto-stock' ),
						'values' => array(
							'color-dark'  => esc_html__( 'Dark', 'auto-moto-stock' ),
							'color-light' => esc_html__( 'Light', 'auto-moto-stock' )
						),
					),
					'el_class'                 => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					),
				)
			);
			$amotos_shortcodes['amotos_car_search']          = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Search', 'auto-moto-stock' ),
				'attr'  => array(
					'search_styles'            => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Search Form Style', 'auto-moto-stock' ),
						'desc'   => esc_html__( 'Select one in styles below for search form. Almost, you should use layout full-width for search form to can display it best', 'auto-moto-stock' ),
						'values' => array(
							'style-default'       => esc_html__( 'Form Default ', 'auto-moto-stock' ),
							'style-default-small' => esc_html__( 'Form Default Small ', 'auto-moto-stock' ),
							'style-mini-line'     => esc_html__( 'Mini Inline', 'auto-moto-stock' ),
							'style-absolute'      => esc_html__( 'Form Absolute Map ', 'auto-moto-stock' ),
							'style-vertical'      => esc_html__( 'Map Vertical', 'auto-moto-stock' )
						),
					),
					'show_status_tab'          => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show status tab', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Select vehicle status field like tab', 'auto-moto-stock' ),
						'default'  => 'true',
						'required' => array(
							'element' => 'search_styles',
							'value'   => array(
								'style-default',
								'style-default-small',
								'style-absolute',
								'style-vertical'
							)
						)
					),
					'status_enable'            => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Status', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'type_enable'              => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'title_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Title', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'address_enable'           => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Address', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'country_enable'           => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Country', 'auto-moto-stock' ),
					),
					'state_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Province/ ', 'auto-moto-stock' ),
					),
					'city_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'City/Town', 'auto-moto-stock' ),
					),
					'neighborhood_enable'      => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Neighborhood', 'auto-moto-stock' ),
					),
					'doors_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Doors', 'auto-moto-stock' ),
					),
					'seats_enable'          => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Seats', 'auto-moto-stock' ),
					),
					'owners_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Owners', 'auto-moto-stock' ),
					),
					'price_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Price', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'price_is_slider'          => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'price_enable', 'value' => 'true' )
					),
					'mileage_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Mileage', 'auto-moto-stock' ),
					),
					'mileage_is_slider'           => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'mileage_enable', 'value' => 'true' )
					),
					'power_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Power', 'auto-moto-stock' ),
					),
					'power_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'power_enable', 'value' => 'true' )
					),
					'volume_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Cubic Capacity', 'auto-moto-stock' ),
					),
					'volume_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'volume_enable', 'value' => 'true' )
					),
					'label_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Label', 'auto-moto-stock' ),
					),
					'car_identity_enable' => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
					),
					'other_stylings_enable'    => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Other Styling', 'auto-moto-stock' ),
					),
					'map_search_enable'        => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Map Search  Enable', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Show map and search vehicles with form and show result by map', 'auto-moto-stock' ),
						'default'  => 'true',
						'required' => array(
							'element' => 'search_styles',
							'value'   => array(
								'style-mini-line',
								'style-default',
								'style-default-small'
							)
						)
					),
					'color_scheme'             => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'desc'   => esc_html__( 'Select color scheme for form search', 'auto-moto-stock' ),
						'values' => array(
							'color-dark'  => esc_html__( 'Dark', 'auto-moto-stock' ),
							'color-light' => esc_html__( 'Light', 'auto-moto-stock' )
						),
					),
					'el_class'                 => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					),
				)
			);

			$amotos_shortcodes['amotos_car_search_map'] = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Search Map', 'auto-moto-stock' ),
				'attr'  => array(
					'show_status_tab'          => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Show status tab', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Select vehicle status field like tab', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'status_enable'            => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Status', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'type_enable'              => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'title_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Title', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'address_enable'           => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Address', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'country_enable'           => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Country', 'auto-moto-stock' ),
					),
					'state_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Province/State', 'auto-moto-stock' ),
					),
					'city_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'City/Town', 'auto-moto-stock' ),
					),
					'neighborhood_enable'      => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Neighborhood', 'auto-moto-stock' ),
					),
					'doors_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Doors', 'auto-moto-stock' ),
					),
					'seats_enable'          => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Seats', 'auto-moto-stock' ),
					),
					'owners_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Owners', 'auto-moto-stock' ),
					),
					'price_enable'             => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Price', 'auto-moto-stock' ),
						'default' => 'true',
					),
					'price_is_slider'          => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'price_enable', 'value' => 'true' )
					),
					'mileage_enable'              => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Mileage', 'auto-moto-stock' ),
					),
					'mileage_is_slider'           => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'mileage_enable', 'value' => 'true' )
					),
					'power_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Power', 'auto-moto-stock' ),
					),
					'power_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'power_enable', 'value' => 'true' )
					),
					'volume_enable'         => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Cubic Capacity', 'auto-moto-stock' ),
					),
					'volume_is_slider'      => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ),
						'required' => array( 'element' => 'volume_enable', 'value' => 'true' )
					),
					'label_enable'             => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Label', 'auto-moto-stock' ),
					),
					'car_identity_enable' => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
					),
					'other_stylings_enable'    => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Other Styling', 'auto-moto-stock' ),
					),
					'show_advanced_search_btn' => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Show Advanced Search Button', 'auto-moto-stock' ),
					),
					'item_amount'              => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '18',
					),
					'el_class'                 => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					),
				)
			);

			$amotos_shortcodes['amotos_car_mini_search'] = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Vehicle Mini Search', 'auto-moto-stock' ),
				'attr'  => array(
					'status_enable' => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Status Enable', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Check to show status search field.', 'auto-moto-stock' )
					),
					'el_class'      => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					),
				)
			);
			$amotos_shortcodes['amotos_manager']                = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Manager', 'auto-moto-stock' ),
				'attr'  => array(
					'dealer'          => $this->add_narrow_taxonomy( 'dealer', esc_html__( 'Dealer', 'auto-moto-stock' ) ),
					'layout_style'    => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'values'  => array(
							'manager-slider' => esc_html__( 'Carousel', 'auto-moto-stock' ),
							'manager-grid'   => esc_html__( 'Grid', 'auto-moto-stock' ),
							'manager-list'   => esc_html__( 'List', 'auto-moto-stock' )
						),
						'default' => 'manager-slider'
					),
					'item_amount'     => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '12'
					),
					'items'           => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Columns', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'default'  => '4',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					'image_size'      => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'desc'    => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'default' => '270x340'
					),
					'show_paging'     => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show Paging', 'auto-moto-stock' ),
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-list' )
						)
					),
					'dots'            => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show pagination control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'manager-slider' )
					),
					'nav'             => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Show navigation control', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'default'  => 'true'
					),
					'nav_position'    => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Navigation Position', 'auto-moto-stock' ),
						'values'   => array(
							'center'    => esc_html__( 'Center', 'auto-moto-stock' ),
							'top-right' => esc_html__( 'Top Right', 'auto-moto-stock' )
						),
						'default'  => 'center',
						'required' => array( 'element' => 'nav', 'value' => 'true' )
					),
					'autoplay'        => array(
						'type'     => 'checkbox',
						'title'    => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'required' => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'default'  => 'false'
					),
					'autoplaytimeout' => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Autoplay interval timeout.', 'auto-moto-stock' ),
						'default'  => 1000,
						'required' => array( 'element' => 'autoplay', 'value' => 'true' )
					),
					'items_md'        => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Desktop Small', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 1199', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '3',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					'items_sm'        => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Tablet', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 992', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '2',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					'items_xs'        => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Tablet Small', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 768', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '2',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					'items_mb'        => array(
						'type'     => 'select',
						'title'    => esc_html__( 'Items Mobile', 'auto-moto-stock' ),
						'desc'     => esc_html__( 'Browser Width < 480', 'auto-moto-stock' ),
						'values'   => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default'  => '1',
						'required' => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					'el_class'        => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_dealer']               = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Dealer', 'auto-moto-stock' ),
				'attr'  => array(
					'item_amount'        => array(
						'type'    => 'text',
						'title'   => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'default' => '6'
					),
					'show_paging'        => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Show Paging', 'auto-moto-stock' )
					),
					'include_heading'    => array(
						'type'  => 'checkbox',
						'title' => esc_html__( 'Include title', 'auto-moto-stock' )
					),
					'heading_title'      => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'heading_sub_title'  => array(
						'type'     => 'text',
						'title'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'required' => array( 'element' => 'include_heading', 'value' => 'true' )
					),
					'el_class'           => array(
						'type'  => 'text',
						'title' => esc_html__( 'Extra class name', 'auto-moto-stock' ),
						'desc'  => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			);
			$amotos_shortcodes['amotos_login']                = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Login', 'auto-moto-stock' ),
				'attr'  => array(
					'redirect' => array(
						'type'   => 'select',
						'title'  => esc_html__( 'Redirect Page', 'auto-moto-stock' ),
						'desc'   => esc_html__( 'After Login Redirect Page.', 'auto-moto-stock' ),
						'values' => array(
							'my_profile'   => esc_html__( 'My Profile', 'auto-moto-stock' ),
							'current_page' => esc_html__( 'Current Page', 'auto-moto-stock' )
						)
					)
				)
			);
			$amotos_shortcodes['amotos_register']             = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Register', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_profile']              = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Profile', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_reset_password']       = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Reset Password', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_my_invoices']          = array(
				'type'  => 'custom',
				'title' => esc_html__( 'My Invoice', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_package']              = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Package', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_my_cars']        = array(
				'type'  => 'custom',
				'title' => esc_html__( 'My Vehicles', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_submit_car']      = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Submit Vehicle', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_my_favorites']         = array(
				'type'  => 'custom',
				'title' => esc_html__( 'My Favorites', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_advanced_search']      = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Advanced Search Page', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_compare']              = array(
				'type'  => 'custom',
				'title' => esc_html__( 'Compare', 'auto-moto-stock' )
			);
			$amotos_shortcodes['amotos_my_save_search']       = array(
				'type'  => 'custom',
				'title' => esc_html__( 'My Saved Search', 'auto-moto-stock' )
			);
            $amotos_shortcodes['amotos_nearby_places'] = array(
                'type'  => 'custom',
                'title' => esc_html__( 'NearBy Places', 'auto-moto-stock' ),
                'attr'  => array(
                    'lat'           => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Latitude', 'auto-moto-stock' ),
                        'default' => ''
                    ),
                    'lng'           => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Longitude', 'auto-moto-stock' ),
                        'default' => ''
                    ),
                    'rank_by'           => array(
                        'type'    => 'select',
                        'title'   => esc_html__( 'Rank by', 'auto-moto-stock' ),
                        'values'   => array(
                            'default' => esc_html__('Prominence', 'auto-moto-stock'),
                            'distance' => esc_html__('Distance', 'auto-moto-stock'),
                        ),
                        'default' => 'default'
                    ),
                    'radius'           => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Radius', 'auto-moto-stock' ),
                        'default' => '5000',
                        'required' => array(
                            'element' => 'rank_by',
                            'value'   => array( 'default')
                        )
                    ),
                    'distance_in'           => array(
                        'type'    => 'select',
                        'title'   => esc_html__( 'Near by places distance in', 'auto-moto-stock' ),
                        'values'   => array(
                            'm' => esc_html__('Meter', 'auto-moto-stock'),
                            'km' => esc_html__('Km', 'auto-moto-stock'),
                            'mi' => esc_html__('Mile', 'auto-moto-stock'),
                        ),
                        'default' => 'km'
                    ),
                    'map_height'           => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Map Height', 'auto-moto-stock' ),
                        'default' => 475,
                    ),
                    'el_class'           => array(
                        'type'    => 'text',
                        'title'   => esc_html__( 'Extra class name', 'auto-moto-stock' ),
                        'default' => '',
                    ),
                )
            );

			amotos_get_admin_template('/includes/insert-shortcode/templates/shortcode-popup.php', array(
				'amotos_shortcodes' => $amotos_shortcodes
			));
		}
	}

	/**
	 * Instantiate the AMOTOS_Insert_Shortcode class.
	 */
	AMOTOS_Insert_Shortcode::init();
}
