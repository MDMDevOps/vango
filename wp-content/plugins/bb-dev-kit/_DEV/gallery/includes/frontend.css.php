<?php

\wpcl\bbdk\includes\Css::render_module_css( array(
	'module' => $module,
	'settings' => $settings,
	'selector' => ".fl-node-{$id}",
	'scss'     => 'gallery.scss',
));