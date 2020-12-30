<?php

namespace WPCL\BBDK\Modules\Gallery;

class DKGallery extends \FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( array(
			'name'          	=> __( 'Gallery', 'bb_dev_kit' ),
			'description'   	=> '',
			'category'      	=> __( 'Dev Kit', 'bb_dev_kit' ),
			'editor_export' 	=> true,
			'partial_refresh'	=> true,
		));


	}

	public function enqueue_scripts() {
		/**
		 * Enqueue all if builder is active
		 */
		if( isset( $_GET['fl_builder'] ) ) {
			$this->add_js( 'bbdk/slick' );
			$this->add_css( 'bbdk/slick' );
			$this->add_js( 'abt/masonry' );
		}
		/**
		 * Else just enqueue the assets we need
		 */
		if( $this->settings ) {
			switch ( $this->settings->gallery_type)  {
				case 'slider':
					$this->add_js( 'bbdk/slick' );
					$this->add_css( 'bbdk/slick' );
					break;
				case 'masonry':
					$this->add_js( 'bbdk/masonry' );
					break;
				default:
					// Nothing to do by default
					break;
			}
		}
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
		 * Construct the shortcode atts string needed for the gallery shortcode
		 */
		$settings->shortcode_atts = $this->get_shortcode_atts( $settings );
		/**
		 * Set up style vars
		 */
		$style_vars = array(
			'gallery_columns' => 'string',
		);

		$settings->style_vars = \wpcl\bbdk\includes\Css::get_scss_vars( $style_vars, $settings );

		return $settings;
	}

	private function get_shortcode_atts( $settings ) {

		$shortcode_atts = '';

		$atts = array(
			'columns',
			'orderby',
			'order',
			'size',
			'type',
			'link'
		);

		foreach( $atts as $att ) {

			$setting = "gallery_{$att}";

			$shortcode_atts .= !empty( $att ) ? " {$att}='{$settings->$setting}'" : '';

		}

		if( !empty( $this->settings->photos ) ) {

			$ids = implode( ',', $this->settings->photos );

			$shortcode_atts .= " ids='{$ids}'";
		}

		return trim( $shortcode_atts );
	}

	private static function get_image_sizes() {

		$sizes = get_intermediate_image_sizes();

		$settings = array();

		foreach( $sizes as $size ) {
			$settings[$size] = $size;
		}

		return $settings;
	}

	private static function get_gallery_types() {
		$types = array(
			'default' => __( 'Default Grid', 'fl-builder' ),
			'masonry' => __( 'Masonry', 'fl-builder' ),
		);
		/**
		 * Maybe add jetpack tiled galleries
		 */
		if( class_exists( 'Jetpack' ) && \Jetpack::is_module_active( 'tiled-gallery' ) ) {
			$types = array_merge( $types, array(
				'rectangular' => __( 'Tiled Rectangular', 'fl-builder' ),
				'square' => __( 'Tiled Square', 'fl-builder' ),
				'columns' => __( 'Tiled Columns', 'fl-builder' ),
				'circle' => __( 'Circle', 'fl-builder' ),
			) );
		}
		/**
		 * Finally, add the slider gallery type
		 */
		$types = array_merge( $types, array(
			'slider' => 'Slider',
		) );

		return $types;
	}

	public static function register() {
		\FLBuilder::register_module( __CLASS__, array(
			'general' => array( // Tab
				'title' => __('General', '_ac'), // Tab title
				'sections' => array( // Tab Sections
					'general' => array( // Section
						'title' => __('General Options', '_ac'), // Section Title
						'fields' => array(
							'photos' => array(
								'type'  => 'multiple-photos',
								'label' => __( 'Photos', 'fl-builder' )
							),
							'gallery_link' => array(
								'type'  => 'select',
								'label' => __( 'Link To', 'fl-builder' ),
								'default' => 'none',
								'options' => array(
									'none' => __( 'None', 'fl-builder' ),
									'media' => __( 'Media File', 'fl-builder' ),
									'attachment' => __( 'Attachment Page', 'fl-builder' )
								),
							),
							'gallery_columns' => array(
								'type' => 'unit',
								'label' => 'Columns',
								'default' => 3,
								'units' => array( 'cols'),
								'responsive' => true,
								'slider' => array(
									'min' => 1,
									'max' => 9,
									'step' => 1,
								),
							),
							'gallery_orderby' => array(
								'type' => 'select',
								'label' => __( 'Order By', 'fl-builder' ),
								'default' => 'menu_order',
								'options' => array(
									'menu_order' => __( 'Menu Order', 'fl-builder' ),
									'title' => __( 'Title', 'fl-builder' ),
									'post_date' => __( 'Date', 'fl-builder' ),
									'ID' => __( 'ID', 'fl-builder' ),
									'rand' => __( 'Random', 'fl-builder' ),
								),
							),
							'gallery_order' => array(
								'type' => 'button-group',
								'label' => 'Order',
								'default' => 'ASC',
								'options' => array(
									'ASC' => 'ASC',
									'DESC' => 'DESC',
								),
							),
							'gallery_size' => array(
								'type' => 'select',
								'label' => __( 'Image Size', 'fl-builder' ),
								'default' => 'none',
								'options' => self::get_image_sizes(),
							),
							'gallery_type' => array(
								'type' => 'select',
								'label' => __( 'Type', 'fl-builder' ),
								'default' => 'default',
								'options' => self::get_gallery_types(),
								'toggle' => array(
									'slider' => array(
										'tabs' => array( 'style' ),
									),
								),
							),
						),
					),
					'slider' => array( // Section
						'title' => __('Slider Options', '_ac'), // Section Title
						'fields' => array(
							'speed' => array(
								'type' => 'unit',
								'default' => 600,
								'label' => 'Animation Speed',
							),
							'autoplaySpeed' => array(
								'type' => 'unit',
								'default' => 6000,
								'label' => 'Play Speed',
							),
							'centermode' => array(
								'type' => 'button-group',
								'label' => 'CenterMode',
								'default' => '0',
								'options' => array(
									'1' => 'Enabled',
									'0' => 'Disabled',
								),
							),
							'dots' => array(
								'type' => 'button-group',
								'label' => 'Dots',
								'default' => '1',
								'options' => array(
									'1' => 'Enabled',
									'0' => 'Disabled',
								),
							),
							'arrows' => array(
								'type' => 'button-group',
								'label' => 'Arrows',
								'default' => '1',
								'options' => array(
									'1' => 'Enabled',
									'0' => 'Disabled',
								),
							),
							'autoplay' => array(
								'type' => 'button-group',
								'label' => 'Autoplay',
								'default' => '1',
								'options' => array(
									'1' => 'Enabled',
									'0' => 'Disabled',
								),
							),
						),
					),
				)
			),
		));
	}
} // end class