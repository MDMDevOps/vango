<?php
/**
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\extensions;
use \wpcl\bbdk\includes\Framework;

class PresetClasses extends Framework {
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
		$this->subscriber->addFilter( 'fl_builder_module_attributes', [$this, 'extendAttributes'] , 10, 2 );
	}
	/**
	 * Extend Settings Form
	 * @param  [array] $form : The settings array for the row settings form
	 * @param  [string] $id  : The id of the form
	 * @return [array]       : The (maybe) modified form
	 */
	public function extendSettingsForm( $form, $id ) {

		$options = apply_filters( "bb_dev_kit/preset_classes/{$id}", [] );

		$field = [
			'type' => 'select',
			'multiple' => true,
			'label' => __( 'Preset Classes', 'bb_dev_kit' ),
			'options' => array_merge( [ '' => 'Choose' ], $options ),
			'help' => "Add classes to list using the filter 'bb_dev_kit/preset_classes/{$id}'",
			'preview' => [ 'type' => 'refresh' ],
		];

		if ( strpos( $id, 'DK') === 0 && isset( $form['general']['sections']['general']['fields'] ) ) {
			$form['general']['sections']['general']['fields']['preset_classes'] = $field;

		}
		elseif ( $id === 'row' || $id === 'col' ) {
			$form['tabs']['style'][ 'sections' ]['general']['fields']['preset_classes'] = $field;
		}
		elseif( isset( $form['general']['sections']['general']['fields'] ) ) {
			$form['general']['sections']['general']['fields']['preset_classes'] = $field;
		}
		return $form;
	}

	public function extendAttributes( $atts, $module ) {

		if( isset( $module->settings->preset_classes ) && !empty( $module->settings->preset_classes ) ) {
			/**
			 * Create a string, so we can implode to individual values
			 */
			$preset_classes = implode( ' ', $module->settings->preset_classes );
			/**
			 * Make sure it's really not empty
			 */
			if( empty( $preset_classes ) ) {
				return $atts;
			}
			/**
			 * Re-explode to remove non-unique string values
			 */
			$preset_classes = array_unique( explode( ' ', $preset_classes ) );
			/**
			 * Get the classes already added to the module
			 * @var [type]
			 */
			$module_classes = implode( ' ', $atts['class'] );

			foreach( $preset_classes as $index => $class ) {
				if( strpos( $module_classes, $class ) ) {
					unset( $preset_classes[$index] );
				}
			}
			/**
			 * Again, check if it's empty
			 */
			if ( !empty($preset_classes) ) {
				$atts['class'][] = implode( ' ', $preset_classes );
			}
		}
		return $atts;
	}
}