<?php

namespace wpcl\bbdk\modules\iframe;

use \wpcl\bbdk\includes\Module;

class DKIframe extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Iframe', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'editor_export' => true,
			'partial_refresh' => true,
			'icon' => 'layout.svg',
		]);

	}

	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'iframe', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'src' => [
								'type' => 'link',
								'label' => __('Source', 'bb_dev_kit'),
								'show_target' => false,
								'show_nofollow' => false,
							],
							'width' => [
								'type' => 'unit',
								'label' => __( 'Width', 'bb_dev_kit' ),
								'units' => [ 'px', 'vw', '%', 'rem', 'em' ],
								'default_unit' => '%',
								'default' => '100',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'height' => [
								'type' => 'unit',
								'label' => __( 'Height', 'bb_dev_kit' ),
								'units' => [ 'px', 'vh', 'rem', 'em' ],
								'default_unit' => 'px',
								'default' => '400',
								'preview' => [
									'type' => 'refresh',
								],
							],
							'scrolling' => [
								'type' => 'select',
								'label' => __( 'Scrolling', 'bb_dev_kit' ),
								'default' => 'auto',
								'options' => [
									'auto' => 'Auto',
									'yes' => 'Yes',
									'no' => 'No',
								],
								'preview' => [
									'type' => 'refresh',
								],
							],
							'frameborder' => [
								'type' => 'select',
								'label' => __( 'Frame Border', 'bb_dev_kit' ),
								'default' => '0',
								'options' => [
									'0' => 'Off',
									'1' => 'On',
								],
								'preview' => [
									'type' => 'refresh',
								],
							],
						],
					],
				],
			],
		]);
	}
} // end class