<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'AMOTOS_Vc_map' ) ) {
	/**
	 * Class AMOTOS_Vc_map
	 */
	class AMOTOS_Vc_map {

		/**
		 * Register vc_map if visual composer activated
		 */
		public function register_vc_map() {
			if ( ! function_exists( 'vc_map' ) ) {
				return;
			}
			vc_map( array(
				'name'     => esc_html__( 'Vehicle', 'auto-moto-stock' ),
				'base'     => 'amotos_car',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'param_name'  => 'layout_style',
						'admin_label' => true,
						'value'       => array(
							esc_html__( 'Grid', 'auto-moto-stock' )     => 'car-grid',
							esc_html__( 'List', 'auto-moto-stock' )     => 'car-list',
							esc_html__( 'Zig Zac', 'auto-moto-stock' )  => 'car-zigzac',
							esc_html__( 'Carousel', 'auto-moto-stock' ) => 'car-carousel',
						),
						'std'         => 'car-grid',
						'description' => esc_html__( 'Select Layout Style.', 'auto-moto-stock' )
					),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-type', 'car_type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-status', 'car_status', esc_html__( 'Status', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-styling', 'car_styling', esc_html__( 'Styling', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-city', 'car_city', esc_html__( 'City/Town', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-state', 'car_state', esc_html__( 'Province/State', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-neighborhood', 'car_neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-label', 'car_label', esc_html__( 'Label', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'param_name'       => 'car_featured',
						'std'              => 'false',
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name'       => 'item_amount',
						'std'              => '6',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns', 'auto-moto-stock' ),
						'param_name'       => 'columns',
						'value'            => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'std'              => '3',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Desktop Small', 'auto-moto-stock' ),
						'param_name'       => 'items_md',
						'description'      => esc_html__( 'Browser Width < 1199', 'auto-moto-stock' ),
						'value'            => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '3',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Tablet', 'auto-moto-stock' ),
						'param_name'       => 'items_sm',
						'description'      => esc_html__( 'Browser Width < 992', 'auto-moto-stock' ),
						'value'            => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '2',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Tablet Small', 'auto-moto-stock' ),
						'param_name'       => 'items_xs',
						'description'      => esc_html__( 'Browser Width < 768', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '1',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Mobile', 'auto-moto-stock' ),
						'param_name'       => 'items_mb',
						'description'      => esc_html__( 'Browser Width < 480', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '1',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 , Zic Zac: 290x270 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size',
						'std'              => amotos_get_loop_car_image_size_default(),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'param_name'       => 'columns_gap',
						'value'            => array(
							'0px'  => 'col-gap-0',
							'10px' => 'col-gap-10',
							'20px' => 'col-gap-20',
							'30px' => 'col-gap-30',
						),
						'std'              => 'col-gap-30',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-grid', 'car-carousel' )
						)
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'View All Link', 'auto-moto-stock' ),
						'param_name' => 'view_all_link',
						'value'      => '',
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Paging', 'auto-moto-stock' ),
						'param_name'       => 'show_paging',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element'            => 'layout_style',
							'value_not_equal_to' => 'car-carousel'
						),
					),
					array(
						'param_name'       => 'include_heading',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Include Heading', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'param_name' => 'heading_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'param_name' => 'heading_sub_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Pagination Control', 'auto-moto-stock' ),
						'param_name'       => 'dots',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'car-carousel' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Navigation Control', 'auto-moto-stock' ),
						'param_name'       => 'nav',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'car-carousel' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Heading Contain Navigation Bar', 'auto-moto-stock' ),
						'param_name'       => 'move_nav',
						'dependency'       => array( 'element' => 'nav', 'value' => 'true' ),
						'std'              => 'false',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Position', 'auto-moto-stock' ),
						'param_name'       => 'nav_position',
						'value'            => array(
							esc_html__( 'Middle Center', 'auto-moto-stock' ) => '',
							esc_html__( 'Top Right', 'auto-moto-stock' )     => 'top-right',
							esc_html__( 'Bottom Center', 'auto-moto-stock' ) => 'bottom-center',
						),
						'std'              => '',
						'dependency'       => array( 'element' => 'move_nav', 'value_not_equal_to' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'param_name'       => 'autoplay',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'car-carousel' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'param_name'       => 'autoplaytimeout',
						'description'      => esc_html__( 'Autoplay interval timeout.', 'auto-moto-stock' ),
						'value'            => '',
						'std'              => 1000,
						'dependency'       => array( 'element' => 'autoplay', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'paged',
						'value'      => '1',
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'author_id',
						'value'      => '',
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'manager_id',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Carousel with Left Navigation', 'auto-moto-stock' ),
				'base'     => 'amotos_car_carousel',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name' => 'item_amount',
						'std'        => '6'
					),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-type', 'car_type', esc_html__( 'Type', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-status', 'car_status', esc_html__( 'Status', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-styling', 'car_styling', esc_html__( 'Styling', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-city', 'car_city', esc_html__( 'City/Town', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-state', 'car_state', esc_html__( 'Province/State', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-neighborhood', 'car_neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-label', 'car_label', esc_html__( 'Label', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'param_name'       => 'car_featured',
						'std'              => 'false',
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description' => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'  => 'image_size',
						'std'         => amotos_get_loop_car_image_size_default()
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'param_name'       => 'columns_gap',
						'value'            => array(
							'0px'  => 'col-gap-0',
							'10px' => 'col-gap-10',
							'20px' => 'col-gap-20',
							'30px' => 'col-gap-30',
						),
						'std'              => 'col-gap-0',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'param_name'       => 'color_scheme',
						'value'            => array(
							esc_html__( 'Dark', 'auto-moto-stock' )  => 'color-dark',
							esc_html__( 'Light', 'auto-moto-stock' ) => 'color-light'
						),
						'std'              => 'color-dark',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'param_name'       => 'include_heading',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Include Heading', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'param_name' => 'heading_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'param_name' => 'heading_sub_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Slider', 'auto-moto-stock' ),
				'base'     => 'amotos_car_slider',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'param_name'  => 'layout_style',
						'admin_label' => true,
						'value'       => array(
							esc_html__( 'Navigation Middle', 'auto-moto-stock' )   => 'navigation-middle',
							esc_html__( 'Pagination as Image', 'auto-moto-stock' ) => 'pagination-image'
						),
						'std'         => 'navigation-middle'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name' => 'item_amount',
						'std'        => '6'
					),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-type', 'car_type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-status', 'car_status', esc_html__( 'Status', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-styling', 'car_styling', esc_html__( 'Styling', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-city', 'car_city', esc_html__( 'City/Town', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-state', 'car_state', esc_html__( 'Province/State', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-neighborhood', 'car_neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-label', 'car_label', esc_html__( 'Label', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'param_name'       => 'car_featured',
						'std'              => 'false',
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description' => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 1200x600 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'  => 'image_size',
						'std'         => '1200x600'
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Gallery', 'auto-moto-stock' ),
				'base'     => 'amotos_car_gallery',
				'class'    => '',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Display Carousel?', 'auto-moto-stock' ),
						'param_name'       => 'is_carousel',
						'admin_label'      => true,
						'std'              => false,
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'param_name'       => 'color_scheme',
						'value'            => array(
							esc_html__( 'Dark', 'auto-moto-stock' )  => 'color-dark',
							esc_html__( 'Light', 'auto-moto-stock' ) => 'color-light'
						),
						'std'              => 'color-dark',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'param_name'       => 'category_filter',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Category Filter', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Filter Style', 'auto-moto-stock' ),
						'param_name'       => 'filter_style',
						'value'            => array(
							esc_html__( 'Isotope', 'auto-moto-stock' ) => 'filter-isotope',
							esc_html__( 'Ajax', 'auto-moto-stock' )    => 'filter-ajax'
						),
						'description'      => esc_html__( 'Not applicable for carousel', 'auto-moto-stock' ),
						'std'              => 'filter-isotope',
						'dependency'       => array( 'element' => 'category_filter', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'param_name'       => 'include_heading',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Include Heading', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array( 'element' => 'category_filter', 'value' => 'true' )
					),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-type', 'car_types', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-status', 'car_status', esc_html__( 'Status', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-styling', 'car_styling', esc_html__( 'Styling', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-city', 'car_city', esc_html__( 'City/Town', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-state', 'car_state', esc_html__( 'Province/State', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-neighborhood', 'car_neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-label', 'car_label', esc_html__( 'Label', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
						'param_name'       => 'car_featured',
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'param_name' => 'heading_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'param_name' => 'heading_sub_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name'       => 'item_amount',
						'std'              => '6',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description' => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 290x270 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'  => 'image_size',
						'std'         => '290x270'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns', 'auto-moto-stock' ),
						'param_name'       => 'columns',
						'value'            => array(
							'2' => '2',
							'3' => '3',
							'4' => '4'
						),
						'std'              => '4',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns Gap', 'auto-moto-stock' ),
						'param_name'       => 'columns_gap',
						'value'            => array(
							'0px'  => 'col-gap-0',
							'10px' => 'col-gap-10',
							'20px' => 'col-gap-20',
							'30px' => 'col-gap-30',
						),
						'std'              => 'col-gap-0',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Pagination Control', 'auto-moto-stock' ),
						'param_name'       => 'dots',
						'dependency'       => array( 'element' => 'is_carousel', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Navigation Control', 'auto-moto-stock' ),
						'param_name'       => 'nav',
						'dependency'       => array( 'element' => 'is_carousel', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'param_name'       => 'autoplay',
						'dependency'       => array( 'element' => 'is_carousel', 'value' => 'true' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'param_name'       => 'autoplaytimeout',
						'description'      => esc_html__( 'Autoplay interval timeout.', 'auto-moto-stock' ),
						'value'            => '',
						'std'              => 1000,
						'dependency'       => array( 'element' => 'autoplay', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'car_type',
						'value'      => ''
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				),
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Featured', 'auto-moto-stock' ),
				'base'     => 'amotos_car_featured',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'param_name'  => 'layout_style',
						'value'       => array(
							esc_html__( 'List Two Columns', 'auto-moto-stock' ) => 'car-list-two-columns',
							esc_html__( 'Cities Filter', 'auto-moto-stock' )    => 'car-cities-filter',
							esc_html__( 'Single Carousel', 'auto-moto-stock' )  => 'car-single-carousel',
							esc_html__( 'Sync Carousel', 'auto-moto-stock' )    => 'car-sync-carousel',
						),
						'std'         => 'car-list-two-columns',
						'admin_label' => true,
						'description' => esc_html__( 'Select Layout Style.', 'auto-moto-stock' )
					),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-type', 'car_type', esc_html__( 'Vehicle Type', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-status', 'car_status', esc_html__( 'Status', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-styling', 'car_styling', esc_html__( 'Styling', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-city', 'car_cities', esc_html__( 'City/Town', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-state', 'car_state', esc_html__( 'Province/State', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-neighborhood', 'car_neighborhood', esc_html__( 'Neighborhood', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array_merge( $this->vc_map_add_narrow_taxonomy( 'car-label', 'car_label', esc_html__( 'Label', 'auto-moto-stock' ) ), array(
						'group'            => esc_html__( 'Filter Vehicle', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					) ),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
						'param_name'       => 'color_scheme',
						'value'            => array(
							esc_html__( 'Dark', 'auto-moto-stock' )  => 'color-dark',
							esc_html__( 'Light', 'auto-moto-stock' ) => 'color-light'
						),
						'std'              => 'color-dark',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name'       => 'item_amount',
						'std'              => '6',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 240x180 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size1',
						'std'              => '240x180',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-list-two-columns' )
						)
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 835x320 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size2',
						'std'              => '835x320',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-cities-filter' )
						)
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 570x320 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size3',
						'std'              => '570x320',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-single-carousel' )
						)
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 945x605 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size4',
						'std'              => '945x605',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'car-sync-carousel' )
						)
					),
					array(
						'param_name'       => 'include_heading',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Include Heading', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'param_name' => 'heading_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'param_name' => 'heading_sub_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Text Align', 'auto-moto-stock' ),
						'param_name'       => 'heading_text_align',
						'description'      => esc_html__( 'Select heading alignment.', 'auto-moto-stock' ),
						'value'            => array(
							esc_html__( 'Left', 'auto-moto-stock' )   => 'text-left',
							esc_html__( 'Center', 'auto-moto-stock' ) => 'text-center',
							esc_html__( 'Right', 'auto-moto-stock' )  => 'text-right',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'            => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'car_city',
						'value'      => ''
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Type', 'auto-moto-stock' ),
				'base'     => 'amotos_car_type',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array_merge( $this->vc_map_add_narrow_car_type(), array(
						'admin_label' => true
					) ),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Upload Type Image', 'auto-moto-stock' ),
						'param_name'  => 'type_image',
						'value'       => '',
						'description' => esc_html__( 'Upload the custom image.', 'auto-moto-stock' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'param_name'  => 'image_size',
						'value'       => 'full',
						'description' => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 200x100 (Not Include Unit, Space)).', 'auto-moto-stock' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Map', 'auto-moto-stock' ),
				'base'     => 'amotos_car_map',
				'icon'     => 'fa fa-map-marker',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Map Style', 'auto-moto-stock' ),
						'param_name'  => 'map_style',
						'value'       => array(
							esc_html__( 'Normal', 'auto-moto-stock' )          => 'normal',
							esc_html__( 'Single Vehicle', 'auto-moto-stock' ) => 'car'
						),
						'std'         => 'car',
						'admin_label' => true
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Marker Icon', 'auto-moto-stock' ),
						'param_name'  => 'icon',
						'value'       => '',
						'description' => esc_html__( 'Choose an image from media library.', 'auto-moto-stock' ),
					),
					array(
						'heading'    => esc_html__( 'Vehicle ID', 'auto-moto-stock' ),
						'type'       => 'textfield',
						'param_name' => 'car_id',
						'value'      => '',
						'dependency' => array( 'element' => 'map_style', 'value' => 'car' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Latitude ', 'auto-moto-stock' ),
						'param_name' => 'lat',
						'value'      => '',
						'dependency' => array( 'element' => 'map_style', 'value' => 'normal' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Longitude ', 'auto-moto-stock' ),
						'param_name' => 'lng',
						'value'      => '',
						'dependency' => array( 'element' => 'map_style', 'value' => 'normal' )
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Map height (px or %)', 'auto-moto-stock' ),
						'param_name'       => 'map_height',
						'edit_field_class' => 'vc_col-sm-6',
						'std'              => '500px',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );

			vc_map( array(
				'name'     => esc_html__( 'Vehicle Search', 'auto-moto-stock' ),
				'base'     => 'amotos_car_search',
				'icon'     => 'fa fa-search',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array_merge(
					array(
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Search Form Style', 'auto-moto-stock' ),
							'param_name' => 'search_styles',
							'value'      => array(
								esc_html__( 'Form Default ', 'auto-moto-stock' )       => 'style-default',
								esc_html__( 'Form Default Small ', 'auto-moto-stock' ) => 'style-default-small',
								esc_html__( 'Mini Inline', 'auto-moto-stock' )         => 'style-mini-line',
								esc_html__( 'Form Absolute Map ', 'auto-moto-stock' )  => 'style-absolute',
								esc_html__( 'Map Vertical', 'auto-moto-stock' )        => 'style-vertical',
							),
						),
						array(
							'type'        => 'checkbox',
							'heading'     => esc_html__( 'Show status tab', 'auto-moto-stock' ),
							'description' => __( 'Select vehicle status field like tab.', 'auto-moto-stock' ),
							'param_name'  => 'show_status_tab',
							'value'       => array( esc_html__( 'Yes', 'auto-moto-stock' ) => 'true' ),
							'std'         => 'true',
							'dependency'  => array(
								'element' => 'search_styles',
								'value'   => array(
									'style-default',
									'style-default-small',
									'style-absolute',
									'style-vertical'
								)
							)
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'status_enable',
							'value'            => array( esc_html__( 'Status', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'type_enable',
							'value'            => array( esc_html__( 'Vehicle Type', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'keyword_enable',
							'value'            => array( esc_html__( 'Keyword', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'title_enable',
							'value'            => array( esc_html__( 'Title', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'address_enable',
							'value'            => array( esc_html__( 'Address', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'country_enable',
							'value'            => array( esc_html__( 'Country', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'state_enable',
							'value'            => array( esc_html__( 'Province/State', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'city_enable',
							'value'            => array( esc_html__( 'City/Town', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'neighborhood_enable',
							'value'            => array( esc_html__( 'Neighborhood', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'doors_enable',
							'value'            => array( esc_html__( 'Doors', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'seats_enable',
							'value'            => array( esc_html__( 'Seats', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'owners_enable',
							'value'            => array( esc_html__( 'Owners', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_enable',
							'value'            => array( esc_html__( 'Price', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'price_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_enable',
							'value'            => array( esc_html__( 'Mileage', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'mileage_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_enable',
							'value'            => array( esc_html__( 'Power', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'power_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_enable',
							'value'            => array( esc_html__( 'Cubic Capacity', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'volume_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'label_enable',
							'value'            => array( esc_html__( 'Label', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'car_identity_enable',
							'value'            => array( esc_html__( 'Vehicle ID', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
					),
					$this->vc_map_add_additions_search_form_fields(),
					array(
						array(
							'type'             => 'checkbox',
							'param_name'       => 'other_stylings_enable',
							'value'            => array( esc_html__( 'Other Styling', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'heading'          => esc_html__( 'Map Search  Enable', 'auto-moto-stock' ),
							'param_name'       => 'map_search_enable',
							'description'      => __( 'Show map and search vehicles with form and show result by map', 'auto-moto-stock' ),
							'value'            => array( esc_html__( 'Yes', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array(
								'element' => 'search_styles',
								'value'   => array(
									'style-mini-line',
									'style-default',
									'style-default-small'
								)
							)
						),
						array(
							'type'             => 'dropdown',
							'heading'          => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
							'param_name'       => 'color_scheme',
							'value'            => array(
								esc_html__( 'Dark', 'auto-moto-stock' )  => 'color-dark',
								esc_html__( 'Light', 'auto-moto-stock' ) => 'color-light'
							),
							'std'              => 'color-light',
							'edit_field_class' => 'vc_col-sm-6 vc_column'
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
						),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Search Map', 'auto-moto-stock' ),
				'base'     => 'amotos_car_search_map',
				'icon'     => 'fa fa-search',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array_merge(
					array(
						array(
							'type'        => 'checkbox',
							'heading'     => esc_html__( 'Show status tab', 'auto-moto-stock' ),
							'description' => __( 'Select vehicle status field like tab.', 'auto-moto-stock' ),
							'param_name'  => 'show_status_tab',
							'value'       => array( esc_html__( 'Yes', 'auto-moto-stock' ) => 'true' ),
							'std'         => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'status_enable',
							'value'            => array( esc_html__( 'Status', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'type_enable',
							'value'            => array( esc_html__( 'Vehicle Type', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'keyword_enable',
							'value'            => array( esc_html__( 'Keyword', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'title_enable',
							'value'            => array( esc_html__( 'Title', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'address_enable',
							'value'            => array( esc_html__( 'Address', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'country_enable',
							'value'            => array( esc_html__( 'Country', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'state_enable',
							'value'            => array( esc_html__( 'Province/State', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'city_enable',
							'value'            => array( esc_html__( 'City/Town', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'neighborhood_enable',
							'value'            => array( esc_html__( 'Neighborhood', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'doors_enable',
							'value'            => array( esc_html__( 'Doors', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'seats_enable',
							'value'            => array( esc_html__( 'Seats', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'owners_enable',
							'value'            => array( esc_html__( 'Owners', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_enable',
							'value'            => array( esc_html__( 'Price', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'price_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_enable',
							'value'            => array( esc_html__( 'Mileage', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'mileage_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_enable',
							'value'            => array( esc_html__( 'Power', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'power_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_enable',
							'value'            => array( esc_html__( 'Cubic Capacity', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'volume_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'label_enable',
							'value'            => array( esc_html__( 'Label', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'car_identity_enable',
							'value'            => array( esc_html__( 'Vehicle ID', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
					),
					$this->vc_map_add_additions_search_form_fields(),
					array(
						array(
							'type'             => 'checkbox',
							'param_name'       => 'other_stylings_enable',
							'value'            => array( esc_html__( 'Other Styling', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'show_advanced_search_btn',
							'value'            => array( esc_html__( 'Show Advanced Search Button', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Items Amount', 'auto-moto-stock' ),
							'param_name'       => 'item_amount',
							'std'              => '18',
							'edit_field_class' => 'vc_col-sm-6 vc_column'
						),
						array(
							'type'             => 'textfield',
							'heading'          => esc_html__( 'Marker Vehicle Image Size', 'auto-moto-stock' ),
							'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 100x100 (Not Include Unit, Space)).', 'auto-moto-stock' ),
							'param_name'       => 'marker_image_size',
							'std'              => '100x100',
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
						),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Vehicle Advanced Search', 'auto-moto-stock' ),
				'base'     => 'amotos_car_advanced_search',
				'icon'     => 'fa fa-search',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array_merge(
					array(
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Layout Style', 'auto-moto-stock' ),
							'param_name' => 'layout',
							'value'      => array(
								esc_html__( 'Status As Tab', 'auto-moto-stock' )      => 'tab',
								esc_html__( 'Status As Dropdown', 'auto-moto-stock' ) => 'dropdown',
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Column', 'auto-moto-stock' ),
							'param_name' => 'column',
							'value'      => array(
								esc_html__( '1', 'auto-moto-stock' ) => '1',
								esc_html__( '2', 'auto-moto-stock' ) => '2',
								esc_html__( '3', 'auto-moto-stock' ) => '3',
								esc_html__( '4', 'auto-moto-stock' ) => '4'
							),
							'std'        => '3',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'status_enable',
							'value'            => array( esc_html__( 'Status', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'type_enable',
							'value'            => array( esc_html__( 'Vehicle Type', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'keyword_enable',
							'value'            => array( esc_html__( 'Keyword', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'title_enable',
							'value'            => array( esc_html__( 'Title', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'address_enable',
							'value'            => array( esc_html__( 'Address', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'country_enable',
							'value'            => array( esc_html__( 'Country', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'state_enable',
							'value'            => array( esc_html__( 'Province/State', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'city_enable',
							'value'            => array( esc_html__( 'City/Town', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'neighborhood_enable',
							'value'            => array( esc_html__( 'Neighborhood', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'doors_enable',
							'value'            => array( esc_html__( 'Doors', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'seats_enable',
							'value'            => array( esc_html__( 'Seats', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'owners_enable',
							'value'            => array( esc_html__( 'Owners', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_enable',
							'value'            => array( esc_html__( 'Price', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'std'              => 'true',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'price_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Price?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'price_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_enable',
							'value'            => array( esc_html__( 'Mileage', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'mileage_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Mileage?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'mileage_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_enable',
							'value'            => array( esc_html__( 'Power', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'power_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Power?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'power_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_enable',
							'value'            => array( esc_html__( 'Cubic Capacity', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'volume_is_slider',
							'value'            => array( esc_html__( 'Show Slider for Cubic Capacity?', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
							'dependency'       => array( 'element' => 'volume_enable', 'value' => 'true' )
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'label_enable',
							'value'            => array( esc_html__( 'Label', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'car_identity_enable',
							'value'            => array( esc_html__( 'Vehicle ID', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),

					),
					$this->vc_map_add_additions_search_form_fields(),
					array(
						array(
							'type'             => 'checkbox',
							'param_name'       => 'other_stylings_enable',
							'value'            => array( esc_html__( 'Other Styling', 'auto-moto-stock' ) => 'true' ),
							'edit_field_class' => 'vc_col-sm-6 vc_column',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Color Scheme', 'auto-moto-stock' ),
							'param_name' => 'color_scheme',
							'value'      => array(
								esc_html__( 'Dark', 'auto-moto-stock' )  => 'color-dark',
								esc_html__( 'Light', 'auto-moto-stock' ) => 'color-light'
							),
							'std'        => 'color-light',
						),
						array(
							'type'        => 'textfield',
							'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
						),
					)
				)
			) );

			vc_map( array(
				'name'     => esc_html__( 'Vehicle Mini Search', 'auto-moto-stock' ),
				'base'     => 'amotos_car_mini_search',
				'icon'     => 'fa fa-search',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'             => 'checkbox',
						'description'      => __( 'Check to show status search field.', 'auto-moto-stock' ),
						'param_name'       => 'status_enable',
						'value'            => array( esc_html__( 'Status', 'auto-moto-stock' ) => 'true' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					),
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Manager', 'auto-moto-stock' ),
				'base'     => 'amotos_manager',
				'icon'     => 'fa fa-user-plus',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					$this->vc_map_add_narrow_taxonomy( 'dealer', 'dealer', esc_html__( 'Dealer', 'auto-moto-stock' ) ),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Layout Style', 'auto-moto-stock' ),
						'param_name'  => 'layout_style',
						'value'       => array(
							esc_html__( 'Carousel', 'auto-moto-stock' ) => 'manager-slider',
							esc_html__( 'Grid', 'auto-moto-stock' )     => 'manager-grid',
							esc_html__( 'List', 'auto-moto-stock' )     => 'manager-list',
						),
						'std'         => 'manager-slider',
						'admin_label' => true,
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name'       => 'item_amount',
						'std'              => '12',
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Columns', 'auto-moto-stock' ),
						'param_name'       => 'items',
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'std'              => '4',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' )
						)
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Image Size', 'auto-moto-stock' ),
						'description'      => esc_html__( 'Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space)).', 'auto-moto-stock' ),
						'param_name'       => 'image_size',
						'std'              => '270x340',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Paging', 'auto-moto-stock' ),
						'param_name'       => 'show_paging',
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-list' )
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show pagination control', 'auto-moto-stock' ),
						'param_name'       => 'dots',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show navigation control', 'auto-moto-stock' ),
						'param_name'       => 'nav',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Navigation Position', 'auto-moto-stock' ),
						'param_name'       => 'nav_position',
						'value'            => array(
							esc_html__( 'Center', 'auto-moto-stock' )    => 'center',
							esc_html__( 'Top Right', 'auto-moto-stock' ) => 'top-right',
						),
						'std'              => 'center',
						'dependency'       => array( 'element' => 'nav', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Auto play', 'auto-moto-stock' ),
						'param_name'       => 'autoplay',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'std'              => 'true',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Autoplay Timeout', 'auto-moto-stock' ),
						'param_name'       => 'autoplaytimeout',
						'description'      => esc_html__( 'Autoplay interval timeout.', 'auto-moto-stock' ),
						'value'            => '',
						'std'              => 1000,
						'dependency'       => array( 'element' => 'autoplay', 'value' => 'true' ),
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Loop', 'auto-moto-stock' ),
						'param_name'       => 'loop',
						'dependency'       => array( 'element' => 'layout_style', 'value' => 'manager-slider' ),
						'std'              => '',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
						'group'            => esc_html__( 'Carousel Options', 'auto-moto-stock' )
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Desktop Small', 'auto-moto-stock' ),
						'param_name'       => 'items_md',
						'description'      => esc_html__( 'Browser Width < 1199', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '3',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' ),
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Tablet', 'auto-moto-stock' ),
						'param_name'       => 'items_sm',
						'description'      => esc_html__( 'Browser Width < 992', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '2',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' ),
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Tablet Small', 'auto-moto-stock' ),
						'param_name'       => 'items_xs',
						'description'      => esc_html__( 'Browser Width < 768', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '2',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' ),
						),
					),
					array(
						'type'             => 'dropdown',
						'heading'          => esc_html__( 'Items Mobile', 'auto-moto-stock' ),
						'param_name'       => 'items_mb',
						'description'      => esc_html__( 'Browser Width < 480', 'auto-moto-stock' ),
						'value'            => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'std'              => '1',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'group'            => esc_html__( 'Responsive', 'auto-moto-stock' ),
						'dependency'       => array(
							'element' => 'layout_style',
							'value'   => array( 'manager-grid', 'manager-slider' ),
						),
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'paged',
						'value'      => '1',
					),
					array(
						'type'       => 'hidden',
						'param_name' => 'post_not_in',
						'value'      => ''
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Dealer', 'auto-moto-stock' ),
				'base'     => 'amotos_dealer',
				'icon'     => 'fa fa-group',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Items Amount', 'auto-moto-stock' ),
						'param_name' => 'item_amount',
						'std'        => '6'
					),
					array(
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Show Paging', 'auto-moto-stock' ),
						'param_name'       => 'show_paging',
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'param_name'       => 'include_heading',
						'type'             => 'checkbox',
						'heading'          => esc_html__( 'Include Heading', 'auto-moto-stock' ),
						'edit_field_class' => 'vc_col-sm-6 vc_column'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'auto-moto-stock' ),
						'param_name' => 'heading_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sub Title', 'auto-moto-stock' ),
						'param_name' => 'heading_sub_title',
						'value'      => '',
						'dependency' => array( 'element' => 'include_heading', 'value' => 'true' ),
						'group'      => esc_html__( 'Heading Options', 'auto-moto-stock' )
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'auto-moto-stock' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
					)
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Login', 'auto-moto-stock' ),
				'base'     => 'amotos_login',
				'icon'     => 'fa fa-user',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Redirect Page', 'auto-moto-stock' ),
						'param_name'  => 'redirect',
						'description' => esc_html__( 'After Login Redirect Page.', 'auto-moto-stock' ),
						'value'       => array(
							esc_html__( 'My Profile', 'auto-moto-stock' )   => 'my_profile',
							esc_html__( 'Current Page', 'auto-moto-stock' ) => 'current_page',
						),
					),
				)
			) );
			vc_map( array(
				'name'     => esc_html__( 'Register', 'auto-moto-stock' ),
				'base'     => 'amotos_register',
				'icon'     => 'fa fa-user-plus',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Profile', 'auto-moto-stock' ),
				'base'     => 'amotos_profile',
				'icon'     => 'fa fa-user',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Reset Password', 'auto-moto-stock' ),
				'base'     => 'amotos_reset_password',
				'icon'     => 'fa fa-refresh',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'My Invoice', 'auto-moto-stock' ),
				'base'     => 'amotos_my_invoices',
				'icon'     => 'fa fa-list',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Package', 'auto-moto-stock' ),
				'base'     => 'amotos_package',
				'icon'     => 'fa fa-list-alt',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'My Vehicles', 'auto-moto-stock' ),
				'base'     => 'amotos_my_cars',
				'icon'     => 'fa fa-th',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Submit Vehicle', 'auto-moto-stock' ),
				'base'     => 'amotos_submit_car',
				'icon'     => 'fa fa-newspaper-o',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'My Favorites', 'auto-moto-stock' ),
				'base'     => 'amotos_my_favorites',
				'icon'     => 'fa fa-star',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Advanced Search Page', 'auto-moto-stock' ),
				'base'     => 'amotos_advanced_search',
				'icon'     => 'fa fa-search-plus',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'Compare', 'auto-moto-stock' ),
				'base'     => 'amotos_compare',
				'icon'     => 'fa fa-balance-scale',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );
			vc_map( array(
				'name'     => esc_html__( 'My Saved Search', 'auto-moto-stock' ),
				'base'     => 'amotos_my_save_search',
				'icon'     => 'fa fa-save',
				'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' )
			) );

            vc_map( array(
                'name'     => esc_html__( 'Nearby Places', 'auto-moto-stock' ),
                'base'     => 'amotos_nearby_places',
                'icon'     => 'fa fa-map-marker',
                'category' => esc_html__( 'AMS Shortcode', 'auto-moto-stock' ),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Latitude', 'auto-moto-stock'),
                        'param_name' => 'lat',
                        'value' => '',
                        'admin_label' => true,
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Longitude', 'auto-moto-stock'),
                        'param_name' => 'lng',
                        'value' => '',
                        'admin_label' => true,
                        'edit_field_class' => 'vc_col-sm-6 vc_column'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Rank by', 'auto-moto-stock'),
                        'param_name' => 'rank_by',
                        'value' => array(
                            esc_html__('Prominence', 'auto-moto-stock') => 'default',
                            esc_html__('Distance', 'auto-moto-stock') => 'distance'
                        ),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Radius', 'auto-moto-stock'),
                        'param_name' => 'radius',
                        'value' => '5000',
                        'dependency' => array('element' => 'rank_by', 'value' => 'default'),
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Near by places distance in', 'auto-moto-stock'),
                        'param_name' => 'distance_in',
                        'value' => array(
                            esc_html__('Meter', 'auto-moto-stock') => 'm',
                            esc_html__('Km', 'auto-moto-stock') => 'km',
                            esc_html__('Mile', 'auto-moto-stock') => 'mi',
                        ),
                        'std' => 'km',
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Map Height', 'auto-moto-stock'),
                        'param_name' => 'map_height',
                        'value' => 475,
                        'edit_field_class' => 'vc_col-sm-6 vc_column',
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => esc_html__( 'Extra class name', 'auto-moto-stock' ),
                        'param_name'  => 'el_class',
                        'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'auto-moto-stock' ),
                    )
                )
            ));
		}

		/**
		 * List type of vehicle
		 * @return array
		 */
		private function vc_map_add_narrow_car_type() {
			$type  = array();
			$types = get_categories( array( 'taxonomy' => 'car-type', 'hide_empty' => 0, 'orderby' => 'ASC' ) );
			if ( is_array( $types ) ) {
				foreach ( $types as $st ) {
					$type[ $st->name ] = $st->slug;
				}
			}

			return array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Narrow Type', 'auto-moto-stock' ),
				'param_name'  => 'car_type',
				'admin_label' => true,
				'value'       => $type,
				'std'         => '',
				'description' => esc_html__( 'Enter type by names to narrow output.', 'auto-moto-stock' )
			);
		}

		/**
		 * List taxonomy as selectize
		 *
		 * @param $taxonomy
		 * @param $param_name
		 * @param $heading
		 *
		 * @return array
		 */
		private function vc_map_add_narrow_taxonomy( $taxonomy, $param_name, $heading ) {
			$taxonomies   = array();
			$taxonomy_arr = get_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'orderby' => 'ASC' ) );
			if ( is_array( $taxonomy_arr ) ) {
				foreach ( $taxonomy_arr as $tx ) {
					$taxonomies[ $tx->name ] = $tx->slug;
				}
			}

			return array(
				'type'        => 'amotos_selectize',
                /* translators: %s: taxonomy title */
				'heading'     => sprintf( esc_html__( 'Narrow %s', 'auto-moto-stock' ), esc_html( $heading ) ),
				'param_name'  => $param_name,
				'value'       => $taxonomies,
				'multiple'    => true,
				'std'         => '',
                /* translators: %s: taxonomy title */
				'description' => sprintf( esc_html__( 'Enter %s by names to narrow output.', 'auto-moto-stock' ), esc_html( $heading ) )
			);
		}

		private function vc_map_add_additions_search_form_fields() {
			$config            = array();
			$additional_fields = amotos_get_search_additional_fields();
			foreach ( $additional_fields as $k => $v ) {
				$config[] = array(
					'type'             => 'checkbox',
					'param_name'       => "{$k}_enable",
					'value'            => array( $v => 'true' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'std'              => '',
				);
			}

			return $config;

		}
	}
}