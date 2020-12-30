<?php

namespace WPCL\BBDK\Modules\_SAMPLE;

class DKSample extends \FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( [
			'name' => __( 'SAMPLE', 'bb_dev_kit' ),
			'description' => '',
			'category' => __( 'Dev Kit', 'bb_dev_kit' ),
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
		return $settings;
	}

	public static function register() {
		\FLBuilder::register_module( __CLASS__, [
			'general' => [
				'title' => __( 'Title', 'bb_dev_kit' ),
				'sections' => [
					'general'=> [
						'title' => '',
						'fields' => [
						],
					],
				],
			],
		]);
		/**
		 * Register a custom form
		 */
		\FLBuilder::register_settings_form( __CLASS__ . '\custom_form_name', [
			'title' => __( 'Title', 'bb_dev_kit' ),
			'tabs'  => [
				'general' => [
					'title' => __( 'Title', 'bb_dev_kit' ),
					'sections' => [
						'general' => [
							'title' => '',
							'fields' => [
							],
						],
					],
				],
			],
		]);
		/**
		 * Fields reference
		 */
		'fields' => [
			'align' => [
				'type' => 'align',
				'label' => __('Alignment', 'bb_dev_kit'),
				'default' => 'center',
				// Optional
				// 'values'  => [
				//     'left' => '0 auto 0 0',
				//     'center' => '0 auto',
				//     'right' => '0 0 0 auto',
				// ],
				// 'preview' => [
				//     'type' => 'css',
				//     'selector' => '.my-selector',
				//     'property' => 'text-align',
				// ],
			],
			'border' => [
				'type' => 'border',
				'label' => __('Border', 'bb_dev_kit'),
				'responsive' => true,
				// Optional
				// 'preview' => [
				//     'type' => 'refresh',
				// ],
			],
			'button-group' => [
				'type' => 'button-group',
				'label' => __('Button Group', 'bb_dev_kit'),
				'default' => 'two',
				'options' => [
					'one' => __('One', 'bb_dev_kit'),
					'two' => __('Two', 'bb_dev_kit'),
					'three' => __('Three', 'bb_dev_kit'),
				],
			],
			'code' => [
				'type' => 'code',
				'label'   => __('Code', 'bb_dev_kit'),
				'editor' => 'html',
				'rows' => '18'
			],
			'color' => [
				'type' => 'color',
				'label' => __('Color Picker', 'bb_dev_kit'),
				'default' => '333333',
				'show_reset' => true,
				'show_alpha' => true
			],
			'date' => [
				'type' => 'date',
				'label' => __('Date', 'bb_dev_kit'),
				'min' => '2000-01-01', // Optional
				'max' => '2018-12-31', // Optional
			],
			'dimension' => [
				'type' => 'dimension',
				'label' => __('Dimension', 'bb_dev_kit'),
				'description' => 'px',
				'slider' => [
					'min' => 0,
					'max' => 1000,
					'step' => 10,
				],
			],
			'editor' => [
				'type' => 'editor',
				'label' => __('Editor', 'bb_dev_kit'),
				'media_buttons' => true,
				'wpautop' => true
			],
			'font' => [
				'type' => 'font',
				'label' => __('Font', 'bb_dev_kit'),
				'default' => [
					'family' => 'Helvetica',
					'weight' => 300
				],
			],
			'form' => [
				'type' => 'form',
				'label' => __('Form', 'bb_dev_kit'),
				'form' => __CLASS__ . '\custom_form_name', // ID of a registered form.
				'preview_text'  => 'label', // ID of a field to use for the preview text.
			],
			'gradient' => [
				'type'  => 'gradient',
				'label' => __('Gradiaent', 'bb_dev_kit'),
			],
			'icon' => [
				'type' => 'icon',
				'label' => __('Icon', 'bb_dev_kit'),
				'show_remove' => true,
				// Optional to toggle other fields if you have an icon chosen
				// 'show' => [
				//     'fields' => [ 'field_1', 'field_2'),
				//     'sections' => [ 'section_1', 'section_2'),
				//     'tabs'  => [ 'tab_1', 'tab_2'),
				//),
			],
			'link' => [
				'type' => 'link',
				'label' => __('Link', 'bb_dev_kit'),
				'show_target' => true,
				'show_nofollow' => true,
			],
			'loop-settings' => [
				'title' => __('Loop Settings', 'bb_dev_kit'),
				'file' => FL_BUILDER_DIR . 'includes/loop-settings.php',
			],
			'multiple-audios' => [
				'type' => 'multiple-audios',
				'label' => __('Multiple Audios', 'bb_dev_kit'),
			],
			'multiple-photos' => [
				'type' => 'multiple-photos',
				'label' => __('Multiple Photos', 'bb_dev_kit'),
			],
			'photo' => [
				'type' => 'photo',
				'label' => __('Photo', 'bb_dev_kit'),
				'show_remove' => false,
			],
			'post-type' => [
				'type' => 'post-type',
				'label' => __('Post Type', 'bb_dev_kit'),
				'default' => 'post',
			],
			'raw' => [
				'type' > 'raw',
				'label' => __('Raw', 'bb_dev_kit'),
				'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
			],
			'select' => [
				'type' => 'select',
				'label' => __('Select', 'bb_dev_kit'),
				'default' => 'option-1',
				'multi-select'  => true,
				'options' => [
					'option-1' => __('Option 1', 'bb_dev_kit'),
					'option-2' => __('Option 2', 'bb_dev_kit')
				],
				'toggle' => [
					'option-1' => [
						'fields' => [ 'my_field_1', 'my_field_2' ],
						'sections' => [ 'my_section' ],
						'tabs' => [ 'my_tab' ]
					],
					'option-2' => [],
				],
			],
			'service' => [
				'title' => __('Services', 'bb_dev_kit'),
				'file' => FL_BUILDER_DIR . 'includes/service-settings.php',
				'services' => 'autoresponder'
			],
			'shadow' => [
				'type'  => 'shadow',
				'label' => __('Shadow', 'bb_dev_kit'),
				'show_spread' => true,
			],
			'suggest' => [
				'type' => 'suggest',
				'label' => __('suggest', 'bb_dev_kit'),
				'action' => 'fl_as_posts',
				'data' => 'page',
				'limit' => 3,
			],
			'text' => [
				'type' => 'text',
				'label' => __('text', 'bb_dev_kit'),
				'default' => '',
				'maxlength' => '2',
				'size' => '3',
				'placeholder' => __('Placeholder text', 'bb_dev_kit'),
				'class' => 'my-css-class',
				'description'  => __('Text displayed after the field', 'bb_dev_kit'),
				'help' => __('Text displayed in the help tooltip', 'bb_dev_kit'),
			],
			'textarea' => [
				'type' => 'textarea',
				'label' => __('Textarea Field', 'bb_dev_kit'),
				'default' => '',
				'placeholder' => __('Placeholder Text', 'bb_dev_kit'),
				'maxlength' => '255',
				'rows' => '6',
			],
			'time' => [
				'type' => 'time',
				'label' => __('Time', 'bb_dev_kit'),
				'default' => [
					'hours' => '01',
					'minutes' => '00',
					'day_period' => 'am',
				]
			],
			'timezone' => [
				'type' => 'timezone',
				'label' => __('Time Zone', 'bb_dev_kit'),
				'default' => 'UTC',
			],
			'typography' => [
				'type' => 'typography',
				'label' => __('Typography', 'bb_dev_kit'),
			],
			'unit' => [
				'type' => 'unit',
				'label' => __('Unit', 'bb_dev_kit'),
				'units' => [ 'px', 'vw', '%'],
				'default_unit' => '%',
				'slider' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 10,
					],
				],
			],
			'video' => [
				'type' => 'video',
				'label'  => __('Video', 'bb_dev_kit')
			],
		],
	}
} // end class