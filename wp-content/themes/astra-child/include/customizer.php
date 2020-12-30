<?php

/**
 * Include the kirki framework to create fields
 */
if( !class_exists( 'Kirki' ) ) {
    require AC_ROOT_DIR . 'include/kirki/kirki.php';
}
Kirki::add_config( '_ac', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );
Kirki::add_field( '_ac', [
	'type'        => 'checkbox',
	'settings'    => '_ac[sticky-sidebar]',
	'label'       => esc_html__( 'Stick Sidebar', 'kirki' ),
	'section'     => 'section-sidebars',
	'default'     => false,
] );
Kirki::add_field( '_ac', [
	'type'        => 'number',
	'settings'    => '_ac[sidebar-breakpoint]',
	'label'       => esc_html__( 'Sidebar Breakpoint (px)', 'kirki' ),
	'section'     => 'section-sidebars',
	'default'     => 768,
	'choices'     => [
		'min'  => 0,
		'max'  => 2560,
		'step' => 1,
	],
] );