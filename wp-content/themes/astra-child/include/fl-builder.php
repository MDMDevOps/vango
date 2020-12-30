<?php
/**
 * Add our theme stylesheet as a dependency to beaver builder
 * @see  https://kb.wpbeaverbuilder.com/article/117-common-beaver-builder-plugin-filter-examples
 */
function _ac_fl_builder_layout_style_dependencies( $deps ) {
	$deps[] = '_ac_public';
	return $deps;
}
add_filter( 'fl_builder_layout_style_dependencies', '_ac_fl_builder_layout_style_dependencies' );
/**
 * Force inline rendering of css to fix caching issues
 * @see https://kb.wpbeaverbuilder.com/article/699-fix-for-some-caching-issues-load-css-and-javascript-inline
 */
add_filter( 'fl_builder_render_assets_inline', '__return_true' );
/**
 * Stop Astra from forcing layout options for beaver builder
 */
add_filter( 'astra_enable_page_builder_compatibility', '__return_false' );
/**
 * Disable Gutenberg for beaver builder enabled post types
 *
 */
function _ac_disable_gutenberg( $can_edit, $post_type ) {

	// Bail and do nothing if beaver builder isn't installed
	if( !class_exists( 'FLBuilderModel' ) ) {
		return $can_edit;
	}

	$activated_post_types = get_option( '_fl_builder_post_types', array( 'page' ) );

	if( in_array( get_post_type(), $activated_post_types ) && FLBuilderModel::is_builder_enabled() ) {

		$can_edit = false;
	}

	return $can_edit;

}
add_filter( 'gutenberg_can_edit_post_type', '_ac_disable_gutenberg', 10, 2 );
add_filter( 'use_block_editor_for_post_type', '_ac_disable_gutenberg', 10, 2 );

/**
 * Disable Classic Editor for beaver builder enabled post types
 *
 */
function _ac_disable_classic_editor() {
	// Bail and do nothing if beaver builder isn't installed
	if( !class_exists( 'FLBuilderModel' ) ) {
		return false;
	}

	$screen = get_current_screen();
	$activated_post_types = get_option( '_fl_builder_post_types', array() );

	if( in_array( $screen->id , $activated_post_types ) && FLBuilderModel::is_builder_enabled() ) {
		remove_post_type_support( $screen->id, 'editor' );
	}
}
add_action( 'admin_head', '_ac_disable_classic_editor' );

/**
 * Make beaver builder the default editor if post type is supported
 */
function _ac_make_beaver_builder_default( $post_ID, $post, $update ) {
	// Bail and do nothing if beaver builder isn't installed
	if( !class_exists( 'FLBuilderModel' ) ) {
		return false;
	}

	$activated_post_types = get_option( '_fl_builder_post_types', array() );

	if( in_array( $post->post_type, $activated_post_types ) && !$update ) {
		update_post_meta( $post_ID, '_fl_builder_enabled', true );
	}

}
add_action( 'wp_insert_post', '_ac_make_beaver_builder_default', 10, 3 );
/**
 * Glob and register flbuilder modules
 */
function _ac_register_fl_builder_modules() {


	$modules = glob( AC_ROOT_DIR . 'include/flbuilder/*', GLOB_ONLYDIR );

	foreach( $modules as $module ) {

		$module = basename( $module );

		include AC_ROOT_DIR . 'include/flbuilder/' . $module . '/' . $module . '.php';

		$instance = new $module();

		$instance->register_module();
	}

}
add_action( 'init', '_ac_register_fl_builder_modules' );
/**
 * Parse flbuilder links
 */
function _ac_flbuilder_link_markup( $settings, $context = 'open' ) {
	/**
	 * Make sure we have a link
	 */
	if( empty( $settings->link ) ) {
		return;
	}
	/**
	 * Just close the link if we are closing
	 */
	if( $context === 'close' ) {
		return '</a>';
	}
	/**
	 * Begin constructing our markup
	 */
	$rel  = $settings->link_target === '_blank' ? ' noopener noreferrer' : '';
	$rel .= $settings->link_nofollow === 'yes' ? ' nofollow' : '';
	$rel  = trim( $rel );
	$rel  = !empty( $rel ) ? " rel='{$rel}'" : '';
	/**
	 * Return constructed markup
	 */
	return sprintf( '<a href="%s" target="%s"%s>', $settings->link, $settings->link_target, $rel );
}

function _ac_flbuilder_link_rel( $settings ) {
	$rel  = $settings->link_target === '_blank' ? ' noopener noreferrer' : '';
	$rel .= $settings->link_nofollow === 'yes' ? ' nofollow' : '';
	$rel  = trim( $rel );

	return $rel;
}