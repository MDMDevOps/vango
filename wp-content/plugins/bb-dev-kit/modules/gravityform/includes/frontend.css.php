<?php

echo $module->moduleCss([
	'module' => $module,
	'selector' => ".fl-node-{$id} #gform_{$settings->gform_id}",
]);