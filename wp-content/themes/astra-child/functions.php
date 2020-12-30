<?php
/**
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'AC_VERSION', WP_DEBUG === true ? time() : '1.1.0' );
define( 'AC_PREFIX', WP_DEBUG === true ? '' : '.min' );
define( 'AC_ROOT_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'AC_ROOT_URL', trailingslashit( get_stylesheet_directory_uri() ) );
/**
 * Define $content_width variable for embeds, jetpack, etc
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1280;
}

/**
 * Filter to fix default primary menu if no menu is selected
 */
function ac_primary_menu_fix( $menu, $args ) {
	if( $args['menu_id'] === 'primary-menu' ) {
		return '';
	}
	return $menu;
}
add_filter( 'wp_page_menu', 'ac_primary_menu_fix', 10, 2 );

/**
 * Include our framework functions
 *
 * Framework functions are not invoked by the theme, but are used by other
 * functions as helpers
 */
include AC_ROOT_DIR . 'include/framework.php';
/**
 * Include our custom headers functions
 */
include AC_ROOT_DIR . 'include/custom-headers.php';
/**
 * Include customizer file if user is logged in
 *
 * NOT the same as is_admin, which returns false in the customizer view
 */
if( is_user_logged_in() ) {
	include AC_ROOT_DIR . 'include/customizer.php';
}
/**
 * Include fl builder support
 */
if( class_exists( 'FLBuilderModel' ) ) {
	include_once AC_ROOT_DIR . 'include/fl-builder.php';
}
/**
 * Include jetpack support
 */
if( class_exists( 'Jetpack' ) ) {
	include_once AC_ROOT_DIR . 'include/jetpack.php';
}
/**
 * Add custom image sizes
 */
add_image_size( 'medium-thumbnail', 300, 300, true );
add_image_size( 'large-thumbnail', 768, 768, true );
/**
 * Add featured image size to selector
 */
function _ac_show_image_sizes($sizes) {
    $sizes['medium-thumbnail'] = __( 'Medium Thumbnail', '_ac' );
    $sizes['large-thumbnail'] = __( 'Large Thumbnail', '_ac' );
    return $sizes;
}
add_filter( 'image_size_names_choose', '_ac_show_image_sizes' );

/**
 * Enqueue styles and scripts
 */
function _ac_enqueue_assets() {

	// wp_enqueue_script( '_ac_modernizr', AC_ROOT_URL . 'assets/js/modernizr.min.js', array( ), AC_VERSION, false );

	wp_enqueue_script( '_ac_public', AC_ROOT_URL . 'assets/js/public' . AC_PREFIX . '.js', array( 'jquery' ), AC_VERSION, false );

	wp_enqueue_style( '_ac_public', AC_ROOT_URL . 'assets/css/public' . AC_PREFIX . '.css', array(), AC_VERSION, 'all' );

}
add_action( 'wp_enqueue_scripts', '_ac_enqueue_assets', 99 );
/**
 * Contextually include files dependent on context
 */
function _ac_contextual_include() {

	$context = _ac_get_context();

	if( file_exists( AC_ROOT_DIR . 'include/' . $context . '.php' ) ) {
		include AC_ROOT_DIR . 'include/' . $context . '.php';
	}
}
add_action( 'wp', '_ac_contextual_include' );