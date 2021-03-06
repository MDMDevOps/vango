<?php
/**
 * Register the module and its form settings for beaver builder version less than 2.2.
 * Applicable for UABB version 1.13.2 and before.
 * Converted font, text size, and text transform settings to a responsive typography setting.
 *
 * @package UABB Spacer Gap Module
 */

FLBuilder::register_module(
	'UABBSpacerGap',
	array(
		'spacer_gap_general' => array( // Tab.
			'title'    => __( 'General', 'uabb' ), // Tab title.
			'sections' => array( // Tab Sections.
				'spacer_gap_general' => array( // Section.
					'title'  => '', // Section Title.
					'fields' => array( // Section Fields.
						'desktop_space' => array(
							'type'        => 'unit',
							'label'       => __( 'Desktop', 'uabb' ),
							'size'        => '8',
							'placeholder' => '10',
							'class'       => 'uabb-spacer-gap-desktop',
							'description' => 'px',
							'help'        => __( 'This value will work for all devices.', 'uabb' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-spacer-gap-preview.uabb-spacer-gap',
								'property' => 'height',
								'unit'     => 'px',
							),
						),
						'medium_device' => array(
							'type'        => 'unit',
							'label'       => __( 'Medium Device ( Tabs )', 'uabb' ),
							'default'     => '',
							'size'        => '8',
							'class'       => 'uabb-spacer-gap-tab-landscape',
							'description' => 'px',
						),
						'small_device'  => array(
							'type'        => 'unit',
							'label'       => __( 'Small Device ( Mobile )', 'uabb' ),
							'default'     => '',
							'size'        => '8',
							'class'       => 'uabb-spacer-gap-mobile',
							'description' => 'px',
						),
					),
				),
			),
		),
	)
);
