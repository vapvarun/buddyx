<?php
/**
 * Skin/Color Kirki Fields
 *
 * @package buddyx
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

		/**
		 * Site Skin
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_custom_colors',
				'label'    => esc_html__( 'Set Custom Colors?', 'buddyx' ),
				'section'  => 'site_skin_section',
				'default'  => 'on',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-loader-divider',
				'label'           => esc_html__( 'Loader', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_loader_bg',
				'label'           => esc_html__( 'Site Loader Background', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'site_loader',
						'operator' => '==',
						'value'    => '1',
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-header-divider',
				'label'           => esc_html__( 'Header', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_title_typography_option[color]',
				'label'           => esc_html__( 'Site Title Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_title_hover_color',
				'label'           => esc_html__( 'Site Title Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_tagline_typography_option[color]',
				'label'           => esc_html__( 'Site Tagline Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#757575',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_header_bg_color',
				'label'           => esc_html__( 'Header Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ffffff',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'menu_typography_option[color]',
				'label'           => esc_html__( 'Menu Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'menu_hover_color',
				'label'           => esc_html__( 'Menu Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'menu_active_color',
				'label'           => esc_html__( 'Menu Active Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-body-divider',
				'label'           => esc_html__( 'Body', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'body_background_color',
				'label'           => esc_html__( 'Body Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#f7f7f9',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'typography_option[color]',
				'label'           => esc_html__( 'Body Text Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#505050',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'content_background_color',
				'label'           => esc_html__( 'Content Background Color', 'buddyx' ),
				'description'     => esc_html__( 'Note: This setting will only be used if the box layout is selected.', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#f7f7f9',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting' => 'site_layout',
						'value'   => 'boxed',
					),
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'box_background_color',
				'label'           => esc_html__( 'Box Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ffffff',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'secondary_background_color',
				'label'           => esc_html__( 'Secondary Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#fafafa',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_sub_header_typography[color]',
				'label'           => esc_html__( 'Subheader Title Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_primary_color',
				'label'           => esc_html__( 'Theme Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_links_color',
				'label'           => esc_html__( 'Link Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_links_focus_hover_color',
				'label'           => esc_html__( 'Link Hover', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-headings-divider',
				'label'           => esc_html__( 'Headings', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h1_typography_option[color]',
				'label'           => esc_html__( 'H1 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h2_typography_option[color]',
				'label'           => esc_html__( 'H2 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h3_typography_option[color]',
				'label'           => esc_html__( 'H3 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h4_typography_option[color]',
				'label'           => esc_html__( 'H4 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h5_typography_option[color]',
				'label'           => esc_html__( 'H5 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'h6_typography_option[color]',
				'label'           => esc_html__( 'H6 Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-button-divider',
				'label'           => esc_html__( 'Buttons', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		// Site Buttons.
		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_background_color',
				'label'           => esc_html__( 'Button Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_background_hover_color',
				'label'           => esc_html__( 'Button Background Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#f83939',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'choices'         => array( 'alpha' => true ),
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_text_color',
				'label'           => esc_html__( 'Button Text Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ffffff',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_text_hover_color',
				'label'           => esc_html__( 'Button Text Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ffffff',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_border_color',
				'label'           => esc_html__( 'Button Border Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_buttons_border_hover_color',
				'label'           => esc_html__( 'Button Border Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#f83939',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-footer-divider',
				'label'           => esc_html__( 'Footer', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_footer_title_color',
				'label'           => esc_html__( 'Footer Title Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_footer_content_color',
				'label'           => esc_html__( 'Footer Content Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#505050',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_footer_links_color',
				'label'           => esc_html__( 'Footer Link Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_footer_links_hover_color',
				'label'           => esc_html__( 'Footer Link Hover', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Custom(
			array(
				'settings'        => 'custom-coyright-divider',
				'label'           => esc_html__( 'Copyright', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '<hr style="border-color: #c6c6c6">',
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_copyright_background_color',
				'label'           => esc_html__( 'Copyright Background Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ffffff',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings' => 'site_copyright_border_color',
				'label'    => esc_html__( 'Copyright Border Color', 'buddyx' ),
				'section'  => 'site_skin_section',
				'default'  => '#e8e8e8',
				'choices'  => array( 'alpha' => true ),
				'priority' => 10,
				'output'   => array(
					array(
						'element'  => '.site-info',
						'property' => 'border-color',
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_copyright_content_color',
				'label'           => esc_html__( 'Copyright Content Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#505050',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_copyright_links_color',
				'label'           => esc_html__( 'Copyright Link Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#111111',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		new \Kirki\Field\Color(
			array(
				'settings'        => 'site_copyright_links_hover_color',
				'label'           => esc_html__( 'Copyright Link Hover Color', 'buddyx' ),
				'section'         => 'site_skin_section',
				'default'         => '#ef5455',
				'choices'         => array( 'alpha' => true ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'site_custom_colors',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);