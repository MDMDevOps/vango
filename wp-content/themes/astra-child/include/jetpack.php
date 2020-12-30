<?php
/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function _ac_jetpack_theme_support() {

	add_theme_support( 'jetpack-social-menu', 'dashicons' );

	add_theme_support( 'jetpack-responsive-videos' );

}
add_action( 'after_setup_theme', '_ac_jetpack_theme_support' );