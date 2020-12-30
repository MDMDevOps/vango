<?php
/**
 * Post Types
 *
 * Registers custom post types with WordPress
 *
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\includes;

use \wpcl\bbdk\includes\Css;

class Module extends \FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct( $args ) {
		// $this->css = new Css();
		/**
		 * Add filter to allow themes to override specific module frontend
		 */
		// add_filter( 'fl_builder_module_frontend_file', [ $this, 'frontendThemeOverride' ], 10, 2 );
		/**
		 * Construct our parent class (FLBuilderModule);
		 */
		parent::__construct( $args );
	}

	public function frontendThemeOverride(  $path, $module ) {

		if( !is_subclass_of( $module, __CLASS__ ) ) {
			return $path;
		}

		$class = new \ReflectionClass( $module );

		$slug = apply_filters( 'bbdk/template_path', 'template-parts' );

		$template_path = trailingslashit( $slug ) . strtolower( $class->getShortName() ) . '.php';

		$template_path = apply_filters( "bbdk/template_path/{$class->getShortName()}", $template_path );

		$template = locate_template( $template_path, false, false );

		return !empty( $template ) ? $template : $path;

	}


	public function moduleCss( $args = [] ) {
		/**
		 * Create instance of CSS Class
		 */
		$css = new Css();

		$defaults = [
			'module' => '',
			'settings' => '',
			'selector' => '',
			'form' => '',
			'scss' => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if( !is_object( $args['module'] ) ) {
			return;
		}
		/**
		 * Maybe add form if none is passed in
		 */
		if( empty( $args['form'] ) ) {
			$args['form'] = $args['module']->form;
		}
		/**
		 * Maybe add settings if none is passed
		 */
		if( empty( $args['settings'] ) ) {
			$args['settings'] = $args['module']->settings;
		}
		/**
		 * Maybe add scss file
		 */
		if( empty( $args['scss'] ) && file_exists( $args['module']->dir . 'includes/frontend.scss') ) {
			$args['scss'] = $args['module']->dir . 'includes/frontend.scss';
		}
		/**
		 * Get the fields needed to convert settings to scss variables
		 */
		$args['fields'] = $css->getCssFields( $args );
		/**
		 * Convert settings to scss variables
		 */
		$args['vars'] = $css->extractFields( $args );
		/**
		 * Render css from scss
		 */
		return $css->renderModuleCss( $args );

	}

}