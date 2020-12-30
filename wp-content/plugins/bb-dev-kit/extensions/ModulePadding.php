<?php
/**
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\extensions;

use \wpcl\bbdk\includes\Css;
use \wpcl\bbdk\includes\Framework;

class ModulePadding extends Framework {
	/**
	 * Register filters
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addFilters() {
		$this->subscriber->addFilter( 'fl_builder_register_settings_form', [$this, 'extendSettingsForm'] , 10, 2 );
		$this->subscriber->addFilter( 'fl_builder_render_css', [$this, 'render'], 10, 4 );
	}
	/**
	 * Extend Settings Form
	 * @param  [array] $form : The settings array for the row settings form
	 * @param  [string] $id  : The id of the form
	 * @return [array]       : The (maybe) modified form
	 */
	public function extendSettingsForm( $form, $id ) {
		if( $id === 'module_advanced' ) {
			$form[ 'sections' ][ 'margins' ][ 'fields' ]['module_padding'] = [
				'type' => 'dimension',
				'label' => __( 'Padding', 'bb_dev_kit' ),
				'units' => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px',
				'responsive' => true,
				'slider' => true,
				'preview' => [
					'type' => 'css',
					'selector' => '.fl-module-content',
					'property' => 'padding',
				],
			];
		}
		return $form;
	}
	/**
	 * Rendor CSS
	 */
	public function render( $css, $nodes, $global_settings, $include_global ) {
		/**
		 * Create instance of CSS Class
		 */
		$compiler = new Css();

		foreach( $nodes as $node ) {

			foreach( $node as $module ) {
				/**
				 * Bail if not a module
				 */
				if( $module->type !== 'module' ) {
					continue;
				}
				/**
				 * Bail if settings are invalid
				 */
				if( !isset( $module->settings ) || !is_object( $module->settings ) ) {
					continue;
				}
				/**
				 * Setup args
				 */
				$args = [
					'module' => $module,
					'settings' => $module->settings,
					'selector' => ".fl-node-{$module->node}",
					'scss' => $this->path( 'extensions/includes/modulepadding.scss' ),
					'fields' => [
						'module_padding' => 'dimension',
					]
				];
				/**
				 * Convert settings to scss variables
				 */
				$args['vars'] = $compiler->extractFields( $args );
				/**
				 * Compile CSS
				 */
				$css .= $compiler->renderModuleCss( $args );
			}
		}
		return $css;
	}
}