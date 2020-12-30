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

use Padaliyajay\PHPAutoprefixer\Autoprefixer;
use ScssPhp\ScssPhp\Compiler;

class Css extends Framework {
	/**
	 * SCSS Compiler
	 * @since 1.0.0
	 * @access protected
	 * @var (object) $compiler : instance of the compiler class
	 * @see https://github.com/scssphp/scssphp/
	 */
	protected $compiler;
	/**
	 * Constructor
	 * Create class fields and construct parent
	 *
	 * @method __construct
	 * @return $this
	 */
	public function __construct() {
		parent::__construct();
		$this->compiler = new Compiler();
		return $this;
	}
	/**
	 * Compile a scss/css string to css
	 * Takes a string, and compiles scss and runs autoprefixr
	 *
	 * @since  1.0.0
	 * @param  string $scss scss/css string
	 * @return void
	 */
	public function compile( $scss = '' ) {

		if ( empty( $scss ) ) {
			return;
		}

		$css = '';

		try {

			$css = $this->compiler->compile( $scss );

			$autoprefixer = new Autoprefixer($css);

			$css = $autoprefixer->compile();

		} catch (\Exception $e) {

			// \wpcl\bbdk\BBDevKit::log( $e->getMessage() );

		}

		return $css;
	}
	/**
	 * Render a scss/css string
	 * Echo's result of compile function
	 *
	 * @since  1.0.0
	 * @param  string $scss : scss/css string
	 * @return void
	 */
	public function render( $scss = '' ) {

		if( empty( $scss ) ) {
			return;
		}

		echo $this->compile( $scss );
	}


	public function renderModuleCss( $args = array() ) {

		$defaults = array(
			'selector' => '',
			'scss'     => '',
			'vars'     => [ 'large' => '', 'medium' => '', 'responsive' => '' ],
		);

		$args = wp_parse_args( $args, $defaults );

		if( empty( $args['scss'] ) ) {
			return;
		}


		$css = '';

		foreach( $args['vars'] as $media => $vars ) {

			$args['media'] = $media;

			$css .= $this->getCss( $args );

		}

		return $css;

	}

	public function getCss( $args ) {

		$global = \FLBuilderModel::get_global_settings();

		$breakpoint = $args['media'] . '_breakpoint';

		ob_start();

		if( !empty( $args['vars'][$args['media']] ) ) {

			echo $args['vars'][$args['media']];

			if( $args['media'] !== 'large' ) {
				printf( '@media ( max-width: %spx ){', $global->$breakpoint );
			}

			printf( '%s {', $args['selector'] );

				if( file_exists( $args['scss'] ) ) {
					include $args['scss'];
				}

			echo '}';

			if( $args['media'] !== 'large' ) {
				echo '}';
			}

			return $this->compile( ob_get_clean() );

		}

	}

