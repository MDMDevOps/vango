<?php

namespace wpcl\bbdk\modules\ButtonGroup;

class DKButtonGroup extends \FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( array(
			'name'          	=> __( 'Button Group', 'bb_dev_kit' ),
			'description'   	=> '',
			'category'      	=> __( 'Dev Kit', 'bb_dev_kit' ),
			'editor_export' 	=> true,
			'partial_refresh'	=> true,
		));
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

		$style_vars = array(
			'alignment' => 'string',
			'stacking' => 'string',
		);

		$settings->style_vars = \wpcl\bbdk\includes\Css::get_scss_vars( $style_vars, $settings );

		foreach( $settings->buttons as $index => $button ) {

			$button = \wpcl\bbdk\modules\button\DKButton::set_atts( $button );
		}

		return $settings;
	}

	public function register() {
		\FLBuilder::register_module( __CLASS__, array(
			'general' => array(
				'title' => __( 'General', 'bb_dev_kit' ),
				'sections' => array(
					'general' => array(
						'title' => '',
						'fields' => array(
							'buttons'         => array(
								'type'          => 'form',
								'label'         => __( 'Button Group', 'bb_dev_kit' ),
								'form'          => 'bbdk_button_group', // ID from registered form below
								'preview_text'  => 'text', // Name of a field to use for the preview text
								'multiple'      => true,
							),
							'alignment' => array(
								'type'          => 'align',
								'label'         => __( 'Alignment', 'bb_dev_kit' ),
								'default'       => '0 auto 0 0',
								'responsive'    => true,
								'values'  => array(
									'left'   => 'left',
									'center' => 'center',
									'right'  => 'right',
								),
							),
							'stacking' => array(
							    'type'          => 'select',
							    'label'         => __( 'Button Stacking', 'bb_dev_kit' ),
							    'default'       => 'option-1',
							    'responsive'    => 'flex',
							    'options'       => array(
							        'flex' => __( 'horizontal', 'bb_dev_kit' ),
							        'block' => __( 'vertical', 'bb_dev_kit' )
							    ),
							),
						),
					),
				),
			),
		));

		\FLBuilder::register_settings_form( 'bbdk_button_group' , array(
			'title' => __( 'Add Button', 'bb_dev_kit' ),
			'tabs'  => array(
				'general' => array(
					'title' => __( 'General', 'bb_dev_kit' ),
					'sections' => array(
						'general' => array(
							'title' => '',
							'fields' => array(
								'text'          => array(
									'type'          => 'text',
									'label'         => __( 'Text', 'bb_dev_kit' ),
									'default'       => __( 'Click Here', 'bb_dev_kit' ),
									'preview'         => array(
										'type'            => 'text',
										'selector'        => '.bbdk-button-text',
									),
									'connections'         => array( 'string' ),
								),
								'link'          => array(
									'type'          => 'link',
									'label'         => __( 'Link', 'bb_dev_kit' ),
									'default' => '#',
									'show_target'	=> true,
									'show_nofollow'	=> true,
									'preview'       => array(
										'type'          => 'none',
									),
									'connections'         => array( 'url' ),
								),
								'icon'          => array(
									'type'          => 'icon',
									'label'         => __( 'Icon', 'bb_dev_kit' ),
									'show_remove'   => true,
								),
								'icon_position' => array(
									'type'          => 'select',
									'label'         => __( 'Icon Position', 'bb_dev_kit' ),
									'default'       => 'before',
									'options'       => array(
										'before'        => __( 'Before Text', 'bb_dev_kit' ),
										'after'         => __( 'After Text', 'bb_dev_kit' ),
									),
								),
								'button_class'          => array(
									'type'          => 'text',
									'label'         => __( 'Button Class', 'bb_dev_kit' ),
									'default'       => '',
									'preview'         => array(
										'type'            => 'refresh',
									),
								),
							),
						),
					),
				),
				'style' => array(
					'title' => __( 'Style', 'bb_dev_kit' ),
					'sections' => array(
						'background' => array( // Section
							'title' => __( 'Background', 'bb_dev_kit' ),
							'fields' => array(
								'color'      => array(
									'type'          => 'color',
									'label'         => __( 'Color', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'color_hover'      => array(
									'type'          => 'color',
									'label'         => __( 'Color : Hover', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'bg_color'      => array(
									'type'          => 'color',
									'label'         => __( 'Background Color', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'bg_color_hover' => array(
									'type'          => 'color',
									'label'         => __( 'Background Color : Hover', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'border_color'      => array(
									'type'          => 'color',
									'label'         => __( 'Border Color', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'border_color_hover'      => array(
									'type'          => 'color',
									'label'         => __( 'Border Color : Hover', 'bb_dev_kit' ),
									'default'       => '',
									'show_reset'    => true,
									'show_alpha'    => true,
									'preview'       => array(
										'type'          => 'refresh',
									),
								),
								'border_width'  => array(
									'type'          => 'unit',
									'label'         => __( 'Border Width', 'bb_dev_kit' ),
									'units'	       => array( 'px'),
									'default_unit' => 'px',
									'default' => '',
								),
								'border_radius'  => array(
									'type'          => 'unit',
									'label'         => __( 'Border Radius', 'bb_dev_kit' ),
									'units'	       => array( 'px', '%' ),
									'default_unit' => 'px',
									'default' => '',
								),
								'typography' => array(
									'type'       => 'typography',
									'label'      => 'Button Fonts',
									'responsive' => true,
									'preview'    => array(
										'type'	    => 'css',
										'selector'  => '.bbdk-button',
									),
								),
								'button_width'         => array(
									'type'          => 'select',
									'label'         => __( 'Width', 'bb_dev_kit' ),
									'default'       => '',
									'options'       => array(
										'auto'          => _x( 'Auto', 'Width.', 'bb_dev_kit' ),
										'100%'          => __( 'Full Width', 'bb_dev_kit' ),
										'custom'        => __( 'Custom', 'bb_dev_kit' ),
									),
									'toggle'        => array(
										'100%'        => array(
											'fields'        => array( 'button_custom_width' ),
										),
									),
								),
								'button_max_width'  => array(
									'type'          => 'unit',
									'label'         => __( 'Custom Width', 'bb_dev_kit' ),
									'units'	       => array( 'px', 'em', 'rem', '%' ),
									'default_unit' => 'px',
									'default' => '200',
									'responsive' => true
								),
								'button_padding' => array(
									'type'        => 'dimension',
									'label'         => __( 'Padding', 'bb_dev_kit' ),
									'units'	       => array( 'px', 'em', 'rem', '%' ),
									'default_unit' => 'px',
									'responsive' => true,
								),
							),
						),
					),
				),
			),
		));
	}
} // end class