<?php
/**
 * Sticky Header - Account options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Sticky_Header_Account_Configs' ) ) {

	/**
	 * Register Sticky Header Above Header ColorsCustomizer Configurations.
	 */
	class Astra_Sticky_Header_Account_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sticky Header Colors Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-header-account';
			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				/**
				 * Option: Sticky Header account Heading.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-heading]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => $_section,
					'title'    => __( 'Sticky Header Option', 'astra-addon' ),
					'settings' => array(),
					'priority' => 120,
					'context'  => array(
						Astra_Addon_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
								'operator' => '==',
								'value'    => 'icon',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
								'operator' => '==',
								'value'    => 'text',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
								'operator' => '!=',
								'value'    => 'none',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
								'operator' => '==',
								'value'    => 'menu',
							),
						),
					),
				),

				/**
				 * Option: Search Color.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-header-account-icon-color]',
					'default'   => '',
					'type'      => 'control',
					'section'   => $_section,
					'priority'  => 130,
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Icon Color', 'astra-addon' ),
					'context'   => array(
						Astra_Addon_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
								'operator' => '==',
								'value'    => 'icon',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
								'operator' => '==',
								'value'    => 'icon',
							),
						),
					),
				),

				/**
				 * Option: Text Color.
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-header-account-type-text-color]',
					'default'   => '',
					'type'      => 'control',
					'section'   => $_section,
					'priority'  => 131,
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Text Color', 'astra-addon' ),
					'context'   => array(
						Astra_Addon_Builder_Helper::$design_tab_config,
						array(
							'relation' => 'OR',
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-login-style]',
								'operator' => '==',
								'value'    => 'text',
							),
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[header-account-logout-style]',
								'operator' => '==',
								'value'    => 'text',
							),
						),
					),
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Menu Colors', 'astra-addon' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'priority'  => 140,
					'context'   => array(
						Astra_Addon_Builder_Helper::$design_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
							'operator' => '==',
							'value'    => 'menu',
						),
					),
				),

				// Option: Menu Color.
				array(
					'name'       => 'sticky-header-account-menu-color-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Normal', 'astra-addon' ),
					'section'    => $_section,
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 7,
					'context'    => array(
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[header-account-action-type]',
							'operator' => '==',
							'value'    => 'menu',
						),
						Astra_Addon_Builder_Helper::$design_tab,
					),
				),

				// Option: Background Color.
				array(
					'name'       => 'sticky-header-account-menu-bg-obj-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'section'    => $_section,
					'title'      => __( 'Background Color', 'astra-addon' ),
					'tab'        => __( 'Normal', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 8,
					'context'    => Astra_Addon_Builder_Helper::$design_tab,
				),

				// Option: Menu Hover Color.
				array(
					'name'       => 'sticky-header-account-menu-h-color-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'tab'        => __( 'Hover', 'astra-addon' ),
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'section'    => $_section,
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 19,
					'context'    => Astra_Addon_Builder_Helper::$design_tab,
				),

				// Option: Menu Hover Background Color.
				array(
					'name'       => 'sticky-header-account-menu-h-bg-color-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'type'       => 'sub-control',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Hover', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 21,
					'context'    => Astra_Addon_Builder_Helper::$design_tab,
				),

				// Option: Active Menu Color.
				array(
					'name'       => 'sticky-header-account-menu-a-color-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'tab'        => __( 'Active', 'astra-addon' ),
					'title'      => __( 'Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 31,
					'context'    => Astra_Addon_Builder_Helper::$design_tab,
				),

				// Option: Active Menu Background Color.
				array(
					'name'       => 'sticky-header-account-menu-a-bg-color-responsive',
					'default'    => '',
					'parent'     => ASTRA_THEME_SETTINGS . '[sticky-header-account-menu-colors]',
					'type'       => 'sub-control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'section'    => $_section,
					'title'      => __( 'Background Color', 'astra-addon' ),
					'tab'        => __( 'Active', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 33,
					'context'    => Astra_Addon_Builder_Helper::$design_tab,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Sticky_Header_Account_Configs();



