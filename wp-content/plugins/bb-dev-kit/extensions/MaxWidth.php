<?php
/**
 * @link    https://www.wpcodelabs.com
 * @since   1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\extensions;

use \wpcl\bbdk\includes\Css;
use \wpcl\bbdk\includes\Framework;

class MaxWidth extends Framework {
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
		if( $id === 'col' ) {
			$form['tabs']['advanced'][ 'sections' ][ 'margins' ][ 'fields' ]['max_width'] = array(
				'type' => 'unit',
				'label' => __( 'Max Width', 'bb_dev_kit' ),
				'units' => array( 'px', 'vw', '%' ),
				'default_unit' => 'px',
				'responsive' => true,
				'preview' => array(
					'type' => 'refresh',
				),
			);
			$form['tabs']['advanced'][ 'sections' ][ 'margins' ][ 'fields' ]['node_alignment'] = array(
				'type' => 'align',
				'label' => __( 'Node Alignment', 'bb_dev_kit' ),
				'responsive' => true,
				'values' => array(
					'left' => '0 auto 0 0',
					'center' => '0 auto',
					'right' => '0 0 0 auto',
				),
				'preview' => array(
					'type' => 'refresh',
				),
			);
		}
		else if( $id === 'module_advanced' ) {
			$form[ 'sections' ][ 'margins' ][ 'fields' ]['module_padding'] = array(
				'type' => 'dimension',
				'label' => __( 'Padding', 'bb_dev_kit' ),
				'units' => array( 'px', 'em', 'rem', '%' ),
				'default_unit' => 'px', // Optional
				'responsive' => true,
				'slider' => true,
				'preview' => array(
					'type' => 'css',
					'selector' => '.fl-module-content',
					'property' => 'padding',
				),
			);
			$form[ 'sections' ][ 'margins' ][ 'fields' ]['max_width'] = array(
				'type' => 'unit',
				'label' => __( 'Max Width', 'bb_dev_kit' ),
				'units' => array( 'px', 'vw', '%' ),
				'default_unit' => 'px', // Optional
				'responsive'  => true,
				'slider' => [
					'min' => 0,
					'max' => 1000,
					'step' => 10,
				],
				'preview' => array(
					'type' => 'refresh',
				),
			);
			$form[ 'sections' ][ 'margins' ][ 'fields' ]['node_alignment'] = array(
				'type' => 'align',
				'label' => __( 'Node Alignment', 'bb_dev_kit' ),
				'responsive' => true,
				'values' => array(
					'left' => '0 auto 0 0',
					'center' => '0 auto',
					'right' => '0 0 0 auto',
				),
				'preview' => array(
					'type' => 'refresh',
				),
			);
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

		foreach ( $nodes as $node ) {

			foreach ( $node as $module ) {

				if ( !isset( $module->settings ) || !is_object( $module->settings ) ) {
					continue;
				}
				/**
				 * Setup args
				 */
				$args = [
					'module' => $module,
					'settings' => $module->settings,
					'selector' => $module->type === 'column' ? ".fl-node-{$module->node} > .fl-col-content" : ".fl-node-{$module->node}",
					'scss' => self::path( 'extensions/includes/maxwidth.scss' ),
					'fields' => [
						'max_width' => 'unit',
						'node_alignment' => 'string',
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