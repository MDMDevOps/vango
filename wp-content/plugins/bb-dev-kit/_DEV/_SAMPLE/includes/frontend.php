<?php

printf( '<div class="bbdk-gallery %s-gallery">',
	$settings->gallery_type
);

if( !empty( $settings->shortcode_atts ) ) {
	echo do_shortcode( "[gallery {$settings->shortcode_atts}]" );
}

echo '</div>';