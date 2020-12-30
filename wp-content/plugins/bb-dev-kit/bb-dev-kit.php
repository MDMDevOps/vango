<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin admin area.
 * This file also defines plugin parameters, registers the activation and deactivation functions, and defines a function that starts the plugin.
 * @link    https://github.com/WPCodeLabs/WP-Query-Engine
 * @since   1.0.0
 * @package bb_dev_kit
 *
 * @wordpress-plugin
 * Plugin Name: Dev Kit for Beaver Builder
 * Plugin URI:  https://www.wpcodelabs.com/downloads/dev-kit-for-beaver-builder/
 * Description: Advanced extensions and modules for beaver builder
 * Version:     0.1.0
 * Author:      WP Code Labs
 * Author URI:  https://www.wpcodelabs.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: bb_dev_kit
 */

namespace wpcl\bbdk;

/**
 * If this file is called directly, abort
 */
if ( !defined( 'WPINC' ) ) {
	die( 'Abort' );
}

if( !class_exists( 'BBDevKit' ) ) {

	define( 'BBDK_ROOT', __FILE__ );

	define( 'BBDK_VERSION', '0.1.0' );

	require_once __DIR__ . '/vendor/autoload.php';

	class BBDevKit extends includes\Framework {

		public function __construct() {
			/**
			 * Check that beaver builder is active
			 */
			if( $this->isPluginActive('bb-plugin/fl-builder.php') === false && $this->isPluginActive('beaver-builder-lite-version/fl-builder.php') === false ) {
				return false;
			}
			/**
			 * Construct parent
			 */
			parent::__construct();
			/**
			 * Kickoff the plugin
			 */
			$this->burnBabyBurn();
			/**
			 * Load text domain
			 */
			load_plugin_textdomain( 'bb_dev_kit', false, basename( dirname( BBDK_ROOT ) ) . '/languages' );
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
			$this->subscriber->addAction( 'init', [$this, 'registerModules'] );
			$this->subscriber->addAction( 'wp_enqueue_scripts', [$this, 'registerScripts'] );
		}
		/**
		 * Kickoff operation of the plugin
		 * Light the fires, and burn the tires
		 *
		 */
		public function burnBabyBurn() {

			new extensions\Scss();

			new extensions\ModulePadding();

			new extensions\MaxWidth();

			new extensions\StackingOrder();

			new extensions\PresetClasses();

		}
		/**
		 * Register flbuilder modules
		 */
		public function registerModules() {

			if( !class_exists( 'FLBuilder' ) ) {
				return false;
			}

			modules\button\DKButton::register();

			modules\iframe\DKIframe::register();

			modules\gravityform\DKGravityForm::register();

			modules\simplemenu\DKSimpleMenu::register();

			modules\gallery\DKGallery::register();

		}
		/**
		 * Register the javascript for the frontend
		 *
		 * @since 1.0.0
		 */
		public function registerScripts() {
			wp_register_script( 'bbdk/gallaries', plugin_dir_url( BBDK_ROOT ) . 'assets/js/gallaries.min.js', [], BBDK_VERSION, true );
			// wp_register_script( 'bbdk/slick', plugin_dir_url( BBDK_ROOT ) . 'assets/js/slick.js', ['jquery'], BBDK_VERSION, true );
			// wp_register_script( 'bbdk/masonry', plugin_dir_url( BBDK_ROOT ) . 'assets/js/masonry.js', ['jquery'], BBDK_VERSION, true );
			// wp_register_style( 'bbdk/slick', plugin_dir_url( BBDK_ROOT ) . 'assets/css/slick.css', [], BBDK_VERSION, 'all' );
		}

		public function getTemplatePart( $name = '' ) {

			$slug = apply_filters( 'bbdk/template_path', 'template-parts/bbdk' );

			$template = locate_template( $template_path, false, false );

			return !empty( $template ) ? $template : false;

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
	}
}
$BBDevKit = new BBDevKit();
