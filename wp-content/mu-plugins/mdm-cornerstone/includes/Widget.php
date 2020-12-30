<?php
/**
 * Widget parent file
 *
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_cornerstone
 */

namespace mdm\cornerstone;

class Widget extends \WP_Widget {
	/**
	 * Root ID for all widgets of this type.
	 *
	 * @since 1.0.0
	 * @var mixed|string
	 */
	public $id_base = '';
	/**
	 * Name for this widget type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $name = '';
	/**
	 * Option array passed to wp_register_sidebar_widget().
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $widget_options = [];
	/**
	 * Option array passed to wp_register_widget_control().
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $control_options = [];
	/**
	 * Constructor, initialize the widget
	 * @method __construct
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct( $this->id_base, $this->name, $this->widget_options, $this->control_options );
	}
	/**
	 * Create back end form for specifying image and content
	 * @param $instance
	 * @see https://codex.wordpress.org/Function_Reference/wp_parse_args
	 * @since 1.0.0
	 */
	public function form( $instance ) {

		printf( '<div class="%s_widget_form mdm-widget-form" style="padding-top: 10px; padding-bototm: 10px;">', $this->id_base );

		foreach( $this->getFields() as $field => $args ) {
			/**
			 * Set value, or default
			 */
			if( !isset( $instance[ $args['id'] ] ) ) {
				$instance[ $args['id'] ] = $args['default'];
			}

			echo '<div class="field" style="margin-bottom: 14px;">';

				$this->input( $args, $instance[ $args['id'] ] );

				if( isset( $args['description'] ) && !empty( $args['description'] ) ) {

					printf( '<p class="description">%s</p>', esc_attr( $args['description'] ) );

				}

			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Update form values
	 * @param $new_instance, $old_instance
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		/**
		 * Loop through each field and sanitize
		 */
		foreach( $this->getFields() as $field => $args ) {

			$instance[$args['id']] = $new_instance[$args['id']];
			/**
			 * Handline for group fields
			 */
			if( is_array( $new_instance[ $args['id'] ] ) ) {

				foreach( $new_instance[ $args['id'] ] as $index => $value ) {

					if( isset( $args['sanitize'] ) && function_exists( $args['sanitize'] ) ) {

						$instance[ $args['id'] ][$index] = call_user_func( $args['sanitize'], $value );

					}

					else {

						$instance[ $args['id'] ][$index] = sanitize_text_field( $value  );

					}

				}

			}
			/**
			 * Handline for singular fields
			 */
			else {
				if( isset( $args['sanitize'] ) && function_exists( $args['sanitize'] ) ) {

					$instance[$args['id']] = call_user_func( $args['sanitize'], $new_instance[$args['id']] );

				}

				else {

					$instance[$args['id']] = sanitize_text_field( $new_instance[$args['id']] );

				}
			}

		}

		return $instance;
	}

	/**
	 * Output widget on the front end
	 * @param $args, $instance
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		// Display before widget args
		echo $args['before_widget'];
		// Display Title
		if( !empty( $instance['title'] ) ) {
			$instance['title']  = apply_filters( 'widget_title', $instance['title'], $instance, $this->widget_id_base );
			// Again check if filters cleared name, in the case of 'dont show titles' filter or something
			$instance['title']  = ( !empty( $instance['title']  ) ) ? $args['before_title'] . $instance['title']  . $args['after_title'] : '';
			// Display Title
			echo $instance['title'];
		}

		/**
		 * DO WIDGETY STUFF
		 */
		echo '<ul>';
		foreach( $this->getFields() as $field => $field_args ) {
			if( isset( $instance[$field_args['id']] ) ) {
				printf( '<li><strong>%s:</strong> %s</li>', $field_args['id'], esc_attr( $instance[$field_args['id']] ) );
			}
		}
		echo '</ul>';

		// Display after widgets args
		echo $args['after_widget'];
	}

	public function getFields() {
		return [];
	}

