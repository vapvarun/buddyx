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
				'label'    => esc_html__( 'Show site loader?', 'buddyx' ),
				'description' => esc_html__( 'Display a loading animation while the page is loading.', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => 1,
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		// Loader animation type — visual choice between 5 presets.
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
			array(
				'settings' => 'site_loader_type',
				'label'    => esc_html__( 'Loader animation', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => 'dots',
				'priority' => 10,
				'choices'  => array(
					'dots'    => get_template_directory_uri() . '/assets/images/loader-dots.svg',
					'spinner' => get_template_directory_uri() . '/assets/images/loader-spinner.svg',
					'pulse'   => get_template_directory_uri() . '/assets/images/loader-pulse.svg',
					'bars'    => get_template_directory_uri() . '/assets/images/loader-bars.svg',
					'logo'    => get_template_directory_uri() . '/assets/images/loader-logo.svg',
				),
				'transport' => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		// Background color — moved here from Site Skin (5.1.0). Same setting ID
		// preserves customer data; old Skin location removed.
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
			array(
				'settings' => 'site_loader_bg',
				'label'    => esc_html__( 'Background color', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => '#ef5455',
				'choices'  => array( 'alpha' => true ),
				'priority' => 10,
				'transport' => 'postMessage',
				'output'    => array(
					array(
						'element'  => '.site-loader',
						'property' => 'background-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		// Loader element color (the dots/spinner/bars/text glyphs).
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
			array(
				'settings' => 'site_loader_color',
				'label'    => esc_html__( 'Element color', 'buddyx' ),
				'description' => esc_html__( 'Color of the spinner, dots, or text. Ignored when using a logo.', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => '#ffffff',
				'choices'  => array( 'alpha' => false ),
				'priority' => 10,
				'transport' => 'postMessage',
				'output'    => array(
					array(
						'element'  => '.site-loader',
						'property' => '--bx-loader-color',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		// Optional accessible loader text — also used as aria-label.
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'text',
			array(
				'settings' => 'site_loader_text',
				'label'    => esc_html__( 'Loader text', 'buddyx' ),
				'description' => esc_html__( 'Used for the screen-reader announcement (aria-label). Visible on the screen for the "Logo" loader.', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => esc_html__( 'Loading', 'buddyx' ),
				'priority' => 10,
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		// Custom logo image (only shown when type=logo).
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'image',
			array(
				'settings' => 'site_loader_logo',
				'label'    => esc_html__( 'Custom logo image', 'buddyx' ),
				'description' => esc_html__( 'Upload an image to use as the loader. Falls back to the site logo when empty.', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => '',
				'priority' => 10,
				'transport' => 'refresh',
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'site_loader_type',
						'operator' => '==',
						'value'    => 'logo',
					),
				),
			)
		);

		// Animation duration in seconds.
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'slider',
			array(
				'settings' => 'site_loader_speed',
				'label'    => esc_html__( 'Animation speed (seconds)', 'buddyx' ),
				'section'  => 'site_loader',
				'default'  => '1.5',
				'priority' => 10,
				'transport' => 'postMessage',
				'choices'  => array(
					'min'  => 0.5,
					'max'  => 3.0,
					'step' => 0.1,
					'unit' => 's',
				),
				'output'    => array(
					array(
						'element'  => '.site-loader',
						'property' => '--bx-loader-speed',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
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