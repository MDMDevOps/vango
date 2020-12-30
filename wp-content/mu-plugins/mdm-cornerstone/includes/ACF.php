<?php
/**
 * The plugin file that controls functions related to Advanced Custom Fields
 *
 * ACF is used with our themes and other places, so this loads it if needed and
 * syncs some files
 *
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_wp_cornerstone
 */

namespace mdm\cornerstone;

class ACF extends Framework {
	/**
	 * Load ACF from here instead of from installed plugin
	 *
	 * @method __construct
	 * @return $this
	 */
	public function __construct() {
		/**
		 * Construct parent
		 */
		parent::__construct();
		/**
		 * Maybe hide the admin menu item for ACF
		 *
		 * If a user has activated the plugin themselves, allow it to be shown. Otherwise hide *our* instance of it
		 */
		if( $this->isPluginActive( 'advanced-custom-fields-pro/acf.php' ) === false && $this->isDev() === false ) {
			// $this->subscriber->addFilter( 'acf/settings/show_admin', '__return_false' );
		}
		/**
		 * Load ACF on the admin side
		 */
		if( is_admin() ) {
			$this->loadAcf();
		}
		/**
		 * Return this
		 */
		return $this;
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
		$this->subscriber->addFilter( 'acf/settings/save_json', [$this, 'acfSavePoint'] );
		$this->subscriber->addFilter( 'acf/settings/load_json', [$this, 'acfSavePoint'] );
	}
	/**
	 * Load Advanced Custom Fields from *this* vendor directory, instead of a plugin
	 */
	public function loadAcf() {

		$plugin = $this->isPluginActive( 'advanced-custom-fields-pro/acf.php' );

		if( $plugin ) {

			$composer = get_plugin_data( $this->path( 'vendor/advanced-custom-fields/advanced-custom-fields-pro/acf.php' ) );
			/**
			 * If plugin version is higher
			 */
			if ( version_compare( $plugin, $composer['Version'], '<' ) ) {
				$plugin = false;
			}
		}

		if( $plugin === false ) {
			include_once $this->path( 'vendor/advanced-custom-fields/advanced-custom-fields-pro/acf.php' );
			/**
			 * Correct ACF Url
			 */
			add_filter( 'acf/settings/url', function( $url ) {
				return $this->url( 'vendor/advanced-custom-fields/advanced-custom-fields-pro/' );
			});
		}
	}
	/**
	 * Save ACF Json in assets/json/* directory
	 *
	 * @param  string $path : path to save point
	 * @return path to our folder
	 * @see https://www.advancedcustomfields.com/resources/local-json/
	 */
	public function acfSavePoint( $path ) {
		return $this->path( 'assets/json' );
	}
	/**
	 * Load ACF Json files from assets/json/* directory
	 *
	 * @param  array $paths : paths to load json files from
	 * @return path to our folder
	 * @see https://www.advancedcustomfields.com/resources/local-json/
	 */
	public function acfLoadPoint( $paths ) {
		$paths[] = $this->path( 'assets/json' );
		return $paths;
	}

} // end class