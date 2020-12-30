<?php

namespace wpcl\bbdk\modules\gravityform;

use \wpcl\bbdk\includes\Module;

class DKGravityForm extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Gravity Form', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'editor_export' => true,
			'partial_refresh' => true,
			'enabled' => class_exists( 'RGFormsModel' ),
		]);

	}

	/**
	 * Update data before saving
	 *
	 * Formats the settings passed from beaver builder, into a usable settings
	 * Sets a rel setting for frontend.php
	 * Sets scss variables for frontend.css.php
	 *
	 * @param  [object] $settings : all settings passed from beaver builder
	 * @since version 1.0.0
	 */
	public function update( $settings ) {

		$settings->shortcode_atts = '';

		$fields = [
			'id',
			'title',
			'description',
			'ajax',
			'tabindex',
		];

		foreach( $fields as $field ) {

			$setting = 'gform_' . $field;

			if ( !empty( $settings->$setting ) ) {
				$settings->shortcode_atts .= ' ' . $field . '="' . $settings->$setting . '"';
			}

		}


		if( !empty( $settings->field_values ) ) {

			$field_vals = '';

			foreach( $settings->field_values as  $index => $field ) {

				$field_vals .= $index !== 0 ? '&' : '';

				$field_vals .= $field->field_name . '=' . $field->field_value;

			}

			$settings->shortcode_atts .= " field_values='{$field_vals}'";
		}

		$settings->shortcode_atts = trim( $settings->shortcode_atts );

		return $settings;
	}

	/**
	 * Get all of the gravity forms created on the site
	 *
	 * @return [array] Array of form ID's and Titles, for use in select field
	 */
	public static function getForms() {

		$options = [ 0 => 'Choose Form' ];

		if( class_exists('RGFormsModel') ) {

			$forms = \RGFormsModel::get_forms();

			foreach( $forms as $form ) {
				$options[$form->id] = $form->title;
			}
		}

		return $options;
	}

	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'Form Options', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'gform_id' => [
								'type' => 'select',
								'label' => __('Choose Form', 'bb_dev_kit'),
								'default' => 0,
								'multi-select'  => false,
								'options' => self::getForms(),
							],
							'gform_title' => [
								'type' => 'select',
								'label' => __('Display Title', 'bb_dev_kit'),
								'default' => 'false',
								'multi-select'  => false,
								'options' => [
									'false' => __('False', 'bb_dev_kit'),
									'true' => __('True', 'bb_dev_kit'),
								],
							],
							'gform_description' => [
								'type' => 'select',
								'label' => __('Display Description', 'bb_dev_kit'),
								'default' => 'false',
								'multi-select'  => false,
								'options' => [
									'false' => __('False', 'bb_dev_kit'),
									'true' => __('True', 'bb_dev_kit'),
								],
							],
							'gform_ajax' => [
								'type' => 'select',
								'label' => __('Enable Ajax', 'bb_dev_kit'),
								'default' => 'false',
								'multi-select'  => false,
								'options' => [
									'false' => __('False', 'bb_dev_kit'),
									'true' => __('True', 'bb_dev_kit'),
								],
							],
							'gform_tabindex' => [
								'type' => 'unit',
								'label' => __('Unit', 'bb_dev_kit'),
								'units' => ['index'],
								'slider' => [
									'min' => -1,
									'max' => 100,
									'step' => 1,
								],
							],
							'field_values' => [
								'type' => 'form',
								'label' => __( 'Field Values', 'wpcl_beaver_extender' ),
								'form' => __CLASS__ . '_field_values',
								'preview_text' => 'field_name',
								'multiple' => true,
							],
						],
					],
				],
			],
			'style' => [
				'title' => __( 'Style', 'bb_dev_kit' ),
				'sections' => [
					'button' => [
						'title' => __( 'button', 'bb_dev_kit' ),
						'fields' => [
							'button_color' => [
								'type' => 'color',
								'label' => __( 'Color', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'cssvar' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_color_hover' => [
								'type' => 'color',
								'label' => __( 'Color : Hover', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_bg_color' => [
								'type' => 'color',
								'label' => __( 'Background Color', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_bg_color_hover' => [
								'type' => 'color',
								'label' => __( 'Background Color : Hover', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_border' => [
								'type' => 'border',
								'label' => 'Border',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_border_hover' => [
								'type' => 'border',
								'label' => 'Border : Hover',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'button_typography' => [
								'type' => 'typography',
								'label' => 'Button Fonts',
								'responsive' => true,
								'preview' => [
									'type' => 'css',
									'selector' => '.bbdk-button',
								],
							],
							'button_width' => [
								'type' => 'select',
								'label' => __( 'Width', 'bb_dev_kit' ),
								'default' => '',
								'options' => [
									'auto' => __( 'Auto', 'bb_dev_kit' ),
									'100%' => __( 'Full Width', 'bb_dev_kit' ),
									'custom' => __( 'Custom', 'bb_dev_kit' ),
								],
								'toggle' => [
									'100%' => [
										'fields' => [ 'button_custom_width' ],
									],
								],
							],
							'button_max_width'  => [
								'type' => 'unit',
								'label' => __( 'Custom Width', 'bb_dev_kit' ),
								'units' => [ 'px', 'em', 'rem', '%' ],
								'default_unit' => 'px',
								'default' => '200',
								'responsive' => true
							],
							'button_align' => [
								'type' => 'align',
								'label' => __( 'Alignment', 'bb_dev_kit' ),
								'default' => 'left',
								'responsive' => true,
								'values' => [
									'left' => 'left',
									'center' => 'center',
									'right' => 'right',
								],
							],
							'button_padding' => [
								'type' => 'dimension',
								'label' => __( 'Padding', 'bb_dev_kit' ),
								'units' => [ 'px', 'em', 'rem', '%' ],
								'default_unit' => 'px',
								'responsive' => true,
							],
						],
					],
				],
			],
		]);
		/**
		 * Register a settings form to use in the "form" field type above.
		 */
		\FLBuilder::register_settings_form( __CLASS__ . '_field_values' , [
			'title' => __( 'Add Field Value', 'bb_dev_kit' ),
			'tabs' => [
				'general' => [
					'title' => __( 'General', 'bb_dev_kit' ),
					'sections' => [
						'general' => [
							'title' => '',
							'fields' => [
								'field_name' => [
									'type' => 'text',
									'label' => __( 'Field Name', 'bb_dev_kit' ),
									'default' => '',
									'description' => __( 'The name of the field to dynamically populate', 'bb_dev_kit' )
								],
								'field_value' => [
									'type' => 'text',
									'label' => __( 'Field Value', 'wpcl_beaver_extender' ),
									'default' => '',
									'description' => __( 'The value of the field to dynamically populate', 'bb_dev_kit' )
								],
							],
						],
					],
				],
			],
		]);
	}
} // end class