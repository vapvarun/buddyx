<?php
/**
 * WP Login Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  WP Login Logo
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'enable_custom_login',
				'label'    => esc_html__( 'Customize Your Logo Section', 'buddyx' ),
				'section'  => 'site_wp_login_logo',
				'default'  => '',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings'        => 'enable_custom_login_logo',
				'label'           => esc_html__( 'Disable Logo?', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'default'         => '',
				'choices'         => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Image(
			array(
				'settings'        => 'custom_login_logo_image',
				'label'           => esc_attr__( 'Custom Logo', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_image_width',
				'label'           => esc_attr__( 'Logo Width', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo width (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_image_height',
				'label'           => esc_attr__( 'Logo Height', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo height (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_space',
				'label'           => esc_attr__( 'Logo Space Bottom', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo bottom spacing (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '0px',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\URL(
			array(
				'settings'        => 'custom_login_logo_url',
				'label'           => esc_attr__( 'Logo URL', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\Text(
			array(
				'settings'        => 'custom_login_logo_title',
				'label'           => esc_attr__( 'Logo Title', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'enable_custom_login_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			)
		);

		new \Kirki\Field\Text(
			array(
				'settings'        => 'custom_login_page_title',
				'label'           => esc_attr__( 'Login Page Title', 'buddyx' ),
				'description'     => esc_attr__( 'Login page title that is shown on WordPress login page.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);