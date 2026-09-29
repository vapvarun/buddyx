<?php
/**
 * Typography Customizer Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Title Typography
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'site_title_typography_option',
				'label'    => esc_html__( 'Site Title Settings', 'buddyx' ),
				'section'  => 'site_title_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '700',
					'font-style'      => 'normal',
					'font-size'       => '40px',
					'line-height'     => '1.1',
					'letter-spacing'  => '-0.01em',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.site-title a',
						// Color is excluded here: Tokens\Component already emits it as
						// the dark-mode-aware --bx-color-site-title CSS variable. A
						// literal color from this generic output would tie the header
						// text to its light-mode value and make it unreadable once
						// [data-bx-mode="dark"] is set (see BuddyX bug: header text not
						// visible in Dark mode / Dark style preset).
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'site_tagline_typography_option',
				'label'    => esc_html__( 'Site Tagline Settings', 'buddyx' ),
				'section'  => 'site_title_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '400',
					'font-style'      => 'normal',
					'font-size'       => '14px',
					'line-height'     => '1.5',
					'letter-spacing'  => '0.01em',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.site-description',
						// Same reasoning as the site-title rule above: color is owned
						// by Tokens\Component's --bx-color-site-tagline variable.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		/**
		 *  Headings Typography
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h1_typography_option',
				'label'    => esc_html__( 'H1 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '32px',
					'line-height'     => '1.2',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h1',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h1 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h2_typography_option',
				'label'    => esc_html__( 'H2 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '26px',
					'line-height'     => '1.25',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h2',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h2 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h3_typography_option',
				'label'    => esc_html__( 'H3 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '22px',
					'line-height'     => '1.3',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h3',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h3 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h4_typography_option',
				'label'    => esc_html__( 'H4 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '20px',
					'line-height'     => '1.3',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h4',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h4 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h5_typography_option',
				'label'    => esc_html__( 'H5 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '18px',
					'line-height'     => '1.4',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h5',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h5 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'h6_typography_option',
				'label'    => esc_html__( 'H6 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-style'      => 'normal',
					'font-size'       => '16px',
					'line-height'     => '1.4',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h6',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-h6 variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		/**
		 *  Menu Typography
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'menu_typography_option',
				'label'    => esc_html__( 'Menu Settings', 'buddyx' ),
				'section'  => 'menu_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '500',
					'font-style'      => 'normal',
					'font-size'       => '15px',
					'line-height'     => '1.6',
					'letter-spacing'  => '0.02em',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-menu-fg variable; see the site-title rule above.
						'exclude' => array( 'color' ),
					),
				),
			)
		);

		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'sub_menu_typography_option',
				'label'    => esc_html__( 'Sub Menu Settings', 'buddyx' ),
				'section'  => 'menu_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '500',
					'font-style'      => 'normal',
					'font-size'       => '14px',
					'line-height'     => '1.6',
					'letter-spacing'  => '0.02em',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.main-navigation ul#primary-menu>li .sub-menu a',
					),
				),
			)
		);

		/**
		 * Body Typography
		 */
		\BuddyX\Buddyx\Customizer_Framework\Field::add( 'typography',
			array(
				'settings' => 'typography_option',
				'label'    => esc_html__( 'Settings', 'buddyx' ),
				'section'  => 'body_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '400',
					'font-style'      => 'normal',
					'font-size'       => '16px',
					'line-height'     => '1.6',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'body:not(.block-editor-page):not(.wp-core-ui), input, optgroup, select, textarea',
						// Color is owned by Tokens\Component's dark-mode-aware
						// --bx-color-fg variable (--global-font-color alias); see the
						// site-title rule above for the full reasoning.
						'exclude' => array( 'color' ),
					),
					// Also expose the chosen body font as the global font-family
					// token so it is reflected in the customizer's CSS variables
					// (and consumable by plugins) — not only as a literal rule.
					array(
						'element'  => ':root',
						'property' => '--global-font-family',
						'choice'   => 'font-family',
					),
				),
			)
		);
