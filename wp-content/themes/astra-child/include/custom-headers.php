<?php
/**
 * Replace the custom header image with user selected one
 * Uses an additional metabox instead of the featured image
 */
function _ac_custom_header_image( $img ) {

	$custom = get_post_meta( get_the_id(), 'ac_header_image', true );

	if( !empty( $custom ) ) {
		$img = wp_get_attachment_url( $custom );
	}

	return $img;
}
add_filter( 'astra_advanced_headers_title_bar_bg', '_ac_custom_header_image' );
/**
 * Fix for astra that disabled below header menu and above header menu
 * when page headers are being used
 */
add_filter( 'astra_above_header_disable', '__return_false', 999 );
add_filter( 'astra_below_header_disable', '__return_false', 999 );