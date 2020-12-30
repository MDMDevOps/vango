<?php
/**
 * The plugin file that controls the admin functions
 *
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_wp_cornerstone
 */

namespace mdm\cornerstone;

class FLBuilder extends Framework {
	/**
	 * Check if Beaver Builder plugin is active and construct
	 *
	 * @method __construct
	 * @return $this
	 */
	public function __construct() {
		if( $this->isPluginActive('bb-plugin/fl-builder.php') ) {
			parent::__construct();
		}
		return $this;
	}
	/**
	 * Register actions
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addActions() {
		$this->subscriber->addAction( 'init', [$this, 'register_modules'] );
	}
	/**
	 * Register custom beaver builder modules
	 */
	public function register_modules() {

		if( !class_exists( 'FLBuilder' ) ) {
			return false;
		}

		$modules = glob( $this->path( 'flbuilder/*' ), GLOB_ONLYDIR );

		foreach( $modules as $module ) {

			$module = basename( $module );

			// include $this->path( 'flbuilder/' . $module . '/' . $module . '.php' );

			$module = __NAMESPACE__ . '\\flbuilder\\' . $module . '\\' . $module;

			$instance = new $module();

			$instance->register();
		}
	}

} // end class