<?php
/**
 * Typography Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Title Typography
		 */
		new \Kirki\Field\Typography(
			array(
				'settings' => 'site_title_typography_option',
				'label'    => esc_html__( 'Site Title Settings', 'buddyx' ),
				'section'  => 'site_title_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '38px',
					'line-height'     => '1.2',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.site-title a',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'site_tagline_typography_option',
				'label'    => esc_html__( 'Site Tagline Settings', 'buddyx' ),
				'section'  => 'site_title_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => 'regular',
					'font-size'       => '15px',
					'line-height'     => '1.4',
					'letter-spacing'  => '0',
					// 'color'           => '#757575',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.site-description',
					),
				),
			)
		);

		/**
		 *  Headings Typography
		 */
		new \Kirki\Field\Typography(
			array(
				'settings' => 'h1_typography_option',
				'label'    => esc_html__( 'H1 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '32px',
					'line-height'     => '1.2',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h1',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'h2_typography_option',
				'label'    => esc_html__( 'H2 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '26px',
					'line-height'     => '1.25',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h2',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'h3_typography_option',
				'label'    => esc_html__( 'H3 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '22px',
					'line-height'     => '1.3',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h3',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'h4_typography_option',
				'label'    => esc_html__( 'H4 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '20px',
					'line-height'     => '1.3',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h4',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'h5_typography_option',
				'label'    => esc_html__( 'H5 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '18px',
					'line-height'     => '1.4',
					'letter-spacing'  => '0',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h5',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'h6_typography_option',
				'label'    => esc_html__( 'H6 Tag Settings', 'buddyx' ),
				'section'  => 'headings_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '600',
					'font-size'       => '16px',
					'line-height'     => '1.4',
					'letter-spacing'  => '0',
					// 'color'        => '#111111',
					'text-transform'  => 'none',
					'text-align'      => '',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'h6',
					),
				),
			)
		);

		/**
		 *  Menu Typography
		 */
		new \Kirki\Field\Typography(
			array(
				'settings' => 'menu_typography_option',
				'label'    => esc_html__( 'Menu Settings', 'buddyx' ),
				'section'  => 'menu_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '500',
					'font-size'       => '15px',
					'line-height'     => '1.6',
					'letter-spacing'  => '0.02em',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
					),
				),
			)
		);

		new \Kirki\Field\Typography(
			array(
				'settings' => 'sub_menu_typography_option',
				'label'    => esc_html__( 'Sub Menu Settings', 'buddyx' ),
				'section'  => 'menu_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '500',
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
		new \Kirki\Field\Typography(
			array(
				'settings' => 'typography_option',
				'label'    => esc_html__( 'Settings', 'buddyx' ),
				'section'  => 'body_typography_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => 'regular',
					'font-size'       => '16px',
					'line-height'     => '1.6',
					'letter-spacing'  => '0',
					// 'color'           => '#505050',
					'text-transform'  => 'none',
					'text-align'      => 'left',
					'text-decoration' => '',
				),
				'priority' => 10,
				'tooltip'  => esc_html__( 'We recommend using font size in pixels (px)', 'buddyx' ),
				'output'   => array(
					array(
						'element' => 'body:not(.block-editor-page):not(.wp-core-ui), input, optgroup, select, textarea',
					),
				),
			)
		);