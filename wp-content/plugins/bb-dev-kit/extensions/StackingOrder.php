<?php
/**
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\extensions;

use \wpcl\bbdk\includes\Css;
use \wpcl\bbdk\includes\Framework;

class StackingOrder extends Framework {
	/**
	 * Register filters
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addFilters() {
		$this->subscriber->addFilter( 'fl_builder_register_settings_form', [$this, 'extendSettingsForm'] , 10, 2 );
		$this->subscriber->addFilter( 'fl_builder_render_css', [$this, 'render'], 10, 4 );
		$this->subscriber->addFilter( 'fl_builder_column_group_attributes', [$this, 'columnAttr'], 10, 2 );
	}
	/**
	 * Extend Settings Form
	 * @param  [array] $form : The settings array for the row settings form
	 * @param  [string] $id  : The id of the form
	 * @return [array]       : The (maybe) modified form
	 */
	public function extendSettingsForm( $form, $id ) {
		if( $id === 'col' ) {

			$fields = [];

			foreach( $form['tabs']['advanced']['sections']['visibility']['fields'] as $name => $setting ) {
				if( $name === 'responsive_order' ) {
					/**
					 * Set medium order
					 */
					$fields['medium_order'] = [
						'type' => 'select',
						'label' => __( 'Medium Stacking Order', 'bb_dev_kit' ),
						'help' => 'The order of the columns in this group when they are stacked for medium devices.',
						'options' => [
							'default' => 'Default',
							'reversed' => 'Reversed'
						],
						'preview' => [ 'type' => 'none' ],
					];
					/**
					 * Change name
					 */
					$setting['label'] = 'Responsive Stacking Order';
				}
				/**
				 * Set the field
				 */
				$fields[$name] = $setting;
			}

			$form['tabs']['advanced']['sections']['visibility']['fields'] = $fields;
		}
		return $form;
	}
	/**
	 * Extend Column Attributes
	 * @param  array $attrs array of attributes
	 * @param  object $group column group object
	 * @return $attrs
	 */
	public function columnAttr( $attrs, $group ) {
		$cols = \FLBuilderModel::get_nodes( 'column', $group );

		foreach ( $cols as $col ) {
			if ( isset( $col->settings->medium_order ) && 'reversed' == $col->settings->medium_order ) {
				if ( ! in_array( 'fl-col-group-medium_order-reversed', $attrs['class'] ) ) {
					$attrs['class'][] = 'fl-col-group-medium-reversed';
				}
			}
		}
		return $attrs;
	}
	/**
	 * Rendor CSS
	 */
	public function render( $css, $nodes, $global_settings, $include_global ) {
		/**
		 * Create instance of CSS Class
		 */
		$compiler = new Css();

		ob_start();

		printf( '@media ( min-width: %spx ) and ( max-width: %spx ){',
			$global_settings->responsive_breakpoint,
			$global_settings->medium_breakpoint
		);

		echo file_get_contents( $this->path( 'extensions/includes/stackingorder.scss' ) );

		echo '};';

		$scss = ob_get_clean();

		$compiled = $compiler->compile( $scss );

		$css .= $compiled;

		return $css;
	}
}