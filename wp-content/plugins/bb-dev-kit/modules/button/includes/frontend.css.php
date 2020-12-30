<?php

\FLBuilderCSS::typography_field_rule([
	'settings' => $settings,
	'setting_name' => 'typography',
	'selector' => ".fl-node-{$id} a.bbdk-button",
] );

\FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'border',
	'selector' => ".fl-node-$id a.bbdk-button",
) );

\FLBuilderCSS::border_field_rule( array(
	'settings' => $settings,
	'setting_name' => 'border_hover',
	'selector' => ".fl-node-$id a.bbdk-button:hover, .fl-node-$id a.bbdk-button:focus",
) );

echo $module->moduleCss([
	'module' => $module,
	'selector' => ".fl-node-{$id}",
]);

