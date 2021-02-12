<?php
/**
 * Fromework class
 *
 * Defines common fields and methods shared by all plugin classes
 *
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\includes;

class Framework {
	/**
	 * Plugin Options
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $settings : The array that holds plugin options
	 */
	protected $loader;
	/**
	 * Instances
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $instances : Collection of instantiated classes
	 */
	protected static $instances = array();
	/**
	 * Whether or not to load a class
	 * @since 1.0.0
	 * @access protected
	 * @var (bool) $enabled : Flag to conditionally disable a class
	 */
	protected $enabled = true;
	/**
	 * Constructor
	 * @since 1.0.0
	 * @access protected
	 */
	protected function __construct() {
		// Nothing to do here at this time
	}
	/**
	 * Registers our plugin with WordPress.
	 */
	public static function register( $class_name = null ) {
		// Get called class
		$class_name = !is_null( $class_name ) ? $class_name : get_called_class();
		// Instantiate class
		$class = $class_name::get_instance( $class_name );
		// Check that it's enabled
		if( $class->enabled ) {
			// Create API manager
			$class->loader = Loader::get_instance();
			// Register stuff
			$class->loader->register( $class );
		}
		// Return instance
		return $class;
	}
	/**
	 * Gets an instance of our class.
	 */
	public static function get_instance( $class_name = null ) {
		// Use late static binding to get called class
		$class = !is_null( $class_name ) ? $class_name : get_called_class();
		// Get instance of class
		if( !isset(self::$instances[$class] ) ) {
			self::$instances[$class] = new $class();
		}
		return self::$instances[$class];
	}
	/**
	 * Helper function to use relative URLs
	 * @since 1.0.0
	 * @access protected
	 */
	public static function url( $url = '' ) {
		return plugin_dir_url( BBDK_ROOT ) . ltrim( $url, '/' );
	}
	/**
	 * Helper function to use relative paths
	 * @since 1.0.0
	 * @access protected
	 */
	public static function path( $path = '' ) {
		return plugin_dir_path( BBDK_ROOT ) . ltrim( $path, '/' );
	}
	/**
	 * Helper function to add to a list of errors
	 * Errors can later be printed out to the console
	 */
	public static function log( $error ) {

		$errors = get_transient( '_acl_error_log' );

		$errors = is_array( $errors ) ? $errors : array();

		$errors[] = $error;

		set_transient( '_acl_error_log', $errors );
	}
	/**
	 * Print the error log to console
	 */
	public function printLog() {

		$errors = get_transient( '_acl_error_log' );

		$errors = is_array( $errors ) ? $errors : array();

		if( !empty( $errors ) ) {
			printf( '<script id="debug">console.log( %s )</script>', json_encode( $errors ) );
		}

		delete_transient( '_acl_error_log' );
	}
}