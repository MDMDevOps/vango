<?php

namespace wpcl\bbdk\modules\shortcode;

use \wpcl\bbdk\includes\Module;

class DKShortCode extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Shortcode', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'icon' => 'editor-code.svg',
			'editor_export' => true,
			'partial_refresh' => true,
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
		/**
		 * Remove any brackets, so we can make sure we add them correctly
		 */
		$settings->shortcode = trim( str_replace([ '[', ']' ], '', $settings->shortcode ) );
		/**
		 * Add the brackets back in, correctly
		 * @var [type]
		 */
		$settings->shortcode = !empty( $settings->shortcode ) ? "[{$settings->shortcode}]" : '';

		return $settings;
	}
	/**
	 * Register module with fl-builder
	 *
	 * @since  1.0.0
	 */
	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'iframe', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'shortcode' => [
								'type' => 'text',
								'label' => __('Shortcode', 'bb_dev_kit'),
								'default' => '',
							],
						],
					],
				],
			],
		]);
	}
}