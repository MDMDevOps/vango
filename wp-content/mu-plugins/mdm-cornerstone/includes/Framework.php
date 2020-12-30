<?php
/**
 * The common functions
 *
 * This file isn't instatiated directly, it acts as a shared parent for other classes
 *
 * @link http://midwestdigitalmarketing.com
 * @since 1.0.0
 * @package mdm_cornerstone
 */

namespace mdm\cornerstone;

class Framework {
	/**
	 * Subscriber
	 * @since 1.0.0
	 * @access protected
	 * @var (object) $subscriber : instance of the subscriber class
	 */
	protected $subscriber;
	/**
	 * Get subscriber instance
	 * Check if already registered, and run functions to register filters and actions
	 *
	 * @method __construct
	 * @return $this
	 */
	public function __construct() {
		/**
		 * Set subscriber
		 */
		$this->subscriber = Subscriber::getInstance();
		/**
		 * Conditionally add actions/filters, but only if they haven't already
		 * been added
		 */
		if( $this->subscriber->isRegistered( $this ) === false ) {
			/**
			 * Register this class
			 */
			$this->subscriber->registerClass( $this );
			/**
			 * Register actions
			 */
			$this->addActions();
			/**
			 * Register filters
			 */
			$this->addFilters();
			/**
			 * Register shortcodes
			 */
			$this->addShortcodes();
		}
		/**
		 * Return the object for use
		 */
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
		// Nothing to do here now, adding class so method exists for child classes
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
		// Nothing to do here now, adding class so method exists for child classes
	}
	/**
	 * Register shortcodes
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addShortcodes() {
		// Nothing to do here now, adding class so method exists for child classes
	}
	/**
	 * Helper function to use relative URLs
	 * @since 1.0.0
	 * @access protected
	 */
	public function url( $url = '' ) {
		return MDM_CORNERSTONE_URL . ltrim( $url, '/' );
	}
	/**
	 * Helper function to use relative paths
	 * @since 1.0.0
	 * @access protected
	 */
	public function path( $path = '' ) {
		return MDM_CORNERSTONE_DIR . ltrim( $path, '/' );
	}
	/**
	 * Helper function to get all classes inside a directory
	 */
	public function getClasses( $shortpath = '' ) {

		if( empty( $shortpath ) ) {
			return [];
		}

		$classes = [];

		$files = glob( trailingslashit( $this->path( $shortpath ) ) . '*.php' );

		foreach( $files as $file ) {
			$classes[] = str_replace( '.php', '', basename( $file ) );
		}

		return $classes;
	}
	/**
	 * Helper function to get template files
	 */
	public function getTemplate( $name ) {

		$theme_file = locate_template( "templates-parts/{$name}" );

		if( !empty( $theme_file ) ) {
			return $theme_file;
		}

		elseif( file_exists( $this->path( "templates/{$name}" ) ) ) {
			return $this->path( "templates/{$name}" );
		}
	}
	/**
	 * Helper function to expose errors and objects and stuff
	 *
	 * Prints PHP objects, errors, etc to the browswer console using either the
	 * 'wp_footer', or 'admin_footer' hooks. Which are the final hooks that run reliably.
	 * @since  2.1.0
	 */
	public function expose( $object ) {
		add_action( 'shutdown', function() use( $object ) {
			printf( '<script>console.log(%s);</script>', json_encode( $object, JSON_PARTIAL_OUTPUT_ON_ERROR, JSON_PRETTY_PRINT ) );
		}, 9999 );
	}
	/**
	 * Helper function to determine if plugin is active or not
	 * Wrapper function for is_plugin_active core WP function
	 *
	 * @see https://developer.wordpress.org/reference/functions/is_plugin_active/
	 * @param string  $plugin : Path to the plugin file relative to the plugins directory
	 * @return boolean True, if in the active plugins list. False, not in the list.
	 */
	public function isPluginActive( $plugin = '' ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if( is_plugin_active( $plugin ) ) {
			$data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			return $data['Version'];
		} else {
			return false;
		}
	}
	/**
	 * Helper function to determin if in a development environment
	 * Checks against an array of development types
	 *
	 * @since  2.1.0
	 * @see  https://developer.wordpress.org/reference/functions/wp_get_environment_type/
	 */
	public function isDev() {
		if( function_exists('wp_get_environment_type') ) {
			return in_array( wp_get_environment_type(), ['staging', 'development', 'local'] ) || WP_DEBUG === true;
		} else {
			return WP_DEBUG;
		}
	}
} // end class