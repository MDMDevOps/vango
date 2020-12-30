<?php
/**
 * The plugin file that controls core wp tweaks and configurations
 *
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_wp_cornerstone
 */

namespace mdm\cornerstone;

class FrontEnd extends Framework{
	/**
	 * Register actions
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addActions() {
		$this->subscriber->addAction( 'wp_enqueue_scripts', [$this, 'enqueueScripts'] );
		$this->subscriber->addAction( 'wp_enqueue_scripts', [$this, 'enqueueStyles'] );
		$this->subscriber->addAction( 'init', [$this, 'cleanHead'] );
	}
	/**
	 * Register filters
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addFilters() {
		$this->subscriber->addFilter( 'tiny_mce_plugins', [$this, 'disableEmojisTinymce'] );
		$this->subscriber->addFilter( 'wp_resource_hints', [$this, 'disableEmojisRemoveDnsPrefetch'], 10, 2 );
		$this->subscriber->addFilter( 'jetpack_lazy_images_blacklisted_classes', [$this, 'disableLazyLoadForLogo'] );
		$this->subscriber->addFilter( 'gform_enable_field_label_visibility_settings', '__return_true' );
		$this->subscriber->addFilter( 'fl_builder_render_assets_inline', '__return_true' );
	}
	/**
	 * Enqueue Frontend Javascript Files
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 */
	public function enqueueScripts() {
		wp_enqueue_script(
			'mdm_cornerstone_public',
			$this->url( 'assets/js/public.js' ),
			['jquery'],
			MDM_CORNERSTONE_VERSION,
			true
		);
		wp_localize_script(
			'mdm_cornerstone_public',
			'mdm_cornerstone', [
				'wpajaxurl' => admin_url( 'admin-ajax.php'),
				'pluginurl' => $this->url(''),
			]
		);
	}
	/**
	 * Enqueue Frontend CSS Files
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
	 */
	public function enqueueStyles() {
		wp_enqueue_style(
			'mdm_cornerstone_public',
			$this->url( 'assets/css/public.css' ),
			[],
			MDM_CORNERSTONE_VERSION,
			'all'
		);
	}
	/**
	 * Remove unneccessary functions form the header
	 * Mostly things that are annoying at best, and a security issue at worse
	 */
	public function cleanHead() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'edd_version_in_header' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	* Filter function used to remove the tinymce emoji plugin.
	*
	* @param array $plugins
	* @return array Difference betwen the two arrays
	*/
	function disableEmojisTinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, ['wpemoji'] );
		} else {
			return [];
		}
	}

	/**
	* Remove emoji CDN hostname from DNS prefetching hints.
	*
	* @param array $urls URLs to print for resource hints.
	* @param string $relation_type The relation type the URLs are printed for.
	* @return array Difference betwen the two arrays.
	*/
	function disableEmojisRemoveDnsPrefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			/** This filter is documented in wp-includes/formatting.php */
			$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
			$urls = array_diff( $urls, [$emoji_svg_url] );
		}
		return $urls;
	}
	/**
	 * Prevent jetpack lazy loading on logo
	 * @param  array $classes : array of classes NOT to use lazy loading
	 * @return $classes
	 */
	public function disableLazyLoadForLogo( $classes ) {
		$classes[] = 'custom-logo';
		return $classes;
	}

}