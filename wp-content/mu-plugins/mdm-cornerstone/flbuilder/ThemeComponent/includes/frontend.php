<?php

if ( !empty( $settings->component ) ) {

	$component = locate_template( $settings->component );

	if ( $component ) {
		include $component;
	}
}