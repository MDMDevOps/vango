<?php
/**
 * @link https://www.wpcodelabs.com
 * @since 1.0.0
 * @package bb_dev_kit
 */
namespace wpcl\bbdk\extensions;

use \wpcl\bbdk\includes\Css;
use \wpcl\bbdk\includes\Framework;

class Scss extends Framework {
	/**
	 * Register actions
	 *
	 * Uses the subscriber class to ensure only actions of this instance are added
	 * and the instance can be referenced via subscriber
	 *
	 * @since 1.0.0
	 */
	public function addActions() {
		$this->subscriber->addFilter( 'wp_enqueue_scripts', [$this, 'enqueueScripts'] );
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
		$this->subscriber->addFilter( 'fl_builder_register_settings_form', [$this, 'extendSettingsForm'] , 15, 2 );
		$this->subscriber->addFilter( 'fl_builder_render_css', [$this, 'render'], 10, 4 );
		$this->subscriber->addFilter( 'fl_builder_custom_fields', [$this, 'registerField'] );
	}
	/**
	 * Enqueue scripts/styles necessary for custom scss field
	 */
	public function enqueueScripts() {
		/**
		 * Don't need to enqueue files if builder is not open and active
		 */
		if( \FLBuilderModel::is_builder_active() === false ) {
			return false;
		}

		wp_enqueue_style( 'ui-field-scss', $this->url( 'assets/css/ui-field-scss.css' ), [], BBDK_VERSION, 'all' );

		wp_enqueue_script( 'be_ace', $this->url( 'assets/js/ace/src-min/ace.js' ), [], BBDK_VERSION, true );
		wp_enqueue_script( 'devkit_scss', $this->url( 'assets/js/ui-field-scss.js' ), ['jquery'], BBDK_VERSION, true );

		wp_localize_script( 'devkit_scss', 'devkit_scss', array( 'baseurl' => $this->url() ) );
	}

	/**
	 * Extend Settings Form
	 * @param  [array] $form : The settings array for the row settings form
	 * @param  [string] $id  : The id of the form
	 * @return [array]       : The (maybe) modified form
	 */
	public function extendSettingsForm( $form, $id ) {

		if( !in_array( $id, ['row', 'col', 'module_advanced'] ) ) {
			return $form;
		}

		$prefix = [
			'row' => '.fl-node-$id.fl-row',
			'col' => '.fl-node-$id.fl-col',
			'module_advanced' => '.fl-node-$id .fl-module-$slug',
		];

		$field = [
			'title' => __( 'Custom SCSS', 'bb_dev_kit' ), // Section Title
			'fields' => [
				'custom_scss' => [
					'type' => 'devkit_scss',
					'label' => '',
					'prefix' => $prefix[$id],
					'default' => '',
					'preview' => [
						'type' => 'refresh',
					],
				],
			],
		];

		if( $id === 'module_advanced' ) {
			$form[ 'sections' ][ 'custom_scss' ] = $field;
		} else {
			$form['tabs']['advanced'][ 'sections' ][ 'custom_scss' ] = $field;
		}

		return $form;
	}
	/**
	 * Register custom field with BB
	 * @param  array $fields : array of field names / files
	 * @return $fields
	 */
	public function registerField( $fields ) {
		$fields['devkit_scss'] = $this->path( 'fields/ui-field-scss.php' );
		return $fields;
	}
	/**
	 * Rendor custom SCSS
	 */
	public function render( $css, $nodes, $global_settings, $include_global ) {
		/**
		 * Create instance of CSS Class
		 */
		$compiler = new Css();

		foreach( $nodes as $node ) {

			foreach( $node as $module ) {

				if( !in_array( $module->type, array( 'row', 'column', 'module' ) ) ) {
					continue;
				}
				/**
				 * Set prefix
				 */
				switch ( $module->type ) {
					case 'row':
						$prefix = ".fl-node-{$module->node}.fl-row";
						break;
					case 'column':
						$prefix = ".fl-node-{$module->node}.fl-col";
						break;
					default:
						$prefix = ".fl-node-{$module->node}.fl-module-{$module->slug}";
						break;
				}
				/**
				 * Compile and render css
				 */
				if( isset( $module->settings->custom_scss ) && !empty( $module->settings->custom_scss ) ) {
					/**
					 * Construct scss
					 */
					$scss  = '$medium-breakpoint : ' . $global_settings->medium_breakpoint . 'px;';
					$scss .= '$responsive-breakpoint : ' . $global_settings->responsive_breakpoint . 'px;';
					$scss .= "$prefix { {$module->settings->custom_scss} }";
					/**
					 * Append compiled css
					 */
					$css .= $compiler->compile( $scss );
				}
			}
		}
		return $css;
	}
}