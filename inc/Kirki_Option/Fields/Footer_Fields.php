<?php
/**
 * Footer Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Footer Section
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_footer_bg',
				'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
				'section'  => 'site_footer_section',
				'default'  => 'off',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Background(
			array(
				'settings'        => 'background_setting',
				'label'           => esc_html__( 'Background Control', 'buddyx' ),
				'section'         => 'site_footer_section',
				'default'         => array(
					'background-color'      => 'rgba(255,255,255,0.8)',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'cover',
					'background-attachment' => 'scroll',
				),
				'transport'       => 'auto',
				'output'          => array(
					array(
						'element' => '.site-footer-wrapper',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_footer_bg',
						'operator' => '==',
						'value'    => '1',
					),
				),
			)
		);

		/**
		 *  Site Copyright
		 */
		new \Kirki\Field\Textarea(
			array(
				'settings' => 'site_copyright_text',
				'label'    => esc_html__( 'Add Content', 'buddyx' ),
				'section'  => 'site_copyright_section',
				'default'  => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'buddyx' ),
				'priority' => 10,
			)
		);