<?php

namespace wpcl\bbdk\modules\simplemenu;

use \wpcl\bbdk\includes\Module;

class DKSimpleMenu extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Simple Menu', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'icon' => 'hamburger-menu.svg',
			'editor_export' => true,
			'partial_refresh' => true,
		]);

	}

	/**
	 * Update data before saving
	 *
	 * Maybe adds default icon, if a global icon is selected but no an icon for
	 * a singular menu item
	 *
	 * @param object $settings : all settings passed from beaver builder
	 * @since version 1.0.0
	 */
	public function update( $settings ) {

		if ( !empty( $settings->icon ) ) {
			foreach( $settings->links as $link ) {
				$link->icon = empty( $link->icon ) ? $settings->icon : $link->icon;
			}
		}

		return $settings;
	}
	/**
	 * Constructs menu item classes for an individual menu item
	 *
	 * @since  1.0.0
	 */
	public function getMenuItemClass( $link ) {

		$class = ['dk-menu-item'];

		$class[] = trim( $link->class );

		$class[] = get_queried_object()->ID === url_to_postid( $link->link ) ? 'current-item' : '';

		return trim( implode( ' ', array_filter( $class ) ) );
	}
	/**
	 * Register module with fl-builder
	 *
	 * @since  1.0.0
	 */
	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'Menu', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'links' => [
								'type' => 'form',
								'label' => __('Links', 'bb_dev_kit'),
								'form' => __CLASS__ . '\menu_items',
								'preview_text'  => 'text',
								'multiple' => true,
							],
							'align' => [
								'type' => 'align',
								'label' => __('Alignment', 'bb_dev_kit'),
								'default' => 'left',
								'responsive' => true,
							],
							'typography' => [
								'type' => 'typography',
								'label' => __('Typography', 'bb_dev_kit'),
								'preview' => array(
									'type' => 'css',
									'selector' => '.menu-item-content',
								),
							],
							'color' => [
								'type' => 'color',
								'label' => __('Color', 'bb_dev_kit'),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true
							],
							'color_hover' => [
								'type' => 'color',
								'label' => __('Hover Color', 'bb_dev_kit'),
								'default' => '',
								'show_reset' => true,
								'show_alpha' => true
							],
							'linkpadding' => [
								'type' => 'dimension',
								'label' => __('Menu Item Padding', 'bb_dev_kit'),
								'description' => 'px',
								'responsive' => true,
								'slider' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								],
							],
							'icon' => [
								'type' => 'icon',
								'label' => __('Icon', 'bb_dev_kit'),
								'show_remove' => true,
							],
							'iconalign' => [
								'type' => 'align',
								'label' => __('Icon Alignment', 'bb_dev_kit'),
								'default' => 'right',
								'responsive' => true,
								'values'  => [
									'left' => 'left',
									'right' => 'right',
								],
							],
							'iconsize' => [
								'type' => 'unit',
								'label' => __('Icon Size', 'bb_dev_kit'),
								'units' => [ 'px', 'em', 'rem'],
								'default_unit' => 'px',
								'slider' => [
									'px' => [
										'min' => 0,
										'max' => 100,
										'step' => 1,
									],
								],
							],
							'iconalignitems' => [
								'type' => 'select',
								'label' => __('Icon Vertical Alignment', 'bb_dev_kit'),
								'default' => 'normal',
								'options' => [
									'normal' => __('Top', 'bb_dev_kit'),
									'center' => __('Center', 'bb_dev_kit'),
									'flex-end' => __('Bottom', 'bb_dev_kit'),
									'baseline' => __('Baseline', 'bb_dev_kit')
								],
							],
							'iconmargin' => [
								'type' => 'dimension',
								'label' => __('Icon Margin', 'bb_dev_kit'),
								'units' => ['px', 'rem', 'em'],
								'default_unit' => 'px',
								'responsive' => true,
								'slider' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								],
							],
						],
					],
				],
			],
		]);
		\FLBuilder::register_settings_form( __CLASS__ . '\menu_items', [
			'title' => __( 'Info Cards', 'bb_dev_kit' ),
			'tabs'  => [
				'general' => [
					'title' => __( 'Options', 'bb_dev_kit' ),
					'sections' => [
						'options'=> [
							'title' => '',
							'fields' => [
								'text' => [
									'type' => 'text',
									'label' => __('Text', 'bb_dev_kit'),
									'default' => '',
								],
								'link' => [
									'type' => 'link',
									'label' => __('Link', 'bb_dev_kit'),
									'show_target' => true,
									'show_nofollow' => true,
								],
								'class' => [
									'type' => 'text',
									'label' => __('Class', 'bb_dev_kit'),
									'default' => '',
								],
								'icon' => [
									'type' => 'icon',
									'label' => __('Icon', 'bb_dev_kit'),
									'show_remove' => true,
								],
							],
						],
					],
				],
			],
		]);
	}
} // end class