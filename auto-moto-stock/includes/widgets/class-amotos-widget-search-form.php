<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('AMOTOS_Widget_Search_Form')) {

    class AMOTOS_Widget_Search_Form extends AMOTOS_Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_cssclass = 'amotos_widget amotos_widget_search_form';
            $this->widget_description = esc_html__("Display the search form.", 'auto-moto-stock');
            $this->widget_id = 'amotos_widget_search_form';
            $this->widget_name = esc_html__('AMS Search Form', 'auto-moto-stock');
            $this->settings = array(
                'title' => array(
                    'type' => 'text',
                    'std' => esc_html__('Search Form', 'auto-moto-stock'),
                    'label' => esc_html__('Title:', 'auto-moto-stock')
                ),
                'layout'  => array(
                    'type'    => 'select',
                    'std'     => 'tab',
                    'label'   => esc_html__( 'Source', 'auto-moto-stock' ),
                    'options' => array(
                        'tab' => esc_html__('Status As Tab','auto-moto-stock'),
                        'dropdown' => esc_html__('Status As Dropdown','auto-moto-stock')
                    )
                ),
                'status_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Status', 'auto-moto-stock')
                ),
                'type_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Vehicle Type', 'auto-moto-stock')
                ),
                'title_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Title', 'auto-moto-stock')
                ),
                'address_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Address', 'auto-moto-stock')
                ),
                'country_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Country', 'auto-moto-stock')
                ),
                'state_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Province/State', 'auto-moto-stock')
                ),
                'city_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('City/Town', 'auto-moto-stock')
                ),
                'neighborhood_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Neighborhood', 'auto-moto-stock')
                ),
                'doors_enable' => array(
	                'type' => 'checkbox',
	                'std' => false,
	                'label' => esc_html__('Doors', 'auto-moto-stock')
                ),

                'seats_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Seats', 'auto-moto-stock')
                ),
                'owners_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Owners', 'auto-moto-stock')
                ),
                'price_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Price', 'auto-moto-stock')
                ),
                'price_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Price?', 'auto-moto-stock')
                ),
                'mileage_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Mileage', 'auto-moto-stock')
                ),
                'mileage_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Mileage?', 'auto-moto-stock')
                ),
                'power_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Power', 'auto-moto-stock')
                ),
                'power_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Power?', 'auto-moto-stock')
                ),
                'volume_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Cubic Capacity', 'auto-moto-stock')
                ),
                'volume_is_slider' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Show Slider for Cubic Capacity?', 'auto-moto-stock')
                ),
                'label_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Label', 'auto-moto-stock')
                ),
                'car_identity_enable' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Vehicle ID', 'auto-moto-stock')
                ),
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
            $this->widget_start($args, $instance);
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo amotos_get_template_html('widgets/search-form/search-form.php', array('args' => $args, 'instance' => $instance));

            $this->widget_end($args);
        }
    }
}