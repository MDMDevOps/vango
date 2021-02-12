<?php

\FLBuilderCSS::typography_field_rule([
	'settings' => $settings,
	'setting_name' => 'button_typography',
	'selector' => ".fl-node-{$id} #gform_{$settings->gform_id} .gform_footer .gform_button",
] );

\FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'button_border',
	'selector' => ".fl-node-$id #gform_{$settings->gform_id} .gform_footer .gform_button",
) );

\FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'button_border_hover',
	'selector' => ".fl-node-$id #gform_{$settings->gform_id} .gform_footer .gform_button:hover, .fl-node-$id #gform_{$settings->gform_id} .gform_footer .gform_button:focus",
) );

echo $module->moduleCss([
	'module' => $module,
	'selector' => ".fl-node-{$id} #gform_{$settings->gform_id}",
]);