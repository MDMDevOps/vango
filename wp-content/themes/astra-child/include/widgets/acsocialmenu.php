<?php

class ACSocialMenu extends \WP_Widget {

	public $widget_id_base;
	public $widget_name;
	public $widget_options;
	public $control_options;

	/**
	 * Constructor, initialize the widget
	 * @param $id_base, $name, $widget_options, $control_options ( ALL optional )
	 * @since 1.0.0
	 */
	public function __construct() {
		// Construct some options
		$this->widget_id_base = 'ac_social_menu';
		$this->widget_name    = 'Jetpack Social Menu';
		$this->widget_options = array(
			'classname'   => 'ac_social_menu',
			'description' => 'Display Jetpack Social Menu' );
		// Construct parent
		parent::__construct( $this->widget_id_base, $this->widget_name, $this->widget_options );
	}

	/**
	 * Create back end form for specifying image and content
	 * @param $instance
	 * @see https://codex.wordpress.org/Function_Reference/wp_parse_args
	 * @since 1.0.0
	 */
	public function form( $instance ) {
		// // define our default values
		$defaults = array(
			'title'  => null
		);
		// // merge instance with default values
		$instance = wp_parse_args( (array)$instance, $defaults );

		printf( '<p><label for="%s">Title</label>',
			$this->get_field_name( 'title' )
		);

		printf( '<input type="text" class="widefat" id="%s" name="%s" value="%s"></p>',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $instance['title'] )
		);

		echo '<p>Choose menu in Appearance->Menus</p>';

	}

	/**
	 * Update form values
	 * @param $new_instance, $old_instance
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		// Sanitize / clean values
		$instance = array(
			'title'  => sanitize_text_field( $new_instance['title'] ),
		);
		// Return values
		return $instance;
	}

	/**
	 * Output widget on the front end
	 * @param $args, $instance
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		// Extract the widget arguments ( before_widget, after_widget, description, etc )
		extract( $args );
		// Display before widget args
		echo $before_widget;

		if ( function_exists( 'jetpack_social_menu' ) ) {
			jetpack_social_menu();
		}

		// Display after widgets args
		echo $after_widget;
	}

}