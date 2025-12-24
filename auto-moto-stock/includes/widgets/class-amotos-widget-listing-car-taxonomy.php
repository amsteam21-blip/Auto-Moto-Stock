<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Widget_Listing_Car_Taxonomy')) {

    class AMOTOS_Widget_Listing_Car_Taxonomy extends AMOTOS_Widget_Acf
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_cssclass = 'amotos_widget amotos_widget_listing_car_taxonomy';
            $this->widget_description = esc_html__("Display the listing vehicle taxonomy.", 'auto-moto-stock');
            $this->widget_id = 'amotos_widget_listing_car_taxonomy';
            $this->widget_name = esc_html__('AMS Listing Taxonomy', 'auto-moto-stock');
            $this->settings = array(
                'extra' => array(
                    array(
                        'name' => 'title',
                        'type' => 'text',
                        'std' => esc_html__('Vehicle Cities', 'auto-moto-stock'),
                        'title' => esc_html__('Title:', 'auto-moto-stock')
                    ),
                    array(
                        'name' => 'taxonomy',
                        'type' => 'select',
                        'title' => esc_html__('Select Taxonomy:', 'auto-moto-stock'),
                        'std' => 'type',
                        'options' => array(
                            'type' => esc_html__( 'Type', 'auto-moto-stock' ),
                            'styling' => esc_html__( 'Styling', 'auto-moto-stock' ),
                            'status' => esc_html__( 'Status', 'auto-moto-stock' ),
                            'label' => esc_html__( 'Label', 'auto-moto-stock' ),
                            'state' => esc_html__( 'Province/State', 'auto-moto-stock' ),
                            'city' => esc_html__( 'City/Town', 'auto-moto-stock' ),
                            'neighborhood' => esc_html__( 'Neighborhood', 'auto-moto-stock' )
                        )
                    ),
                    array(
                        'name' => 'types',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Types:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-type'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('type'))
                    ),
                    array(
                        'name' => 'status',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Status:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-status'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('status'))
                    ),
                    array(
                        'name' => 'cities',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Cities:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-city'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('city'))
                    ),
                    array(
                        'name' => 'stylings',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Styling:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-styling'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('styling'))
                    ),
                    array(
                        'name' => 'neighborhoods',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Neighborhoods:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-neighborhood'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('neighborhood'))
                    ),
                    array(
                        'name' => 'states',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Province/State:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-state'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('state'))
                    ),
                    array(
                        'name' => 'labels',
                        'type' => 'select',
                        'multiple'=>true,
                        'title' => esc_html__('Select Labels:', 'auto-moto-stock'),
                        'options' => $this->get_all_taxonomies('car-label'),
                        'require' => array('element' => 'taxonomy', 'compare'=>'=','value' => array('label'))
                    ),
                    array(
                        'name' => 'columns',
                        'type' => 'select',
                        'title' => esc_html__('Columns:', 'auto-moto-stock'),
                        'options' => array('1'=>'1', '2'=>'2'),
                        'std' => '1'
                    ),
                    array(
                        'name' => 'show_count',
                        'type' => 'checkbox',
                        'value-inline' => true,
                        'std' => '0',
                        'title' => esc_html__('Show Count Item?', 'auto-moto-stock')
                    ),
	                array(
		                'name' => 'hide_empty',
		                'type' => 'checkbox',
		                'value-inline' => true,
		                'std' => '0',
		                'title' => esc_html__('Hide Empty Item?', 'auto-moto-stock')
	                ),
                    array(
                        'name' => 'color_scheme',
                        'type' => 'select',
                        'title' => esc_html__('Color Scheme:', 'auto-moto-stock'),
                        'options' => array(
                            'scheme-dark' => esc_html__( 'Color Light', 'auto-moto-stock' ),
                            'scheme-light' => esc_html__( 'Color Dark', 'auto-moto-stock' )
                        ),
                        'std' => 'scheme-dark'
                    )
                )
            );

            parent::__construct();
        }
        /**
         * Output widget
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $extra = array_key_exists('extra', $instance) ? $instance['extra'] : array();
            $title = array_key_exists('title', $extra) ? $extra['title'] : '';
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);
            echo wp_kses_post($args['before_widget']);
            if ( $title ) {
                echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
            }
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo amotos_get_template_html('widgets/listing-car-taxonomy/listing-car-taxonomy.php', array('extra' => $extra));

            echo wp_kses_post($args['after_widget']);
        }

        private function get_all_taxonomies($taxonomy_name)
        {
            $list_tax_item = array();
            $taxonomy_items = get_categories( array( 'taxonomy' => $taxonomy_name, 'hide_empty' => 0, 'orderby' => 'ASC' ) );
            foreach($taxonomy_items as $item){
                $list_tax_item[$item->slug] = $item->name;
            }
            return $list_tax_item;
        }
    }
}