	/**
	 * Orchastrate widget form inputs
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function input( $input, $value ) {
		switch ( $input['type'] ) {
			case 'text' :
				$this->textInput( $input, $value );
				break;
			case 'textarea' :
				$this->textareaInput( $input, $value );
				break;
			case 'radio' :
				$this->radioInput( $input, $value );
				break;
			case 'checkbox' :
				$this->checkboxInput( $input, $value );
				break;
			case 'select' :
				$this->selectInput( $input, $value );
				break;
			case 'checkbox-group' :
				$this->checkboxInputGroup( $input, $value );
				break;
			default:
				// Nothing to do here...
				break;
		}
	}
	/**
	 * Output text input for widget forms
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function textInput( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label' => '',
			'class' => 'widefat',
		);
		$input = array_merge( $defaults, $input );
		/**
		 * Do label
		 */
		printf( '<label for="%s" style="margin-bottom: 5px; display: block;">%s</label>',
			$this->get_field_name( $input['id'] ),
			esc_attr( $input['label'] )
		);
		/**
		 * Do Input
		 */
		printf( '<input name="%s" id="%s" class="%s" type="text" value="%s"/>',
			$this->get_field_name( $input['id'] ),
			$this->get_field_id( $input['id'] ),
			$input['class'],
			esc_attr( $value )
		);
	}
	/**
	 * Output textarea for widget forms
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function textareaInput( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label' => '',
			'class' => 'widefat',
			'rows'   => 10,
			'cols'  => 30,
		);
		$input = array_merge( $defaults, $input );
		/**
		 * Do label
		 */
		printf( '<label for="%s" style="margin-bottom: 5px; display: block;">%s</label>',
			$this->get_field_name( $input['id'] ),
			esc_attr( $input['label'] )
		);
		/**
		 * Do Input
		 */
		printf( '<textarea name="%s" id="%s" class="%s" rows="%d" cols="%d">%s</textarea>',
			$this->get_field_name( $input['id'] ),
			$this->get_field_id( $input['id'] ),
			$input['class'],
			$input['rows'],
			$input['cols'],
			esc_attr( $value )
		);
	}
	/**
	 * Output radio input for widget forms
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function radioInput( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label'   => '',
			'class'   => 'widefat',
			'default' => '1',
			'options' => array(
				'1' => __( 'Option 1', 'wpcl_plugin_scaffolding' ),
				'2' => __( 'Option 2', 'wpcl_plugin_scaffolding' ),
			),
		);
		$input = array_merge( $defaults, $input );
		/**
		 * Open group
		 */
		echo '<radiogroup>';
		/**
		 * do legend
		 */
		printf( '<legend style="margin-bottom: 5px; display: block;">%s</legend>',
			$input['label']
		);
		/**
		 * Do Options
		 */
		foreach( $input['options'] as $input_value => $label ) {
			printf( '<input name="%s" id="%s" class="%s" type="radio" value="%s"%s/>',
				$this->get_field_name( $input['id'] ),
				$this->get_field_id( $input['id'] ),
				$input['class'],
				$input_value,
				checked( $value, $input_value, false )
			);
			printf( '<label for="%s">%s</label>',
				$this->get_field_name( $input['id'] ),
				esc_attr( $label )
			);
			echo '</br>';
		}
		/**
		 * Close group
		 */
		echo '</radiogroup>';
	}
	/**
	 * Output checkbox input for widget forms
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function checkboxInput( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label' => '',
			'class' => '',
			'value' => '1'
		);
		$input = array_merge( $defaults, $input );
		/**
		 * Do Input
		 */
		printf( '<input name="%s" id="%s" class="%s" type="checkbox" value="%s" %s/>',
			$this->get_field_name( $input['id'] ),
			$this->get_field_id( $input['id'] ),
			$input['class'],
			$input['value'],
			checked( $input['value'], $value, false )
		);
		/**
		 * Do label
		 */
		printf( '<label for="%s">%s</label>',
			$this->get_field_name( $input['id'] ),
			esc_attr( $input['label'] )
		);
	}

	public function checkboxInputGroup( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label' => '',
			'class' => '',
			'default' => '0',
			'options' => [],
		);
		$input = array_merge( $defaults, $input );

		echo '<fieldset>';

		printf( '<legend style="margin-bottom: 5px; display: block;">%s</legend>',
			$input['label']
		);

		foreach( $input['options'] as $option_value => $option_name ) {
			$args = [
				'id' => "{$input['id']}[$option_value]",
				'label' => $option_name
			];
			$option_value = isset( $value[$option_value] ) ? $value[$option_value] : false;

			echo '<div class="field-group-item">';

			$this->checkboxInput( $args, $option_value );

			echo '</div>';
		}

		echo '</fieldset>';
	}
	/**
	 * Output select input for widget forms
	 *
	 * @param  [array] $input : the input arguments from our field setting
	 * @param  [string] $value : the value of the input
	 */
	private function selectInput( $input, $value ) {
		/**
		 * Normalize the arguments required for this field type
		 */
		$defaults = array(
			'label' => '',
			'class' => 'widefat',
			'options' => array(
				'' => __( 'Select Option', 'wpcl_plugin_scaffolding' ),
			),
		);
		$input = array_merge( $defaults, $input );
		/**
		 * Do label
		 */
		printf( '<label for="%s" style="margin-bottom: 5px; display: block;">%s</label>',
			$this->get_field_name( $input['id'] ),
			esc_attr( $input['label'] )
		);
		/**
		 * Open select
		 */
		printf( '<select name="%s" id="%s" class="%s">',
			$this->get_field_name( $input['id'] ),
			$this->get_field_id( $input['id'] ),
			$input['class']
		);
		/**
		 * Do Options
		 */
		foreach( $input['options'] as $option_value => $label ) {
			printf( '<option value="%s"%s>%s</option>',
				$option_value,
				selected( $value, $option_value, false ),
				$label
			);
		}
		/**
		 * Close Select
		 */
		echo '</select>';
	}

}