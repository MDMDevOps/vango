<?php

namespace wpcl\bbdk\modules\button;

use \wpcl\bbdk\includes\Module;

class DKButton extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( array(
			'name' => __( 'Button', 'bb_dev_kit' ),
			'description' => __( 'Basic Button', 'bb_dev_kit' ),
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'icon' => 'button.svg',
			'editor_export' => true,
			'partial_refresh' => true,
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
		/**
		 * Link Settings
		 */
		if( !empty( $settings->link ) ) {
			$settings->link_rel  = $settings->link_target === '_blank' ? ' noopener noreferrer' : '';
			$settings->link_rel .= $settings->link_nofollow === 'yes' ? ' nofollow' : '';
		}

		return $settings;
	}

	public function getClass( $settings ) {
		$classes = [ 'bbdk-button button' ];

		if( !empty( $settings->button_class ) ) {
			$classes[] = trim( $settings->button_class );
		}

		if( !empty( $settings->icon ) ) {
			$classes[] = 'button-icon';
		}

		if( $settings->icon_position === 'before' ) {
			$classes[] = 'icon-before';
		}

		return implode( ' ', $classes );
	}

	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'General', 'bb_dev_kit' ),
				'sections' => [
					'general' => [
						'title' => '',
						'fields' => [
							'text' => [
								'type' => 'text',
								'label' => __( 'Text', 'bb_dev_kit' ),
								'default' => __( 'Click Here', 'bb_dev_kit' ),
								'preview' => [
									'type' => 'text',
									'selector' => '.bbdk-button-text',
								],
								'connections' => [ 'string' ],
							],
							'link' => [
								'type' => 'link',
								'label' => __( 'Link', 'bb_dev_kit' ),
								'default' => '#',
								'show_target' => true,
								'show_nofollow' => true,
								'preview' => [
									'type'  => 'none',
								],
								'connections' => [ 'url' ],
							],
							'icon' => [
								'type' => 'icon',
								'label' => __( 'Icon', 'bb_dev_kit' ),
								'show_remove' => true,
							],
							'icon_position' => [
								'type' => 'select',
								'label' => __( 'Icon Position', 'bb_dev_kit' ),
								'default' => 'before',
								'options' => [
									'before' => __( 'Before Text', 'bb_dev_kit' ),
									'after' => __( 'After Text', 'bb_dev_kit' ),
								],
							],
							'button_class' => [
								'type' => 'text',
								'label' => __( 'Button Class', 'bb_dev_kit' ),
								'default' => '',
								'preview' => [
									'type' => 'refresh',
								],
							],
						],
					],
				],
			],
			'style' => [
				'title' => __( 'Style', 'bb_dev_kit' ),
				'sections' => [
					'background' => [
						'title' => __( 'Background', 'bb_dev_kit' ),
						'fields' => [
							'color' => [
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
							'color_hover' => [
								'type' => 'color',
								'label' => __( 'Color : Hover', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'bg_color' => [
								'type' => 'color',
								'label' => __( 'Background Color', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'bg_color_hover' => [
								'type' => 'color',
								'label' => __( 'Background Color : Hover', 'bb_dev_kit' ),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview' => [
									'type' => 'refresh',
								],
							],
							'border' => [
								'type' => 'border',
								'label' => 'Border',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'border_hover' => [
								'type' => 'border',
								'label' => 'Border : Hover',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'typography' => [
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
		] );
	}
}