<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

/*
* Add Config
/* ------------------------------------ */
Kirki::add_config(
	'buddyx_kirki',
	array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	)
);

/*
* Add Panels And Sections
/*
 ------------------------------------ */
// Site Layout
Kirki::add_panel(
	'site_layout_panel',
	array(
		'title'       => esc_html__( 'General', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

Kirki::add_section(
	'site_layout',
	array(
		'title'       => esc_html__( 'Site Layout', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_layout_panel',
	)
);

// Site Loader
Kirki::add_section(
	'site_loader',
	array(
		'title'       => esc_html__( 'Site Loader', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_layout_panel',
	)
);

// Page Mapping
Kirki::add_section(
	'page_mapping',
	array(
		'title'       => esc_html__( 'Page Mapping', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_layout_panel',
	)
);

// Typography
Kirki::add_panel(
	'typography_panel',
	array(
		'title'       => esc_html__( 'Typography', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

Kirki::add_section(
	'site_title_typography_section',
	array(
		'title'       => esc_html__( 'Site Title', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'headings_typography_section',
	array(
		'title'       => esc_html__( 'Headings', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'menu_typography_section',
	array(
		'title'       => esc_html__( 'Menu', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'body_typography_section',
	array(
		'title'       => esc_html__( 'Body', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

// Site Header
Kirki::add_section(
	'site_header_section',
	array(
		'title'       => esc_html__( 'Site Header', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Sub Header
Kirki::add_section(
	'site_sub_header_section',
	array(
		'title'       => esc_html__( 'Site Sub Header', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Skin
Kirki::add_section(
	'site_skin_section',
	array(
		'title'       => esc_html__( 'Site Skin', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Blog Layout
Kirki::add_section(
	'site_blog_section',
	array(
		'title'       => esc_html__( 'Site Blog', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Sidebar Layout
Kirki::add_section(
	'site_sidebar_layout',
	array(
		'title'       => esc_html__( 'Site Sidebar', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Footer
Kirki::add_panel(
	'site_footer_panel',
	array(
		'title'       => esc_html__( 'Site Footer', 'buddyx' ),
		'priority'    => 11,
		'description' => '',
	)
);

Kirki::add_section(
	'site_footer_section',
	array(
		'title'       => esc_html__( 'Footer Section', 'buddyx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_footer_panel',
	)
);

// Site Copyright
Kirki::add_section(
	'site_copyright_section',
	array(
		'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
		'priority'    => 11,
		'description' => '',
		'panel'       => 'site_footer_panel',
	)
);

/*
* Add Fields
/* ------------------------------------ */

/**
 *  Site Layout
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'site_layout',
		'label'    => esc_html__( 'Site Layout', 'buddyx' ),
		'section'  => 'site_layout',
		'priority' => 10,
		'default'  => 'wide',
		'choices'  => array(
			'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
			'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
		),
	)
);

/**
 *  Site Container Width
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'        => 'dimension',
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

/**
 *  Site Loader
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_loader',
		'label'    => esc_html__( 'Site Loader ?', 'buddyx' ),
		'section'  => 'site_loader',
		'default'  => '',
		'choices'  => array(
			'on'  => esc_html__( 'Enable', 'buddyx' ),
			'off' => esc_html__( 'Disable', 'buddyx' ),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'color',
		'settings'        => 'site_loader_bg',
		'label'           => esc_html__( 'Site Loader Background', 'buddyx' ),
		'section'         => 'site_loader',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'site_loader',
				'operator' => '==',
				'value'    => '1',
			),
		),
	)
);

/*
	*  Page Mapping
	*/
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'        => 'dropdown-pages',
		'settings'    => 'buddyx_login_page',
		'label'       => esc_attr__( 'Login Page', 'buddyx' ),
		'description' => esc_attr__( 'You can redirect user to custom login page.', 'buddyx' ),
		'section'     => 'page_mapping',
		'priority'    => 10,
		'default'     => 0,
		'placeholder' => '--- Select a Page ---',
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'        => 'dropdown-pages',
		'settings'    => 'buddyx_registration_page',
		'label'       => esc_attr__( 'Registration Page', 'buddyx' ),
		'description' => esc_attr__( 'You can redirect user to custom registration page.', 'buddyx' ),
		'section'     => 'page_mapping',
		'priority'    => 10,
		'default'     => 0,
		'placeholder' => '--- Select a Page ---',
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'        => 'dropdown-pages',
		'settings'    => 'buddyx_404_page',
		'label'       => esc_attr__( '404', 'buddyx' ),
		'description' => esc_attr__( 'You can redirect user to custom 404 page.', 'buddyx' ),
		'section'     => 'page_mapping',
		'priority'    => 10,
		'default'     => 0,
		'placeholder' => '--- Select a Page ---',
	)
);

/**
 *  Site Title Typography
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'site_title_typography_option',
		'label'    => esc_html__( 'Site Title Settings', 'buddyx' ),
		'section'  => 'site_title_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '600',
			'font-size'      => '38px',
			'line-height'    => '1.2',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => '.site-title a',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_title_hover_color',
		'label'    => esc_html__( 'Site Title Hover Color', 'buddyx' ),
		'section'  => 'site_title_typography_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'site_tagline_typography_option',
		'label'    => esc_html__( 'Site Tagline Settings', 'buddyx' ),
		'section'  => 'site_title_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '14px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#757575',
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority' => 10,
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
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h1_typography_option',
		'label'    => esc_html__( 'H1 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '30px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'h1, body.buddypress article.page>.entry-header .entry-title',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h2_typography_option',
		'label'    => esc_html__( 'H2 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '24px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'h2',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h3_typography_option',
		'label'    => esc_html__( 'H3 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '22px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'h3',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h4_typography_option',
		'label'    => esc_html__( 'H4 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '20px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'h4',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h5_typography_option',
		'label'    => esc_html__( 'H5 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '18px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'h5',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'h6_typography_option',
		'label'    => esc_html__( 'H6 Tag Settings', 'buddyx' ),
		'section'  => 'headings_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '16px',
			'line-height'    => '1.4',
			'letter-spacing' => '0',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => '',
		),
		'priority' => 10,
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
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'menu_typography_option',
		'label'    => esc_html__( 'Menu Settings', 'buddyx' ),
		'section'  => 'menu_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '14px',
			'line-height'    => '1.6',
			'letter-spacing' => '0.02em',
			'color'          => '#111111',
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
			),
			array(
				'element'  => '.nav--toggle-small .menu-toggle',
				'property' => 'border-color',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'menu_hover_color',
		'label'    => esc_html__( 'Menu Hover Color', 'buddyx' ),
		'section'  => 'menu_typography_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'menu_active_color',
		'label'    => esc_html__( 'Menu Active Color', 'buddyx' ),
		'section'  => 'menu_typography_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'sub_menu_typography_option',
		'label'    => esc_html__( 'Sub Menu Settings', 'buddyx' ),
		'section'  => 'menu_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => '500',
			'font-size'      => '16px',
			'line-height'    => '1.6',
			'letter-spacing' => '0.02em',
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority' => 10,
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
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'typography_option',
		'label'    => esc_html__( 'Settings', 'buddyx' ),
		'section'  => 'body_typography_section',
		'default'  => array(
			'font-family'    => 'Open Sans',
			'variant'        => 'regular',
			'font-size'      => '14px',
			'line-height'    => '1.6',
			'letter-spacing' => '0',
			'color'          => '#505050',
			'text-transform' => 'none',
			'text-align'     => 'left',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre, input, optgroup, select, textarea',
			),
		),
	)
);

/**
 * Site Header
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_header_bg_color',
		'label'    => esc_html__( 'Header Background Color', 'buddyx' ),
		'section'  => 'site_header_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Search
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
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
	Kirki::add_field(
		'buddyx_kirki',
		array(
			'type'     => 'switch',
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
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
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
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
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

/**
 *  Site Sub Header
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_sub_header_bg',
		'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
		'section'  => 'site_sub_header_section',
		'default'  => '',
		'choices'  => array(
			'on'  => esc_html__( 'Enable', 'buddyx' ),
			'off' => esc_html__( 'Disable', 'buddyx' ),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'background',
		'settings'        => 'sub_header_background_setting',
		'label'           => esc_html__( 'Background Control', 'buddyx' ),
		'section'         => 'site_sub_header_section',
		'default'         => array(
			'background-color'      => 'rgba(255,255,255,0.5)',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		),
		'transport'       => 'auto',
		'output'          => array(
			array(
				'element' => '.site-sub-header',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'site_sub_header_bg',
				'operator' => '==',
				'value'    => '1',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'typography',
		'settings' => 'site_sub_header_typography',
		'label'    => esc_html__( 'Content Typography', 'buddyx' ),
		'section'  => 'site_sub_header_section',
		'default'  => array(
			'font-family'    => '',
			'variant'        => '',
			'font-size'      => '',
			'line-height'    => '',
			'letter-spacing' => '',
			'color'          => '#111111',
			'text-transform' => 'none',
		),
		'priority' => 10,
		'output'   => array(
			array(
				'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_breadcrumbs',
		'label'    => esc_html__( 'Site Breadcrumbs?', 'buddyx' ),
		'section'  => 'site_sub_header_section',
		'default'  => 'on',
		'choices'  => array(
			'on'  => esc_html__( 'Enable', 'buddyx' ),
			'off' => esc_html__( 'Disable', 'buddyx' ),
		),
	)
);

/**
 * Site Skin
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'body_background_color',
		'label'    => esc_html__( 'Body Background Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#f7f7f9',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'color',
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
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_primary_color',
		'label'    => esc_html__( 'Theme Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_links_color',
		'label'    => esc_html__( 'Link Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#111111',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_links_focus_hover_color',
		'label'    => esc_html__( 'Link Hover', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-skin-divider',
		'section'  => 'site_skin_section',
		'default'  => '<hr>',
	)
);

// Site Buttons
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_background_color',
		'label'    => esc_html__( 'Button Background Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_background_hover_color',
		'label'    => esc_html__( 'Button Background Hover Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#f83939',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_text_color',
		'label'    => esc_html__( 'Button Text Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_text_hover_color',
		'label'    => esc_html__( 'Button Text Hover Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_border_color',
		'label'    => esc_html__( 'Button Border Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_border_hover_color',
		'label'    => esc_html__( 'Button Border Hover Color', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => '#f83939',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Blog Layout
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'blog_layout_option',
		'label'    => esc_html__( 'Blog Layout', 'buddyx' ),
		'section'  => 'site_blog_section',
		'priority' => 10,
		'default'  => 'default-layout',
		'choices'  => array(
			'default-layout' => get_template_directory_uri() . '/assets/images/default-layout.png',
			'list-layout'    => get_template_directory_uri() . '/assets/images/list-layout.png',
			'grid-layout'    => get_template_directory_uri() . '/assets/images/grid-layout.png',
			'masonry-layout' => get_template_directory_uri() . '/assets/images/masonry-layout.png',
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'radio',
		'settings'        => 'blog_image_position',
		'label'           => esc_html__( 'Image position', 'buddyx' ),
		'section'         => 'site_blog_section',
		'priority'        => 10,
		'default'         => 'thumb-left',
		'choices'         => array(
			'thumb-left'  => esc_html__( 'Left', 'buddyx' ),
			'thumb-right' => esc_html__( 'Right', 'buddyx' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'blog_layout_option',
				'operator' => '==',
				'value'    => 'list-layout',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'radio',
		'settings'        => 'blog_grid_columns',
		'label'           => esc_html__( 'Grid Columns', 'buddyx' ),
		'section'         => 'site_blog_section',
		'priority'        => 10,
		'default'         => 'one-column',
		'choices'         => array(
			'one-column' => esc_html__( 'One', 'buddyx' ),
			'two-column' => esc_html__( 'Two', 'buddyx' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'blog_layout_option',
				'operator' => '==',
				'value'    => 'grid-layout',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'radio',
		'settings'        => 'blog_masonry_view',
		'label'           => esc_html__( 'View', 'buddyx' ),
		'section'         => 'site_blog_section',
		'priority'        => 10,
		'default'         => 'without-masonry',
		'choices'         => array(
			'without-masonry' => esc_html__( 'Without Masonry', 'buddyx' ),
			'with-masonry'    => esc_html__( 'With Masonry', 'buddyx' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'blog_layout_option',
				'operator' => '==',
				'value'    => 'masonry-layout',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'select',
		'settings'        => 'post_per_row',
		'label'           => esc_html__( 'Post Per Row', 'buddyx' ),
		'section'         => 'site_blog_section',
		'default'         => 'buddyx-masonry-2',
		'priority'        => 10,
		'choices'         => array(
			'buddyx-masonry-2' => esc_html__( 'Two', 'buddyx' ),
			'buddyx-masonry-3' => esc_html__( 'Three', 'buddyx' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'blog_layout_option',
				'operator' => '==',
				'value'    => 'masonry-layout',
			),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-skin-divider1',
		'section'  => 'site_blog_section',
		'default'  => '<hr>',
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'single_post_content_width',
		'label'    => esc_html__( 'Single Post Content Width', 'buddyx' ),
		'section'  => 'site_blog_section',
		'priority' => 10,
		'default'  => 'small',
		'choices'  => array(
			'small' => get_template_directory_uri() . '/assets/images/small.png',
			'large' => get_template_directory_uri() . '/assets/images/large.png',
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'single_post_title_layout',
		'label'    => esc_html__( 'Single Post Title Layout', 'buddyx' ),
		'section'  => 'site_blog_section',
		'priority' => 10,
		'default'  => 'buddyx-section-title-above',
		'choices'  => array(
			'buddyx-section-title-over'  => get_template_directory_uri() . '/assets/images/single-blog-layout-1.png',
			'buddyx-section-half'        => get_template_directory_uri() . '/assets/images/single-blog-layout-2.png',
			'buddyx-section-title-above' => get_template_directory_uri() . '/assets/images/single-blog-layout-3.png',
			'buddyx-section-title-below' => get_template_directory_uri() . '/assets/images/single-blog-layout-4.png',
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'color',
		'settings'        => 'buddyx_section_title_over_overlay',
		'label'           => esc_attr__( 'Image Overlay Color', 'buddyx' ),
		'description'     => esc_attr__( 'Allow to add image overlay color on single post title layout one.', 'buddyx' ),
		'section'         => 'site_blog_section',
		'default'         => 'rgba(0, 0, 0, 0.1)',
		'priority'        => 10,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => array(
			array(
				'setting'  => 'single_post_title_layout',
				'operator' => '==',
				'value'    => 'buddyx-section-title-over',
			),
		),
	)
);

/**
 *  Site Sidebar Layout
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'sidebar_option',
		'label'    => esc_html__( 'Sidebar Layout', 'buddyx' ),
		'section'  => 'site_sidebar_layout',
		'priority' => 10,
		'default'  => 'right',
		'choices'  => array(
			'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
			'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
			'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
			'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'radio-image',
		'settings' => 'single_post_sidebar_option',
		'label'    => esc_html__( 'Single Post Sidebar Layout', 'buddyx' ),
		'section'  => 'site_sidebar_layout',
		'priority' => 10,
		'default'  => 'none',
		'choices'  => array(
			'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
			'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
			'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
			'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
		),
	)
);

if ( function_exists( 'bp_is_active' ) ) {
	if ( ! class_exists( 'Youzify' ) ) {
		Kirki::add_field(
			'buddyx_kirki',
			array(
				'type'     => 'radio-image',
				'settings' => 'buddypress_sidebar_option',
				'label'    => esc_html__( 'Activity Directory Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'both',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);

		Kirki::add_field(
			'buddyx_kirki',
			array(
				'type'     => 'radio-image',
				'settings' => 'buddypress_members_sidebar_option',
				'label'    => esc_html__( 'Members Directory Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);

		Kirki::add_field(
			'buddyx_kirki',
			array(
				'type'     => 'radio-image',
				'settings' => 'buddypress_groups_sidebar_option',
				'label'    => esc_html__( 'Groups Directory Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);
	}
}

if ( function_exists( 'is_bbpress' ) ) {
	Kirki::add_field(
		'buddyx_kirki',
		array(
			'type'     => 'radio-image',
			'settings' => 'bbpress_sidebar_option',
			'label'    => esc_html__( 'bbPress Sidebar Layout', 'buddyx' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		)
	);
}

if ( class_exists( 'WooCommerce' ) ) {
	Kirki::add_field(
		'buddyx_kirki',
		array(
			'type'     => 'radio-image',
			'settings' => 'woocommerce_sidebar_option',
			'label'    => esc_html__( 'WooCommerce Sidebar Layout', 'buddyx' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		)
	);
}

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'sticky_sidebar_option',
		'label'    => esc_html__( 'Sticky Sidebar ?', 'buddyx' ),
		'section'  => 'site_sidebar_layout',
		'default'  => '1',
		'choices'  => array(
			'on'  => esc_html__( 'Enable', 'buddyx' ),
			'off' => esc_html__( 'Disable', 'buddyx' ),
		),
	)
);

/**
 *  Site Footer
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_footer_bg',
		'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
		'section'  => 'site_footer_section',
		'default'  => '',
		'choices'  => array(
			'on'  => esc_html__( 'Enable', 'buddyx' ),
			'off' => esc_html__( 'Disable', 'buddyx' ),
		),
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'            => 'background',
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

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_title_color',
		'label'    => esc_html__( 'Title Color', 'buddyx' ),
		'section'  => 'site_footer_section',
		'default'  => '#111111',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_content_color',
		'label'    => esc_html__( 'Content Color', 'buddyx' ),
		'section'  => 'site_footer_section',
		'default'  => '#505050',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_links_color',
		'label'    => esc_html__( 'Link Color', 'buddyx' ),
		'section'  => 'site_footer_section',
		'default'  => '#111111',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_links_hover_color',
		'label'    => esc_html__( 'Link Hover', 'buddyx' ),
		'section'  => 'site_footer_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Copyright
 */
Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'textarea',
		'settings' => 'site_copyright_text',
		'label'    => esc_html__( 'Add Content', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'buddyx' ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_background_color',
		'label'    => esc_html__( 'Background Color', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_border_color',
		'label'    => esc_html__( 'Border Color', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#e8e8e8',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_content_color',
		'label'    => esc_html__( 'Content Color', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#505050',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_links_color',
		'label'    => esc_html__( 'Link Color', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#111111',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'buddyx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_links_hover_color',
		'label'    => esc_html__( 'Link Hover Color', 'buddyx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#ef5455',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);
