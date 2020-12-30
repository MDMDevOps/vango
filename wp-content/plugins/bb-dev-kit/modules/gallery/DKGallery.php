<?php

namespace wpcl\bbdk\modules\gallery;

use \wpcl\bbdk\includes\Module;

use \Wa72\HtmlPageDom\HtmlPageCrawler;

class DKGallery extends Module {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'Gallery', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
			'editor_export' => true,
			'partial_refresh' => true,
		]);

		$this->add_js( 'bbdk/gallaries' );

	}

	/**
	 * Add some custom gallery classes
	 */
	public function gallery( $html ) {

		// $html_string = 'HTML string';
		// $dom = new DOMDocument();
		// $dom->loadHTML($html_string);

		// foreach( $dom->getElementsByTagName('figure') as $node) {
		//    $title = $dom->saveHTML($node);
		//    $content[$title] = array();

		//    while(($node = $node->nextSibling) && $node->nodeName !== 'h4') {
		//       $content[$title] = $dom->saveHTML($node);
		//    }
		// }

		/**
		 * Create HTML
		 * @var HtmlPageCrawler
		 */
		// $crawler = new HtmlPageCrawler( $html );

		// $content = $crawler->filter('.gallery')->html();

		// // $content .=

		// // $c->filter('figure');

		// // print_r($c->filter('.gallery-item')->saveHTML());
		// // var_dump($c->filter('.gallery')->addClass('newclass')->saveHTML());
		// var_dump($c->filter('.gallery')->html());

		// $doc = new \DOMDocument();
		// $doc->loadHTML($html);
		//
		// preg_split("/<figure>.+</figure>/i", $html);

		// var_dump($html);

		if ( in_array( $this->settings->gallery_type, [ 'default', 'slider', 'masonry' ] ) ) {
			$html = str_replace( "gallery-columns-{$this->settings->gallery_columns}", '', $html);
		}
		return $html;
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

			$shortcode_atts .= !empty( $settings->$setting ) ? " {$att}='{$settings->$setting}'" : '';

		}

		if( !empty( $this->settings->photos ) ) {

			$ids = implode( ',', $this->settings->photos );

			$shortcode_atts .= " include='{$ids}'";
		}

		$settings->shortcode_atts = trim( $shortcode_atts );

		$settings->gallery_columns = !empty( $settings->gallery_columns ) ? $settings->gallery_columns : 3;

		return $settings;
	}
	public static function getImageSizes() {

		$sizes = get_intermediate_image_sizes();

		$settings = [];

		foreach( $sizes as $size ) {
			$settings[$size] = $size;
		}

		return $settings;
	}
	public static function getGalleryTypes() {
		/**
		 * Default gallery type
		 */
		$types = [
			'default' => __( 'Default Grid', 'bb_dev_kit' ),
		];
		/**
		 * Maybe add jetpack tiled galleries
		 */
		if( class_exists( 'Jetpack' ) && \Jetpack::is_module_active( 'tiled-gallery' ) ) {
			$types = array_merge( $types, [
				'rectangular' => __( 'Tiled Rectangular', 'bb_dev_kit' ),
				'square' => __( 'Tiled Square', 'bb_dev_kit' ),
				'columns' => __( 'Tiled Columns', 'bb_dev_kit' ),
				'circle' => __( 'Circle', 'bb_dev_kit' ),
			] );
		}
		/**
		 * Finally, add the slider and masonry gallery type
		 */
		$types = array_merge( $types, array(
			'slider' => __( 'Slider', 'bb_dev_kit' ),
			'masonry' => __( 'Masonry', 'bb_dev_kit' ),
		) );

		return $types;
	}


	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'Form Options', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
							'photos' => [
								'type'  => 'multiple-photos',
								'label' => __( 'Photos', 'bb_dev_kit' )
							],
							'gallery_link' => [
								'type'  => 'select',
								'label' => __( 'Link To', 'bb_dev_kit' ),
								'default' => 'none',
								'options' => [
									'none' => __( 'None', 'bb_dev_kit' ),
									'media' => __( 'Media File', 'bb_dev_kit' ),
									'attachment' => __( 'Attachment Page', 'bb_dev_kit' )
								],
							],
							'gallery_orderby' => [
								'type' => 'select',
								'label' => __( 'Order By', 'bb_dev_kit' ),
								'default' => 'menu_order',
								'options' => [
									'menu_order' => __( 'Menu Order', 'bb_dev_kit' ),
									'title' => __( 'Title', 'bb_dev_kit' ),
									'post_date' => __( 'Date', 'bb_dev_kit' ),
									'ID' => __( 'ID', 'bb_dev_kit' ),
									'rand' => __( 'Random', 'bb_dev_kit' ),
								],
							],
							'gallery_order' => [
								'type' => 'button-group',
								'label' => 'Order',
								'default' => 'ASC',
								'options' => [
									'ASC' => 'ASC',
									'DESC' => 'DESC',
								],
							],
							'gallery_size' => [
								'type' => 'select',
								'label' => __( 'Image Size', 'bb_dev_kit' ),
								'default' => 'none',
								'options' => self::getImageSizes(),
							],
							'gallery_type' => [
								'type' => 'select',
								'label' => __( 'Type', 'bb_dev_kit' ),
								'default' => 'default',
								'options' => self::getGalleryTypes(),
								'toggle' => [
									'slider' => [
										'sections' => [ 'slider'],
									],
								],
							],
							'gallery_columns' => [
								'type' => 'unit',
								'label' => 'Columns',
								'default' => 3,
								'units' => ['cols'],
								'responsive' => true,
								'slider' => [
									'min' => 1,
									'max' => 9,
									'step' => 1,
								],
							],
						],
					],
					'slider' => [
						'title' => __( 'Slider', 'bb_dev_kit' ),
						'fields' => [
							'slider_speed' => [
								'type' => 'unit',
								'default' => 600,
								'units' => ['ms'],
								'label' => 'Transition Speed',
							],
							'slider_autoplay' => [
								'type' => 'button-group',
								'label' => 'Autoplay',
								'default' => '1',
								'options' => [
									'1' => 'Enabled',
									'0' => 'Disabled',
								],
								'toggle' => [
									'1' => [
										'fields' => [ 'slider_autoplaySpeed' ],
									],
								],
							],
							'slider_autoplaySpeed' => [
								'type' => 'unit',
								'default' => 6000,
								'units' => ['ms'],
								'label' => 'Play Speed',
							],
							'slider_dots' => [
								'type' => 'button-group',
								'label' => 'Dots',
								'default' => '1',
								'options' => [
									'1' => 'Enabled',
									'0' => 'Disabled',
								],
							],
							'slider_arrows' => [
								'type' => 'button-group',
								'label' => 'Arrows',
								'default' => '1',
								'options' => [
									'1' => 'Enabled',
									'0' => 'Disabled',
								],
								'toggle' => [
									'1' => [
										'fields' => [ 'slider_previous', 'slider_next' ],
									],
								],
							],
							'slider_previous' => [
								'type' => 'icon',
								'label' => __( 'Previous Arrow', 'bb_dev_kit' ),
								'show_remove' => true
							],
							'slider_next' => [
								'type' => 'icon',
								'label' => __( 'Next Arrow', 'bb_dev_kit' ),
								'show_remove' => true
							],
						],
					],
				],
			],
		]);
	}
} // end class