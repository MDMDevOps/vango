<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin admin area.
 * This file also defines plugin parameters, registers the activation and deactivation functions, and defines a function that starts the plugin.
 * @link    https://bitbucket.org/midwestdigitalmarketing/cornerstone/
 * @since   1.0.0
 * @package mdm_cornerstone
 *
 * @wordpress-plugin
 * Plugin Name: MDM Cornerstone
 * Plugin URI:  https://bitbucket.org/midwestdigitalmarketing/cornerstone/
 * Description: Site specific plugin functionality
 * Version:     0.1.0
 * Author:      Mid-West Digital Marketing
 * Author URI:  http://midwestdigitalmarketing.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mdm_cornerstone
 */

namespace mdm\cornerstone;

// If this file is called directly, abort
if ( !defined( 'WPINC' ) ) {
    die( 'Cannot load file directly' );
}

if( !class_exists( 'MDMCornerStone' ) ) {
	/**
	 * Version of the plugin
	 * @since 1.0.0
	 */
	define( 'MDM_CORNERSTONE_VERSION', '2.1.0' );
	/**
	 * URL to the appropriate root directory
	 * @since 1.0.0
	 */
	define( 'MDM_CORNERSTONE_URL', plugin_dir_url( __FILE__ ) . 'mdm-cornerstone/' );
	/**
	 * Path to the appropriate root directory
	 * @since 1.0.0
	 */
	define( 'MDM_CORNERSTONE_DIR', plugin_dir_path( __FILE__ ) . 'mdm-cornerstone/' );
	/**
	 * Include composer autoload file
	 */
	require_once MDM_CORNERSTONE_DIR . 'vendor/autoload.php';
	/**
	 * Main plugin class
	 * Only used to instantiate the individual classes, and to provide an easier
	 * way to access framework methods within our themes
	 *
	 */
	class MDMCornerStone extends Framework {

		public function __construct() {
			/**
			 * Construct parent
			 */
			parent::__construct();
			/**
			 * Setup environment variables
			 */
			$this->setEnvironment();
			/**
			 * Kickoff the plugin
			 */
			$this->burnBabyBurn();
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
			include_once $this->path( 'vendor/advanced-custom-fields/advanced-custom-fields-pro/acf.php' );
			$this->subscriber->addAction( 'init', [$this, 'registerPostTypes'] );
			$this->subscriber->addAction( 'init', [$this, 'registerTaxonomies'] );
			$this->subscriber->addAction( 'widgets_init', [$this, 'registerWidgets'] );
			/**
			 * Debugging scripts specific to development environments
			 */
			if ( $this->isDev() ) {
				$this->subscriber->addAction( 'wp_footer', [$this, 'printHooks'], 99 );
				$this->subscriber->addAction( 'admin_footer', [$this, 'printHooks'], 99 );
			}
		}
		/**
		 * Kickoff operation of the plugin
		 * Light the fires, and burn the tires
		 *
		 * @access private
		 */
		private function burnBabyBurn() {

			$classes = $this->getClasses( 'includes' );

			foreach( $classes as $class ) {

				$class = __NAMESPACE__ . '\\' . $class;

				if ( !is_subclass_of( $class, __NAMESPACE__ . '\\Framework' ) ) {
					continue;
				}

				new $class();
			}
		}
		/**
		 * Register custom post types
		 */
		public function registerPostTypes() {

			$post_types = $this->getClasses( 'posttypes' );

			foreach( $post_types as $post_type_name ) {

				$post_type = __NAMESPACE__ . '\\posttypes\\' . $post_type_name;

				$post_type = new $post_type();

				$post_type->register();

			}
		}
		/**
		 * Register custom taxonomies
		 */
		public function registerTaxonomies() {

			$taxonomies = $this->getClasses( 'taxonomies' );

			foreach( $taxonomies as $taxonomy_name ) {

				$taxonomy =  __NAMESPACE__ . '\\taxonomies\\' . $taxonomy_name;

				$taxonomy = new $taxonomy();

				$taxonomy->register();
			}
		}
		/**
		 * Register custom widgets
		 */
		public function registerWidgets() {

			$widgets = $this->getClasses( 'widgets' );

			foreach( $widgets as $widget_name ) {

				$widget = __NAMESPACE__ . '\\widgets\\' . $widget_name;

				register_widget( $widget );
			}
		}
		/**
		 * Set up the environment for local or staging development
		 *
		 * @access private
		 */
		private function setEnvironment() {

			if ( !defined( 'WP_ENVIRONMENT_TYPE' ) && strripos($_SERVER['HTTP_HOST'], 'mdmserver.us') ) {
				define( 'WP_ENVIRONMENT_TYPE', 'staging' );
			}

			elseif ( !defined( 'WP_ENVIRONMENT_TYPE' ) && strripos($_SERVER['HTTP_HOST'], '.local') ) {
				define( 'WP_ENVIRONMENT_TYPE', 'development' );
			}
		}
		/**
		 * Maybe print filters/hooks for debugging purposes
		 */
		public function printHooks() {
			global $wp_filter;
			$this->expose( $wp_filter );
		}
	}
}
new MDMCornerStone();