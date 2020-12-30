<?php

namespace mdm\cornerstone\flbuilder\ThemeComponent;

class ThemeComponent extends \FLBuilderModule {
	/**
	 * Theme Components
	 * @var array
	 */
	protected $components = [];
	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Get all the theme components
		 */
		$this->components = apply_filters( 'fl_builder_theme_components', [] );
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Theme Component', 'mdm_cornerstone' ),
			'description' => '',
			'category' => __( 'Basic', 'mdm_cornerstone' ),
			'editor_export' => true,
			'partial_refresh' => true,
			'enabled' => !empty( $this->components ),
		]);
	}
	/**
	 * Register module with fl-builder
	 *
	 * @since  1.0.0
	 */
	public function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'Component', 'mdm_cornerstone' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'component' => [
								'type' => 'select',
								'label' => __('Theme Component', 'mdm_cornerstone'),
								'default' => '',
								'options' => $this->components,
							],
						],
					],
				],
			],
		]);
	}
}