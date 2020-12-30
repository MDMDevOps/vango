<?php
// /**
//  * Group styles
//  */
// \wpcl\bbdk\includes\Css::render_module_css( array(
// 	'module' => $module,
// 	'settings' => $settings,
// 	'selector' => ".fl-node-{$id}",
// 	'scss'     => 'button-group.scss',
// ));
// /**
//  * Individual button styles
//  */
// foreach( $settings->buttons as $index => $button ) {

// 	$count = $index + 1;

// 	\FLBuilderCSS::typography_field_rule( array(
// 		'settings'	   => $button,
// 		'setting_name' => 'typography',
// 		'selector' 	   => ".fl-node-{$id} .button-container:nth-child({$count}) .bbdk-button",
// 	) );

// 	\wpcl\bbdk\includes\Css::render_module_css( array(
// 		'module' => $module,
// 		'settings' => $button,
// 		'selector' => ".fl-node-{$id} .button-container:nth-child({$count})",
// 		'scss'     => 'button.scss',
// 	));

// }