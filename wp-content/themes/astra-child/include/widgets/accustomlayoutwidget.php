<?php

class ACCustomLayoutWidget extends \WP_Widget {

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
		$this->widget_id_base = 'ac_custom_layout';
		$this->widget_name    = 'Custom Layout';
		$this->widget_options = array(
			'classname'   => 'ac_custom_layout',
			'description' => 'Display Astra Custom Layout' );
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
			'title'  => null,
			'layout' => null,
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

		printf( '<p><label for="%s">Custom Layout</label>',
			$this->get_field_name( 'layout' )
		);


		printf( '<select class="widefat" id="%s" name="%s">',
			$this->get_field_id( 'layout' ),
			$this->get_field_name( 'layout' )
		);

		foreach( $this->get_all_layouts() as $id => $title ) {
			printf( '<option value="%d" %s>%s</option>', $id, selected( intval( $instance['layout'] ), $id, false ), $title );
		}

		echo '</select></p>';

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
			'layout' => sanitize_text_field( $new_instance['layout'] ),
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

		if( get_post_status( $instance['layout'] ) !== 'publish' ) {
			return;
		}
		// Extract the widget arguments ( before_widget, after_widget, description, etc )
		extract( $args );
		// Display before widget args
		echo $before_widget;

		if( !empty( $instance['title'] ) ) {
			$instance['title']  = apply_filters( 'widget_title', $instance['title'], $instance, $this->widget_id_base );
			// Again check if filters cleared name, in the case of 'dont show titles' filter or something
			$instance['title']  = ( !empty( $instance['title']  ) ) ? $args['before_title'] . $instance['title']  . $args['after_title'] : '';
			// Display Title
			echo $instance['title'];
		}

		// // Get the post data
		$block = get_post( $instance['layout'] );

		// Maybe use beaver builder...
		if( get_post_meta( $instance['layout'], '_fl_builder_enabled', true ) === '1' && class_exists( 'FLBuilder' ) ) {
			\FLBuilder::render_query( array(
			    'post_type' => 'astra-advanced-hook',
			    'p'         => $instance['layout'],
			) );
		}
		// Else default behavior
		else {
			echo apply_filters( 'the_content', $block->post_content );
		}

		// Display after widgets args
		echo $after_widget;
	}

	private function get_all_layouts() {
		$layouts = array();

		$args = array(
			'post_type'              => array( 'astra-advanced-hook' ),
			'post_status'            => array( 'publish' ),
			'nopaging'               => true,
			'posts_per_page'         => '-1',
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

			$layouts[$query->post->ID] = $query->post->post_title;

		endwhile; endif;

		wp_reset_postdata();

		return $layouts;
	}

}