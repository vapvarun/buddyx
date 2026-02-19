<?php
/**
 * Header Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 * Site Header
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_sticky_header',
				'label'    => esc_html__( 'Enable Sticky Header ?', 'buddyx' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		/**
		 *  Site Search
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_search',
				'label'    => esc_html__( 'Enable Search Icon', 'buddyx' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		/**
		 *  Site Cart
		 */
		if ( function_exists( 'is_woocommerce' ) ) :
			new \Kirki\Field\Checkbox_Switch(
				array(
					'settings' => 'site_cart',
					'label'    => esc_html__( 'Enable Cart Icon', 'buddyx' ),
					'section'  => 'site_header_section',
					'default'  => '1',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				)
			);
		endif;

		/**
		 *  Site Login
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_login_link',
				'label'    => esc_html__( 'Enable Login Link', 'buddyx' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		/**
		 *  Site Register
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_register_link',
				'label'    => esc_html__( 'Enable Register Link', 'buddyx' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);