<?php

function _ac_get_context() {

	$context = 'default';

	if( is_front_page() && !is_home() ) {
		$context = 'frontpage';
	}

	else if( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$context = 'woocommerce';
	}

	else if( is_archive() ) {
		$context = 'archive';
	}

	else if( is_search() ) {
		$context = 'search';
	}

	else if( is_home() ) {
		$context = 'blog';
	}

	else if( is_singular() ) {
		$context = 'single';
	}

	else if( is_404() ) {
		$context = '404';
	}

	$context = apply_filters( 'astra_child_context', $context );

	return $context;
}

function _ac_get_options( $setting = '' ) {

	$options = get_theme_mod( '_ac', array() );

	$options = wp_parse_args( $options, array(
		'sticky-sidebar' => false,
		'sidebar-breakpoint' => false
	) );

	if( !empty( $setting ) && isset( $options[$setting] ) ) {
		return $options[$setting];
	} else {
		return $options;
	}

}
/**
 * Print customizer css
 */
function _ac_custom_css() {

	$css = apply_filters( '_ac_custom_css', '' );

	if( !empty( $css ) ) {
		printf( '<style>%s</style>', $css );
	}

}
add_action( 'wp_head', '_ac_custom_css' );
/**
 * Maybe change the sidebar breakpoint
 */
function _ac_custom_sidebar_breakpoint( $css ) {

	$breakpoint = _ac_get_options( 'sidebar-breakpoint' );

	if( $breakpoint ) {
		$css .= sprintf( '@media only screen and (max-width: %dpx){ #primary, #secondary { width: 100%%; float: none; } }', $breakpoint );
	}

	return $css;

}
add_action( '_ac_custom_css', '_ac_custom_sidebar_breakpoint' );
/**
 * Maybe use sticky sidebar
 */
function _ac_sticky_sidebar( $classes ) {

	$global = _ac_get_options();

	if( is_singular() ) {

		$meta = get_post_meta( get_the_id(), '_ac_sticky_sidebar', true );

		if( $meta == true && $meta !== 'default' ) {

			$classes[] = 'sticky-sidebar';

		}

		else if( $global['sticky-sidebar'] == true ) {

			$classes[] = 'sticky-sidebar';
		}
	}

	else if( $global['sticky-sidebar'] == true ) {

		$classes[] = 'sticky-sidebar';

	}

	return $classes;
}
add_filter( 'astra_secondary_class', '_ac_sticky_sidebar' );
add_filter( 'body_class', '_ac_sticky_sidebar' );

function _ac_get_template_part( $slug = '', $name = '', $include = true, $relative_path = false, $require_once = false ) {
	/**
	 * Dont waste time if path is empty
	 */
	if( empty( $slug ) ) {
		return;
	}

	$name = apply_filters( "_ac_get_template_part_{$slug}", $name );

	$view = _ac_get_context();

	$type = get_post_type();

	$templates = array();

	$template = false;

	/**
	 * Named templates take priority
	 */
	if( !empty( $name ) ) {
		$templates[] = "{$slug}/{$name}-{$view}-{$type}.php";
		$templates[] = "{$slug}/{$name}-{$view}.php";
		$templates[] = "{$slug}/{$name}-{$type}.php";
		$templates[] = "{$slug}/{$name}.php";
		$templates[] = "{$slug}-{$name}.php";
	}

	/**
	 * View/context based templates
	 */
	$templates[] = "{$slug}/{$view}-{$type}.php";
	$templates[] = "{$slug}/{$view}.php";
	$templates[] = "{$slug}/{$type}.php";
	$templates[] = "{$slug}/default.php";
	$templates[] = "{$slug}.php";

	/**
	 * Search for, and assign first template found
	 */
	foreach( $templates as $template_path ) {

		$template_found = locate_template( $template_path, false, false );

		if( $template_found ) {
			$template = $relative_path === true ? "/{$template_path}" : $template_found;
			break;
		}
	}
	/**
	 * Maybe include
	 */
	if( $template && $include ) {
		if( $require_once ) {
			require_once $template;
		}
		else {
			require $template;
		}
	}
	/**
	 * Or just return it
	 * Useful for when variables need to be accessible
	 */
	else {
		return $template;
	}
}