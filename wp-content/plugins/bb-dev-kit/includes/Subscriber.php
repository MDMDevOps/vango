<?php
/**
 * API_Manager handles registering actions and hooks with the
 * WordPress Plugin API.
 *
 *
 * @link https://www.wpcodelabs.com
 * @since 2.0.0
 * @package bb_dev_kit
 */

namespace wpcl\bbdk\includes;

class Subscriber {
	/**
	 * Instances
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $instances : Collection of instantiated classes
	 */
	protected $instances = [];
	/**
	 * Instance
	 * @since 1.0.0
	 * @access protected
	 * @var (object) $instance : Instance of self
	 */
	protected static $instance;
	/**
	 * Constructor
	 * @since 1.0.0
	 * @access protected
	 */
	protected function __construct() {
		// do nothing here
	}

	/**
	 * Register a class instance for reference
	 *
	 * @since  1.0.0
	 * @param string/obj $class : Class name or instance
	 * @return obj instance of the registered class
	 */
	public function registerClass( $class = '' ) {
		$classname = is_object( $class ) ? get_class( $class ) : $class;
		/**
		 * If we are passed an instance of a class
		 */
		if( is_object( $class ) && !isset( $this->instances[$classname] ) ) {
			$this->instances[ $classname ] = $class;
		}
		/**
		 * Else if we are passed a string/classname
		 */
		elseif( is_string( $class ) && !isset( $this->instances[$classname] ) && class_exists( $class ) ) {
			$this->instances[ $classname ] = new $class();
		}

		return $this->instances[ $classname ];

	}
	/**
	 * Check if a particular class is already registered
	 *
	 * @since  1.0.0
	 * @param string/obj $class : Class name or instance
	 * @return bool
	 */
	public function isRegistered( $class = '' ) {

		$classname = is_object( $class ) ? get_class( $class ) : $class;

		return isset( $this->instances[ $classname ] );
	}
	/**
	 * Check if a particular instance of a class is the registered instance
	 *
	 * @since  1.0.0
	 * @param obj $object : Instance of a class
	 * @return bool
	 */
	public function isRegisteredInstance( $object ) {

		if ( !is_object( $object ) ) {
			return false;
		}

		if( !isset( $this->instances[ get_class( $class ) ] ) ) {
			return false;
		}

		if( $this->instances[ get_class( $class ) ] === $object ) {
			return true;
		}

		return false;
	}
	/**
	 * Get the registered instance of a particular class
	 *
	 * @since  1.0.0
	 * @param string/obj $class : Class name or instance
	 * @return bool
	 */
	public function getRegisteredInstance( $classname = '' ) {

		return isset( $this->instances[ $classname ] ) ? $this->instances[ $classname ] : false;
	}
	/**
	 * Get registered instance of THIS class
	 *
	 * @since  1.0.0
	 * @return obj Instance of this class
	 */
	public static function getInstance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Hooks a function on to a specific action.
	 *
	 * Exactly like wordpress native add_action, which calls add_filter,
	 * this is just a wrapper for addFilter
	 *
	 * @param string $hook : The name of the filter to hook the $function_to_add callback to.
	 * @param callable $function : The callback to be run when the filter is applied.
	 * @param int $priority  :Optional. Used to specify the order in which the functions
	 * @param int $argument_count   Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @see  https://developer.wordpress.org/reference/functions/add_action/
	 */
	public function addAction( $hook = '', $function = '', $priority = 10, $argument_count = 1 ) {
		$this->addFilter( $hook, $function, $priority, $argument_count );
	}
	/**
	 * Removes a function from a specified action hook.
	 *
	 * Exactly like wordpress native remove_action, which calls remove_filter,
	 * this is just a wrapper for removeFilter
	 *
	 * @param string $tag : The action hook to which the function to be removed is hooked.
	 * @param callable $function : The name of the function which should be removed.
	 * @param int $priority : Optional. The priority of the function. Default 10.
	 *
	 * @see  https://developer.wordpress.org/reference/functions/remove_action/
	 */
	public function removeAction( $hook = '', $function = '', $priority = 10 ) {
		$this->removeFilter( $hook, $function, $priority );
	}
	/**
	 * Checks if a specific action has been registered for this hook.
	 *
	 * Wrapper for hasFilter
	 *
	 * @since 1.0.0
	 *
	 * @param string hook The name of the filter hook. Default empty.
	 * @param callable|bool $function Optional. The callback to check for. Default false.
	 * @return bool|int The priority of that hook is returned, or false if the function is not attached.
	 * @see  https://developer.wordpress.org/reference/functions/has_action/
	 */
	public function hasAction( $hook = '', $function = false ) {
		return $this->hasFilter( $hook, $function );
	}
	/**
	 * Hook a function or method to a specific filter action.
	 *
	 * @param string $hook : The name of the filter to hook the $function_to_add callback to.
	 * @param callable $function : The callback to be run when the filter is applied.
	 * @param int $priority  :Optional. Used to specify the order in which the functions
	 * @param int $argument_count   Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_filter/
	 */
	public function addFilter( $hook = '', $function = '', $priority = 10, $argument_count = 1 ) {

		if( is_array( $function ) ) {
			/**
			 * If we were passed in instance of an object
			 */
			if( is_object( $function[0] ) ) {
				/**
				 * Either register or get the instance of the object from our own
				 * classlist
				 */
				$instance = $this->registerClass( $function[0] );
				/**
				 * Add the filter
				 */
				add_filter( $hook, [ $instance, $function[1] ], $priority, $argument_count );
			}
			/**
			 * Else just assume we were passed the correct instance
			 */
			else {
				add_filter( $hook, [ $function[0], $function[1] ], $priority, $argument_count );
			}

		}
		/**
		 * Else if we are just passed a string
		 */
		else {
			add_filter( $hook, $function, $priority );
		}
	}
	/**
	 * Removes a function from a specified filter hook.
	 *
	 * This function removes a function attached to a specified filter hook. This
	 * method can be used to remove default functions attached to a specific filter
	 * hook and possibly replace them with a substitute.
	 *
	 * @param string $tag : The action hook to which the function to be removed is hooked.
	 * @param callable $function : The name of the function which should be removed.
	 * @param int $priority : Optional. The priority of the function. Default 10.
	 *
	 * @see  https://developer.wordpress.org/reference/functions/remove_action/
	 */
	public function removeFilter( $hook = '', $function = '', $priority = 10 ) {
		if( is_array( $function ) ) {
			/**
			 * If is an active object
			 */
			if( is_object( $function[0] ) ) {
				/**
				 * If we have an instnce of that object
				 */
				if( isset( $this->instances[get_class($function[0])] ) ) {
					remove_filter( $hook, [ $this->instances[get_class($function[0])], $function[1] ], $priority );
				}
				/**
				 * Else just assume we were passed the correct instance
				 */
				else {
					remove_filter( $hook, [ $function[0], $function[1] ], $priority );
				}
			}
			/**
			 * If the function is the string of a classname
			 */
			elseif( is_string( $function[0] ) ) {
				/**
				 * If we have an instnce of that classname
				 */
				if( isset( $this->instances[$function[0]] ) ) {
					remove_filter( $hook, [ $this->instances[$function[0]], $function[1] ], $priority );
				}
				/**
				 * Else remove static function
				 */
				remove_filter( $hook, $function[0] . '::' . $function[1], $priority );
			}
		}
		/**
		 * Else if we are just passed a string
		 */
		else {
			remove_filter( $hook, $function, $priority );
		}
	}
	/**
	 * Checks if a specific action has been registered for this hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string hook The name of the filter hook. Default empty.
	 * @param callable|bool $function Optional. The callback to check for. Default false.
	 * @return bool|int The priority of that hook is returned, or false if the function is not attached.
	 * @see  https://developer.wordpress.org/reference/functions/has_filter/
	 */
	public function hasFilter( $hook = '', $function = false ) {

		$has_filter = false;

		if( is_array( $function ) ) {
			/**
			 * If is an active object
			 */
			if( is_object( $function[0] ) ) {
				/**
				 * If we have an instnce of that object
				 */
				if( isset( $this->instances[get_class($function[0])] ) ) {
					$has_filter = has_filter( $hook, [ $this->instances[get_class($function[0])], $function[1] ] );
				}
				/**
				 * Else just assume we were passed the correct instance
				 */
				else {
					$has_filter = has_filter( $hook, [ $function[0], $function[1] ] );
				}
			}
			/**
			 * If the function is the string of a classname
			 */
			elseif( is_string( $function[0] ) ) {
				/**
				 * If we have an instnce of that classname
				 */
				if( isset( $this->instances[$function[0]] ) ) {
					$has_filter = has_filter( $hook, [ $this->instances[$function[0]], $function[1] ] );
				}
				/**
				 * Else remove static function
				 */
				else {
					$has_filter = has_filter( $hook, $function[0] . '::' . $function[1], $priority );
				}
			}
		}
		/**
		 * Else if we are just passed a string
		 */
		else {
			$has_filter = has_filter( $hook, $function, $priority );
		}
		return $has_filter;
	}
	/**
	 * Adds a new shortcode.
	 *
	 * Care should be taken through prefixing or other means to ensure that the
	 * shortcode tag being added is unique and will not conflict with other,
	 * already-added shortcode tags. In the event of a duplicated tag, the tag
	 * loaded last will take precedence.
	 *
	 * @param string $tag : Shortcode tag to be searched in post content.
	 * @param callable $callback : The callback function to run when the shortcode is found.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_shortcode/
	 */
	public function addShortcode( $hook = '', $function = '' ) {

		if( is_array( $function ) ) {
			/**
			 * If we were passed in instance of an object
			 */
			if( is_object( $function[0] ) ) {
				/**
				 * Either register or get the instance of the object from our own
				 * classlist
				 */
				$instance = $this->registerClass( $function[0] );
				/**
				 * Add the filter
				 */
				add_shortcode( $hook, [ $instance, $function[1] ] );
			}
			/**
			 * Else just assume we were passed the correct instance
			 */
			else {
				add_shortcode( $hook, [ $function[0], $function[1] ] );
			}
		}
		/**
		 * Else if we are just passed a string
		 */
		else {
			add_shortcode( $hook, $function );
		}
	}
}