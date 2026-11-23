<?php
/**
 * WP Login Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  WP Login Logo
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'    => 'enable_custom_login',
				'label'       => esc_html__( 'Customize the Login Page', 'buddyx' ),
				'description' => esc_html__( 'Change the logo and browser tab title on the WordPress login screen.', 'buddyx' ),
				'section'     => 'site_wp_login_logo',
				'default'     => '',
				'choices'     => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'switch',
			array(
				'settings'        => 'enable_custom_login_logo',
				'label'           => esc_html__( 'Hide the Login Logo', 'buddyx' ),
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'image',
			array(
				'settings'        => 'custom_login_logo_image',
				'label'           => esc_attr__( 'Login Logo', 'buddyx' ),
				'description'     => esc_html__( 'Replaces the WordPress logo on the login screen.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'dimension',
			array(
				'settings'        => 'custom_login_logo_image_width',
				'label'           => esc_attr__( 'Logo Width', 'buddyx' ),
				'description'     => esc_html__( 'Width of your login logo, in pixels.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'dimension',
			array(
				'settings'        => 'custom_login_logo_image_height',
				'label'           => esc_attr__( 'Logo Height', 'buddyx' ),
				'description'     => esc_html__( 'Height of your login logo, in pixels.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'dimension',
			array(
				'settings'        => 'custom_login_logo_space',
				'label'           => esc_attr__( 'Space Below Logo', 'buddyx' ),
				'description'     => esc_html__( 'Space between your login logo and the login form, in pixels.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '0px',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'url',
			array(
				'settings'        => 'custom_login_logo_url',
				'label'           => esc_attr__( 'Logo Link', 'buddyx' ),
				'description'     => esc_html__( 'Where the login logo takes visitors when clicked. Leave empty to link to your homepage.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'text',
			array(
				'settings'        => 'custom_login_logo_title',
				'label'           => esc_attr__( 'Logo Text', 'buddyx' ),
				'description'     => esc_html__( 'Text read out by screen readers for the login logo link. Leave empty to use your site name.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'refresh',
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

		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'text',
			array(
				'settings'        => 'custom_login_page_title',
				'label'           => esc_attr__( 'Login Page Title', 'buddyx' ),
				'description'     => esc_attr__( 'Text shown in the browser tab on the login page. Leave empty to use the standard title.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);
