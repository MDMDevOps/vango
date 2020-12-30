<?php

\FLBuilderCSS::typography_field_rule( array(
	'settings'	   => $settings,
	'setting_name' => 'typography',
	'selector' 	   => ".fl-node-{$id} .menu-item-content",
) );


echo $module->moduleCss([
	'module' => $module,
	'selector' => ".fl-node-{$id}",
]);