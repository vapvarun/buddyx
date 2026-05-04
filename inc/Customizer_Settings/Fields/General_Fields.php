<?php
/**
 * General/Layout Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Layout
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'site_layout',
				'label'    => esc_html__( 'Site Layout', 'buddyx' ),
				'section'  => 'site_layout',
				'priority' => 10,
				'default'  => 'wide',
				'tooltip'  => esc_html__( 'You can select wide or box layout based on your preference.', 'buddyx' ),
				'choices'  => array(
					'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
					'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
				),
			)
		);

		/**
		 *  Site Container Width
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dimension',
			array(
				'settings'    => 'site_container_width',
				'label'       => esc_html__( 'Max Content Layout Width', 'buddyx' ),
				'description' => esc_html__( 'Select the maximum content width for your website (px)', 'buddyx' ),
				'section'     => 'site_layout',
				'default'     => '1170px',
				'priority'    => 10,
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => '.container',
						'function' => 'css',
						'property' => 'max-width',
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dimension',
			array(
				'settings'    => 'site_global_border_radius',
				'label'       => esc_html__( 'Global Border Radius', 'buddyx' ),
				'description' => esc_html__( 'Set the content, various elements border radius for your website (px)', 'buddyx' ),
				'section'     => 'site_layout',
				'default'     => '8px',
				'priority'    => 10,
				'transport'   => 'refresh',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dimension',
			array(
				'settings'    => 'site_button_border_radius',
				'label'       => esc_html__( 'Buttons Border Radius', 'buddyx' ),
				'description' => esc_html__( 'Set the buttons border radius for your website (px)', 'buddyx' ),
				'section'     => 'site_layout',
				'default'     => '6px',
				'priority'    => 10,
				'transport'   => 'refresh',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dimension',
			array(
				'settings'    => 'site_form_border_radius',
				'label'       => esc_html__( 'Form Border Radius', 'buddyx' ),
				'description' => esc_html__( 'Set the form elements (except textarea) border radius for your website (px)', 'buddyx' ),
				'section'     => 'site_layout',
				'default'     => '6px',
				'priority'    => 10,
				'transport'   => 'refresh',
			)
		);

		/**
		 *  Site Loader
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
			array(
				'settings' => 'site_loader',
				'label'    => esc_html__( 'Site Loader ?', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => '2',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		/*
		 *  Page Mapping
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dropdown-pages',
			array(
				'settings'    => 'buddyx_login_page',
				'label'       => esc_attr__( 'Login Page', 'buddyx' ),
				'description' => esc_attr__( 'You can redirect user to custom login page.', 'buddyx' ),
				'section'     => 'page_mapping',
				'priority'    => 10,
				'default'     => 0,
				'placeholder' => '--- Select a Page ---',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dropdown-pages',
			array(
				'settings'    => 'buddyx_registration_page',
				'label'       => esc_attr__( 'Registration Page', 'buddyx' ),
				'description' => esc_attr__( 'You can redirect user to custom registration page.', 'buddyx' ),
				'section'     => 'page_mapping',
				'priority'    => 10,
				'default'     => 0,
				'placeholder' => '--- Select a Page ---',
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'dropdown-pages',
			array(
				'settings'    => 'buddyx_404_page',
				'label'       => esc_attr__( '404', 'buddyx' ),
				'description' => esc_attr__( 'You can redirect user to custom 404 page.', 'buddyx' ),
				'section'     => 'page_mapping',
				'priority'    => 10,
				'default'     => 0,
				'placeholder' => '--- Select a Page ---',
			)
		);