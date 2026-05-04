<?php
/**
 * Site Performance Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 *  Site Performance
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_load_google_font_locally',
				'label'    => esc_html__( 'Load Google Fonts Locally ?', 'buddyx' ),
				'section'  => 'site_performance_section',
				'default'  => '',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings'        => 'site_preload_local_font',
				'label'           => esc_html__( 'Preload Local Fonts ?', 'buddyx' ),
				'section'         => 'site_performance_section',
				'default'         => '',
				'choices'         => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'site_load_google_font_locally',
						'operator' => '==',
						'value'    => 1,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'site_flush_local_font',
				'label'           => esc_html__( 'Flush Local Fonts Cache', 'buddyx' ),
				'description'     => esc_html__( 'Click the button to reset the local fonts cache.', 'buddyx' ),
				'section'         => 'site_performance_section',
				'default'         => '<input type="submit" value="Flush Local Font Files" class="button button-secondary buddyx-flush-font-files">',
				'active_callback' => array(
					array(
						'setting'  => 'site_load_google_font_locally',
						'operator' => '==',
						'value'    => 1,
					),
				),
			)
		);