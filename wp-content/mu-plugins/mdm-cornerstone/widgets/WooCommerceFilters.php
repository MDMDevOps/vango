<?php

namespace mdm\cornerstone\Widgets;

use \mdm\cornerstone\Widget;

class WooCommerceFilters extends Widget {

	/**
	 * Root ID for all widgets of this type.
	 *
	 * @since 1.0.0
	 * @var mixed|string
	 */
	public $id_base = 'wc_filter_widget';
	/**
	 * Name for this widget type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $name = 'Woocommerce Filters';

	/**
	 * Constructor, initialize the widget
	 * @param $id_base, $name, $widget_options, $control_options ( ALL optional )
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Set options
		 */
		$this->widget_options = [
			'classname' => 'wc_filters',
			'description' => 'WooCommerce Product Filters',
			'customize_selective_refresh' => true
		];

		parent::__construct();
	}
	/**
	 * Update form values
	 * @param $new_instance, $old_instance
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = parent::update( $new_instance, $old_instance );

		$instance['tax_filters'] = [];

		$taxonomies = $this->getTaxnomies();

		foreach( $taxonomies as $name => $label ) {
			if( !isset( $instance["tax_{$name}_options"] ) ) {
				continue;
			}
			if( !is_array( $instance["tax_{$name}_options"] ) ) {
				continue;
			}
			if( !isset( $instance["tax_{$name}_options"]['enabled'] ) ) {
				continue;
			}
			if( $instance["tax_{$name}_options"]['enabled'] !== '1' ) {
				continue;
			}
			$instance['tax_filters'][$name] = [
				'label' => $instance["tax_{$name}_label"],
				'hide_empty' => isset( $instance["tax_{$name}_options"]['hide_empty'] ) ? intval(  $instance["tax_{$name}_options"]['hide_empty'] ) : false,
			];
		}

		return $instance;
	}
	/**
	 * Get fields for settings form
	 *
	 * Has to be ran late, because not all fields are available during widgets_init
	 *
	 * @return [type] [description]
	 */
	public function getFields() {
		$fields = [
			[
				'id'   => 'title',
				'type' => 'text',
				'label' => __( 'Title', 'mdm-cornerstone' ),
				'default' => '',
			],
		];

		$taxonomies = $this->getTaxnomies();

		foreach( $taxonomies as $name => $label ) {
			$fields[] = [
				'id' => "tax_{$name}_label",
				'type' => 'text',
				'label' => "{$label} Filter Title",
				'default' => $label,
			];
			$fields[] = [
				'id' => "tax_{$name}_options",
				'type' => 'checkbox-group',
				'label' => '',
				'default' => true,
				'options' => [
					'enabled' => "Enable {$label} Filter",
					'hide_empty' => 'Hide Empty'
				],
			];
		}

		return $fields;
	}

	/**
	 * Output widget on the front end
	 * @param $args, $instance
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {

		// $instance['tax_filters'] = $this->getTaxFilters( $instance );

		// if( empty( $instance['filters'] ) ) {
		// 	return;
		// }

		// Display before widget args
		echo $args['before_widget'];
		// Display Title
		if( !empty( $instance['title'] ) ) {
			$instance['title']  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			// Again check if filters cleared name, in the case of 'dont show titles' filter or something
			$instance['title']  = ( !empty( $instance['title']  ) ) ? $args['before_title'] . $instance['title']  . $args['after_title'] : '';
			// Display Title
			echo $instance['title'];
		}

		printf( '<form method="GET" action="%s" class="wc_filters">', get_permalink( get_option( 'woocommerce_shop_page_id' ) ) );

		foreach( $instance['tax_filters'] as $tax => $options ) {

			$terms = get_terms( $tax, array( 'hide_empty' => $options['hide_empty'] ) );

			if( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			$fieldgroup = $tax;

			if( strpos( $fieldgroup, 'pa_' ) === 0 ) {
				$fieldgroup = str_replace('pa_', 'filter_', $fieldgroup );
			}

			echo '<fieldgroup data-fieldgroup="%s">';

				printf( '<legend>%s</legend>', $options['label'] );

				echo '<ul class="taxfilter">';

					foreach( $terms as $term ) {

						$checked = '';

						if( isset( $_GET[$fieldgroup] ) ) {
							$checked = in_array( $term->slug, explode( ',', $_GET[$fieldgroup] ) ) ? ' checked' : '';
						}

						printf( '<li><label class="checkbox"><input type="checkbox" name="%1$s" value="%2$s"%4$s>%3$s</label></li>',
							$fieldgroup,
							$term->slug,
							$term->name,
							$checked
						);
					}

				echo '</ul>';

			echo '</fieldgroup>';
		}

		echo '<input type="submit" class="button" value="Apply Filters">';

		echo '</form>';

		// var_dump($this->getFields());

		// foreach( $instance['filters'] as $filter => $value ) {
		// 	// var_dump($filter);
		// 	$terms = get_terms( $filter, array( 'hide_empty' => true ) );

		// 	if( is_wp_error( $terms ) || empty( $terms ) ) {
		// 		continue;
		// 	}
		// 	echo '<fieldgroup>';
		// 	printf( '<legend>%s</legend>', $filter );
		// 	echo '<ul>';

		// 	foreach( $terms as $term ) {
		// 		printf( '<li><input type="checkbox" id="%1$s[]" name="%1$s[]" value="%2$s"><label>%3$s</label></li>',
		// 			$filter,
		// 			$term->term_taxonomy_id,
		// 			$term->name
		// 		);
		// 	}
		// 	echo '<ul>';
		// 	echo '</fieldgroup>';
		// }

		echo $args['after_widget'];
	}
	/**
	 * Get all WooCommerce filter
	 */
	private function getTaxnomies() {

		$tax = get_object_taxonomies( 'product' );

		$tax_list = [];

		foreach( $tax as $tax_name ) {
			/**
			 * Get tax object
			 */
			$tax_object = get_taxonomy( $tax_name );
			/**
			 * Tax we don't want/need
			 */
			if( in_array( $tax_name, ['product_type', 'product_visibility', 'product_shipping_class'] ) ) {
				continue;
			}
			/**
			 * Add to our list
			 */
			$tax_list[$tax_object->name] = $tax_object->labels->singular_name;
		}

		return $tax_list;
	}

} // end class