	/**
	 * Converts a set of fields to a usable set of scss variables
	 *
	 * @param  array  $fields array of fields that need extracted from settings
	 * @param  object $settings module settings object
	 * @return array $scss key/value array of scss variables
	 */
	public function extractFields( $args = array() ) {

		$defaults = [
			'settings' => '',
			'fields'   => [],
		];

		$args = wp_parse_args( $args, $defaults );

		if ( !is_object( $args['settings'] ) || empty( $args['fields'] ) ) {
			return $scss;
		}

		$scss = [
			'large'      => '',
			'medium'     => '',
			'responsive' => '',
		];

		foreach ( $args['fields'] as $key => $type ) {

			$m_key = $key . '_medium';

			$r_key = $key . '_responsive';

			$unit = $key . '_unit';

			$m_unit = $key . '_medium_unit';

			$r_unit = $key . '_responsive_unit';

			$directions = [ 'top', 'right', 'left', 'bottom' ];

			switch ( $type ) {
				case 'color':
					if ( !empty( $args['settings']->$key ) ) {
						$scss['large'] .= '$' . $key . ' : ' . \FLBuilderColor::hex_or_rgb( $args['settings']->$key ) . ';';
					}
					if ( isset( $args['settings']->$m_key ) && !empty( $args['settings']->$m_key ) ) {
						$scss['medium'] .= '$' . $key . ' : ' . \FLBuilderColor::hex_or_rgb( $args['settings']->$m_key ) . ';';
					}
					if ( isset( $args['settings']->$r_key ) && !empty( $args['settings']->$r_key ) ) {
						$scss['responsive'] .= '$' . $key . ' : ' . \FLBuilderColor::hex_or_rgb( $args['settings']->$r_key ) . ';';
					}
					break;
				case 'unit':
					if( isset( $args['settings']->$key  ) ) {
						if( !empty( $args['settings']->$key ) || $args['settings']->$key === '0' ) {

							$unit_value = isset( $args['settings']->$unit ) ? $args['settings']->$unit : '';

							$scss['large'] .= '$' . $key . ' : ' . $args['settings']->$key . $unit_value . ';';
						}
					}
					if( isset( $args['settings']->$m_key  ) ) {
						if( !empty( $args['settings']->$m_key ) || $args['settings']->$m_key === '0' ) {

							$m_unit_value = isset( $args['settings']->$m_unit ) ? $args['settings']->$m_unit : '';

							$scss['medium'] .= '$' . $key . ' : ' . $args['settings']->$m_key . $m_unit_value . ';';
						}
					}

					if( isset( $args['settings']->$r_key  ) ) {
						if( !empty( $args['settings']->$r_key ) || $args['settings']->$r_key === '0' ) {

							$r_unit_value = isset( $args['settings']->$r_unit ) ? $args['settings']->$r_unit : '';

							$scss['responsive'] .= '$' . $key . ' : ' . $args['settings']->$r_key . $r_unit_value . ';';
						}
					}
					break;
				case 'dimension':

					foreach( $directions as $direction ) {

						$setting = $key . '_' . $direction;

						$m_setting = $key . '_' . $direction . '_medium';

						$r_setting = $key . '_' . $direction . '_responsive';

						if( isset( $args['settings']->$setting  ) ) {

							if( !empty( $args['settings']->$setting ) || $args['settings']->$setting === '0' ) {
								$scss['large'] .= '$' . $setting . ' : ' . $args['settings']->$setting . $args['settings']->$unit . ';';
							}
						}
						if( isset( $args['settings']->$m_setting  ) ) {
							if( !empty( $args['settings']->$m_setting ) || $args['settings']->$m_setting === '0' ) {
								$scss['medium'] .= '$' . $setting . ' : ' . $args['settings']->$m_setting . $args['settings']->$m_unit . ';';
							}
						}
						if( isset( $args['settings']->$r_setting  ) ) {
							if( !empty( $args['settings']->$r_setting ) || $args['settings']->$r_setting === '0' ) {
								$scss['responsive'] .= '$' . $setting . ' : ' . $args['settings']->$r_setting . $args['settings']->$r_unit . ';';
							}
						}
					}
					# code...
					break;
				default:
					if( isset( $args['settings']->$key ) ) {
						if( !empty( $args['settings']->$key ) || $args['settings']->$key === '0' ) {
							$scss['large'] .= '$' . $key . ' : ' . $args['settings']->$key . ';';
						}
					}
					if( isset( $args['settings']->$m_key ) ) {
						if( !empty( $args['settings']->$m_key ) || $args['settings']->$m_key === '0' ) {
							$scss['medium'] .= '$' . $key . ' : ' . $args['settings']->$m_key . ';';
						}
					}
					if( isset( $args['settings']->$r_key ) ) {
						if( !empty( $args['settings']->$r_key ) || $args['settings']->$r_key === '0' ) {
							$scss['responsive'] .= '$' . $key . ' : ' . $args['settings']->$r_key . ';';
						}
					}
					break;
			}
		}
		return $scss;
	}

	public function getCssFields( $args ) {

		$vars = $this->getScssVars( $args['scss'] );

		foreach ( $args['form'] as $tab_title => $tab ) {

			if( $tab_title === 'advanced' ) {
				continue;
			}

			if( !isset( $tab['sections'] ) ) {
				continue;
			}

			foreach ($tab['sections'] as $section_title => $section ) {

				if( !isset($section['fields']) ) {
					continue;
				}

				foreach ($section['fields'] as $field_title => $field) {

					if( isset( $vars[$field_title] ) || ( isset( $field['cssvar'] ) && $field['cssvar'] === true ) ) {

						$fields[$field_title] = $field['type'];
					}
				}
			}
		}
		return $fields;
	}

	public function getScssVars( $path ) {

		if( !file_exists( $path ) ) {
			return [];
		}

		$pattern = sprintf( '/%s(.*?)%s/', preg_quote('$'), preg_quote(':') );

		preg_match_all( $pattern, file_get_contents($path), $matches );

		$vars = [];

		foreach( $matches[1] as $var ) {
			$vars[trim( str_replace( ['_top', '_right', '_bottom', '_left'], '', $var ) )] = 'false';
		}

		return $vars;
	}

}