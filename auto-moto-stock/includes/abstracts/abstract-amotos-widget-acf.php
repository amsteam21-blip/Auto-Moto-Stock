<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( AMOTOS_PLUGIN_DIR . 'includes/widgets/acf/stock-acf.php' );

abstract class AMOTOS_Widget_Acf extends WP_Widget {
	public $widget_cssclass;
	public $widget_description;
	public $widget_id;
	public $widget_name;
	public $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => $this->widget_cssclass,
			'description' => $this->widget_description
		);
		parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );
		add_action( 'load-widgets.php', array( $this, 'enqueue_resources' ), 100 );
	}

	/**
	 * Update function.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 * @see WP_Widget->update
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		if ( ! $this->settings ) {
			return $instance;
		}
		$instance = array();
		foreach ( $new_instance['fields'] as $index => $value ) {
			foreach ( $this->settings['fields'] as $key => $setting ) {
				if ( isset( $new_instance['fields'][ $index ][ $setting['name'] ] ) ) {
					if ( current_user_can( 'unfiltered_html' ) ) {
						$instance['fields'][ $index ][ $setting['name'] ] = $value[ $setting['name'] ];
					} else {
						$instance['fields'][ $index ][ $setting['name'] ] = stripslashes( wp_filter_post_kses( addslashes( $value[ $setting['name'] ] ) ) );
					}
				}
			}
		}
		foreach ( $new_instance as $key => $value ) {
			if ( $key != 'fields' ) {
				$instance[ $key ] = $new_instance[ $key ];
			}
		}

		return $instance;
	}

	/**
	 * Form function.
	 *
	 * @param array $instance
	 *
	 * @see WP_Widget->form
	 */
	public function form( $instance ) {

		if ( ! $this->settings && ! isset( $this->settings['fields'] ) ) {
			return;
		}
		$acf_widget_fields = new SF_Acf_Widget_Fields( $this, $instance );
		$acf_widget_fields->render();
	}

	public function enqueue_resources() {
		wp_enqueue_media();
		wp_enqueue_style( AMOTOS_PLUGIN_PREFIX . 'widget-acf', AMOTOS_PLUGIN_URL . 'includes/widgets/assets/css/widget-acf.css', array(), AMOTOS_PLUGIN_VER, 'all' );
		wp_enqueue_script( AMOTOS_PLUGIN_PREFIX . 'widget-acf', AMOTOS_PLUGIN_URL . 'includes/widgets/assets/js/widget-acf.js', array(), AMOTOS_PLUGIN_VER, true );
	}
}