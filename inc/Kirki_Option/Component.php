<?php
/**
 * BuddyX\Buddyx\Typography_Options\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Kirki_Option;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Kirki;
use function add_action;
use function add_filter;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
				add_filter( 'body_class', array( $this, 'site_single_blog_post_body_classes' ) );
		if ( class_exists( 'SFWD_LMS' ) ) {
			add_filter( 'body_class', array( $this, 'site_learndash_body_classes' ) );
		}
	}

		/**
		 * Site layout body class.
		 */
	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

		/**
		 * Site sticky sidebar body class.
		 */
	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

		/**
		 * Site single blog post body class.
		 */
	public function site_single_blog_post_body_classes( array $classes ) : array {

		$single_post_layout = get_theme_mod( 'single_post_layout', buddyx_defaults( 'single-post-layout' ) );

		if ( $single_post_layout === '2' ) {
			$classes[] = 'single-post-layout-1';
		} elseif ( $single_post_layout === '3' ) {
			$classes[] = 'single-post-layout-2';
		} elseif ( $single_post_layout === '4' ) {
			$classes[] = 'single-post-layout-3';
		} else {
			$classes[] = '';
		}

		return $classes;
	}

		/**
		 * LearnDash dark mode body class.
		 */
	public function site_learndash_body_classes( array $classes ): array {
		if ( isset( $_COOKIE['bxtheme'] ) && 'dark' == $_COOKIE['bxtheme'] && is_user_logged_in() ) {
			$classes[] = 'buddyx-dark-theme';
		}

			return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections( $wp_customize ) {
		// Site Layout
		$wp_customize->add_panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader
		$wp_customize->add_section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Page Mapping
		$wp_customize->add_section(
			'page_mapping',
			array(
				'title'       => esc_html__( 'Page Mapping', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography
		$wp_customize->add_panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header
		$wp_customize->add_section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sub Header
		$wp_customize->add_section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Skin
		$wp_customize->add_section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Blog Layout
		$wp_customize->add_section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sidebar Layout
		$wp_customize->add_section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Footer
		$wp_customize->add_panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'buddyx' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright
		$wp_customize->add_section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
				'priority'    => 11,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);
	}

	public function add_fields( $fields ) {
		/**
		 *  Site Layout
		 */
		$fields[] = array(
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
		);

		/**
		 *  Site Container Width
		 */
		$fields[] = array(
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
		);

		/**
		 *  Site Loader
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_loader',
			'label'    => esc_html__( 'Site Loader ?', 'buddyx' ),
			'section'  => 'site_loader',
			'default'  => '2',
			'choices'  => array(
				'on'  => esc_html__( 'Enable', 'buddyx' ),
				'off' => esc_html__( 'Disable', 'buddyx' ),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'site_loader_bg',
			'label'           => esc_html__( 'Site Loader Background', 'buddyx' ),
			'section'         => 'site_loader',
			'default'         => '#ef5455',
			'choices'         => array( 'alpha' => true ),
			'priority'        => 10,
			'output'          => array(
				array(
					'element'  => '.site-loader',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_loader',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		/*
		 *  Page Mapping
		 */
		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_login_page',
			'label'       => esc_attr__( 'Login Page', 'buddyx' ),
			'description' => esc_attr__( 'You can redirect user to custom login page.', 'buddyx' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_registration_page',
			'label'       => esc_attr__( 'Registration Page', 'buddyx' ),
			'description' => esc_attr__( 'You can redirect user to custom registration page.', 'buddyx' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		$fields[] = array(
			'type'        => 'dropdown-pages',
			'settings'    => 'buddyx_404_page',
			'label'       => esc_attr__( '404', 'buddyx' ),
			'description' => esc_attr__( 'You can redirect user to custom 404 page.', 'buddyx' ),
			'section'     => 'page_mapping',
			'priority'    => 10,
			'default'     => 0,
			'placeholder' => '--- Select a Page ---',
		);

		/**
		 *  Site Title Typography
		 */
		$fields[] = array(
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
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_title_hover_color',
			'label'    => esc_html__( 'Site Title Hover Color', 'buddyx' ),
			'section'  => 'site_title_typography_section',
			'default'  => '#ef5455',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-title a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
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
		);

		/**
		 *  Headings Typography
		 */
		$fields[] = array(
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
		);

		$fields[] = array(
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
		);

		$fields[] = array(
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
		);

		$fields[] = array(
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
		);

		$fields[] = array(
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
		);

		$fields[] = array(
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
		);

		/**
		 *  Menu Typography
		 */
		$fields[] = array(
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
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_hover_color',
			'label'    => esc_html__( 'Menu Hover Color', 'buddyx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#ef5455',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover',
					'property' => 'color',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle:hover',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_active_color',
			'label'    => esc_html__( 'Menu Active Color', 'buddyx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#ef5455',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.main-navigation ul li.current-menu-item>a',
					'property' => 'color',
				),
			),
		);

				$fields[] = array(
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
				);

				/**
				 * Body Typography
				 */
				$fields[] = array(
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
				);

				/**
				 * Site Header
				 */
				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_header_bg_color',
					'label'    => esc_html__( 'Header Background Color', 'buddyx' ),
					'section'  => 'site_header_section',
					'default'  => '#ffffff',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu',
							'property' => 'background-color',
						),
						array(
							'element'  => '.site-header-wrapper',
							'property' => 'border-color',
						),
						array(
							'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
							'property' => 'border-top-color',
						),
						array(
							'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
							'property' => 'border-right-color',
						),
					),
				);

				/**
				 *  Site Search
				 */
				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_search',
					'label'    => esc_html__( 'Enable Search Icon', 'buddyx' ),
					'section'  => 'site_header_section',
					'default'  => '1',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				/**
		 *  Site Cart
		 */
				if ( function_exists( 'is_woocommerce' ) ) :
					$fields[] = array(
						'type'     => 'switch',
						'settings' => 'site_cart',
						'label'    => esc_html__( 'Enable Cart Icon', 'buddyx' ),
						'section'  => 'site_header_section',
						'default'  => '1',
						'choices'  => array(
							'on'  => esc_html__( 'Enable', 'buddyx' ),
							'off' => esc_html__( 'Disable', 'buddyx' ),
						),
					);
		endif;

				/**
		 *  Site Login
		 */
				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_login_link',
					'label'    => esc_html__( 'Enable Login Link', 'buddyx' ),
					'section'  => 'site_header_section',
					'default'  => '1',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				/**
		 *  Site Register
		 */
				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_register_link',
					'label'    => esc_html__( 'Enable Register Link', 'buddyx' ),
					'section'  => 'site_header_section',
					'default'  => '1',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				/**
				 *  Site Sub Header
				 */
				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_sub_header_bg',
					'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
					'section'  => 'site_sub_header_section',
					'default'  => 'off',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				$fields[] = array(
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
				);

				$fields[] = array(
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
				);

				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_breadcrumbs',
					'label'    => esc_html__( 'Site Breadcrumbs?', 'buddyx' ),
					'section'  => 'site_sub_header_section',
					'default'  => 'off',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				/**
				 * Site Skin
				 */
				$fields[] = array(
					'type'     => 'color',
					'settings' => 'body_background_color',
					'label'    => esc_html__( 'Body Background Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#f7f7f9',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => 'body',
							'property' => 'background-color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_primary_color',
					'label'    => esc_html__( 'Theme Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.post-meta-category.post-meta-category a, .buddyx-breadcrumbs a, #breadcrumbs a, .pagination .current, .buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:focus, nav#object-nav.vertical .selected>a, .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.selected a, .buddypress-wrap .bp-navs li.selected a:focus, .buddypress-wrap .bp-navs li.current a:focus,
					.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a, .media .rtm-tabs li.active a, .buddypress.widget .item-options a.selected,

                                        .learndash-wrapper .ld-expand-button.ld-button-alternate,
					.learndash-wrapper .ld-item-list .ld-item-list-item a.ld-item-name:hover,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:hover,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:active,
					.learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a:focus,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:hover,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:active,
					.learndash-wrapper .ld-table-list a.ld-table-list-item-preview:focus,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:hover,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:active,
					.learndash-wrapper .ld-expand-button.ld-button-alternate:focus,
					.learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item .ld-table-list-title a:focus, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:hover, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:active, .learndash-wrapper .ld-course-navigation .ld-table-list.ld-topic-list .ld-table-list-item a.ld-table-list-item-preview:focus,

					.learndash-wrapper .ld-status-unlocked,
					#learndash_lesson_topics_list span a, #learndash_lessons a, #learndash_profile a, #learndash_profile a span, #learndash_quizzes a, .expand_collapse a, .learndash_topic_dots a, .learndash_topic_dots a>span,
					#learndash_lessons h4>a, #learndash_quizzes h4>a, #learndash_lesson_topics_list ul>li>span a, #learndash_course_content .learndash_topic_dots ul>li a, #learndash_profile .learndash-course-link a, #learndash_profile .quiz_title a, #learndash_profile .profile_edit_profile a,
					.learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-item-preview-heading, .learndash-wrapper .ld-course-navigation .ld-lesson-item.ld-is-current-lesson .ld-lesson-title,
					.learndash-wrapper .ld-course-navigation .ld-lesson-item-preview a.ld-lesson-item-preview-heading:hover,
					.learndash-wrapper .ld-button.ld-button-transparent,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-button:hover,
					.learndash-wrapper .ld-breadcrumbs a:hover, .learndash-wrapper .ld-breadcrumbs a:active, .learndash-wrapper .ld-breadcrumbs a:focus,
					.learndash-wrapper .ld-content-actions>a:hover, .learndash-wrapper .ld-content-actions>a:active, .learndash-wrapper .ld-content-actions>a:focus,
					.learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active,
					.learndash-wrapper .ld-profile-summary .ld-profile-card .ld-profile-edit-link:hover,
					.learndash-wrapper .ld-item-list .ld-section-heading .ld-search-prompt:hover,
					#ld-profile .ld-item-list .ld-item-list-item a.ld-item-name:hover,
					.learndash-wrapper .ld-item-list .ld-item-search .ld-closer:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-closer:focus, .learndash-wrapper .ld-home-link:hover, .learndash-wrapper .ld-home-link:active, .learndash-wrapper .ld-home-link:focus,

					#learndash_lessons h4>a:hover, #learndash_lessons h4>a:active, #learndash_lessons h4>a:focus, #learndash_quizzes h4>a:hover, #learndash_quizzes h4>a:active, #learndash_quizzes h4>a:focus, #learndash_lesson_topics_list ul>li>span a:hover, #learndash_lesson_topics_list ul>li>span a:active, #learndash_lesson_topics_list ul>li>span a:focus, #learndash_course_content .learndash_topic_dots ul>li a:hover, #learndash_course_content .learndash_topic_dots ul>li a:active, #learndash_course_content .learndash_topic_dots ul>li a:focus, #learndash_profile .learndash-course-link a:hover, #learndash_profile .learndash-course-link a:active, #learndash_profile .learndash-course-link a:focus, #learndash_profile .quiz_title a:hover, #learndash_profile .quiz_title a:active, #learndash_profile .quiz_title a:focus, #learndash_profile .profile_edit_profile a:hover, #learndash_profile .profile_edit_profile a:active, #learndash_profile .profile_edit_profile a:focus,
                    ul.learn-press-courses .course .course-info .course-price .price, .widget .course-meta-field, .lp-single-course .course-price .price,

                    .llms-student-dashboard .llms-sd-item.current>a, .llms-loop-item-content .llms-loop-title:hover, .llms-pagination ul li .page-numbers.current,

					.tribe-common--breakpoint-medium.tribe-events-pro .tribe-events-pro-map__event-datetime-featured-text, .tribe-common--breakpoint-medium.tribe-events .tribe-events-calendar-list__event-datetime-featured-text, .tribe-common .tribe-common-c-svgicon, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-common a:active, .tribe-common a:focus, .tribe-common a:hover, .tribe-events-cal-links .tribe-events-gcal, .tribe-events-cal-links .tribe-events-ical, .tribe-events-event-meta a, .tribe-events-event-meta a:visited, .tribe-events-pro .tribe-events-pro-organizer__meta-email-link, .tribe-events-pro .tribe-events-pro-organizer__meta-website-link, .tribe-events-pro .tribe-events-pro-photo__event-datetime-featured-text, .tribe-events-schedule .recurringinfo a, .tribe-events-single ul.tribe-related-events li .tribe-related-events-title a, .tribe-events-widget.tribe-events-widget .tribe-events-widget-events-list__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover, .tribe-events .tribe-events-c-ical__link, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link, .tribe-related-event-info .recurringinfo a, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum, .tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link',
							'property' => 'color',
						),
						array(
							'element'  => '.buddypress-icons-wrapper .bp-msg sup, .buddypress-icons-wrapper .user-notifications sup, .menu-icons-wrapper .cart sup, .buddypress-wrap .bp-navs li.current a .count, .buddypress-wrap .bp-navs li.dynamic.current a .count, .buddypress-wrap .bp-navs li.selected a .count, .buddypress_object_nav .bp-navs li.current a .count, .buddypress_object_nav .bp-navs li.selected a .count, .buddypress-wrap .bp-navs li.dynamic.selected a .count, .buddypress_object_nav .bp-navs li.dynamic a .count, .buddypress_object_nav .bp-navs li.dynamic.current a .count, .buddypress_object_nav .bp-navs li.dynamic.selected a .count, .bp-navs ul li .count, .buddypress-wrap .bp-navs li.dynamic a .count, .bp-single-vert-nav .bp-navs.vertical li span, .buddypress-wrap .bp-navs li.dynamic a:hover .count, .buddypress_object_nav .bp-navs li.dynamic a:hover .count, .buddypress-wrap .rtm-bp-navs ul li.selected a:hover>span, .buddypress-wrap .rtm-bp-navs ul li.selected a>span, .users-header .bp-member-type,
                    .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a:after, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:hover:after, .entry .post-categories a,
                    .llms-progress .progress-bar-complete, body .llms-syllabus-wrapper .llms-section-title,

					.tribe-events .tribe-events-calendar-list__event-row--featured .tribe-events-calendar-list__event-date-tag-datetime:after',
							'property' => 'background-color',
						),
						array(
							'element'  => '.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected:focus, .widget .tribe-events .tribe-events-calendar-month__day-cell--selected:hover, .tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover, .widget .tribe-events-widget .tribe-events-widget-events-list__event-row--featured .tribe-events-widget-events-list__event-date-tag-datetime:after, .tribe-events-pro.tribe-events-view--week .datepicker .day.current:before',
							'property' => 'background',
						),
						array(
							'element'  => '.buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a,

                    .llms-student-dashboard .llms-sd-item.current>a, .llms-student-dashboard .llms-sd-item>a:hover,

					.tribe-common .tribe-common-cta--thin-alt, .tribe-common .tribe-common-cta--thin-alt:active, .tribe-common .tribe-common-cta--thin-alt:focus, .tribe-common .tribe-common-cta--thin-alt:hover, .tribe-events-pro .tribe-events-pro-map__event-card-wrapper--active .tribe-events-pro-map__event-card-button, .tribe-events-pro .tribe-events-pro-week-day-selector__day--active, .tribe-events .tribe-events-c-ical__link',
							'property' => 'border-color',
						),
						array(
							'element'  => '.tribe-common .tribe-common-anchor-thin:active, .tribe-common .tribe-common-anchor-thin:focus, .tribe-common .tribe-common-anchor-thin:hover, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:active, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:focus, .tribe-events-widget.tribe-events-widget .tribe-events-widget-featured-venue__view-more-link:hover',
							'property' => 'border-bottom-color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_links_color',
					'label'    => esc_html__( 'Link Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#111111',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => 'a',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_links_focus_hover_color',
					'label'    => esc_html__( 'Link Hover', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => 'a:hover, a:active, a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:hover, .rtmedia-actions-before-comments .rtmedia-comment-link:hover, .rtmedia-actions-before-comments .rtmedia-view-conversation:hover, #buddypress .rtmedia-actions-before-comments .rtmedia-like:hover, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:hover, nav#object-nav.vertical a:hover,
					.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'custom',
					'settings' => 'custom-skin-divider',
					'section'  => 'site_skin_section',
					'default'  => '<hr>',
				);

				// Site Buttons
				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_background_color',
					'label'    => esc_html__( 'Button Background Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'choices'  => array( 'alpha' => true ),
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review,
					.woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,

                    .ld-course-list-items .ld_course_grid .btn-primary,
					.learndash-wrapper .ld-expand-button,
					.learndash-wrapper .ld-expand-button.ld-button-alternate .ld-icon,
					.learndash-wrapper .ld-table-list .ld-table-list-header,
					.learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation-heading,
					.learndash-wrapper .ld-focus .ld-focus-sidebar .ld-focus-sidebar-trigger,
					.learndash-wrapper .ld-button,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a,
					.learndash-wrapper .ld-button, .learndash-wrapper .ld-content-actions .ld-button, .learndash-wrapper .ld-expand-button, .learndash-wrapper .ld-alert .ld-button,
					.learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active:after,
                    .learndash-wrapper .btn-join, .learndash-wrapper #btn-join, .learndash-wrapper .learndash_mark_complete_button, .learndash-wrapper #learndash_mark_complete_button, .ld-course-status-action .ld-button, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary, .ldx-plugin .uo-toolkit-grid__course-action input, .learndash-resume-button input[type=submit], .learndash-reset-form .learndash-reset-button[type=submit], .learndash-wrapper .ld-login-modal input[type=submit], .learndash-wrapper .ld-login-button, .learndash-course-widget-wrap .ld-course-status-action a,

                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
					#wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link,
					.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn',
							'property' => 'background',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_background_hover_color',
					'label'    => esc_html__( 'Button Background Hover Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#f83939',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'choices'  => array( 'alpha' => true ),
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover,
					.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,

                    .ld-course-list-items .ld_course_grid .btn-primary:hover,
					.learndash-wrapper .ld-expand-button:hover,
					.learndash-wrapper .ld-button:hover,
					.learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu .ld-user-menu-items a:hover,
					.learndash-wrapper .ld-button:hover, .learndash-wrapper .ld-button:active, .learndash-wrapper .ld-button:focus, .learndash-wrapper .ld-content-actions .ld-button:hover, .learndash-wrapper .ld-content-actions .ld-button:active, .learndash-wrapper .ld-content-actions .ld-button:focus, .learndash-wrapper .ld-expand-button:hover, .learndash-wrapper .ld-expand-button:active, .learndash-wrapper .ld-expand-button:focus, .learndash-wrapper .ld-alert .ld-button:hover, .learndash-wrapper .ld-alert .ld-button:active, .learndash-wrapper .ld-alert .ld-button:focus,
                    .learndash-wrapper .btn-join:hover, .learndash-wrapper .btn-join:active, .learndash-wrapper .btn-join:focus, .learndash-wrapper #btn-join:hover, .learndash-wrapper #btn-join:active, .learndash-wrapper #btn-join:focus, .learndash-wrapper .learndash_mark_complete_button:hover, .learndash-wrapper .learndash_mark_complete_button:active, .learndash-wrapper .learndash_mark_complete_button:focus, .learndash-wrapper #learndash_mark_complete_button:hover, .learndash-wrapper #learndash_mark_complete_button:active, .learndash-wrapper #learndash_mark_complete_button:focus, .ld-course-status-action .ld-button:hover, .ld-course-status-action .ld-button:active, .ld-course-status-action .ld-button:focus, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:hover, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:active, .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-fields .ld-item-search-submit .ld-button:focus, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:hover, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:active, .learndash-wrapper .ld-file-upload .ld-file-upload-form .ld-button:focus, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:hover, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:active, .ld-course-list-items .ld_course_grid .thumbnail.course a.btn-primary:focus, .ldx-plugin .uo-toolkit-grid__course-action input:hover, .ldx-plugin .uo-toolkit-grid__course-action input:active, .ldx-plugin .uo-toolkit-grid__course-action input:focus, .learndash-resume-button input[type=submit]:hover, .learndash-resume-button input[type=submit]:active, .learndash-resume-button input[type=submit]:focus, .learndash-reset-form .learndash-reset-button[type=submit]:hover, .learndash-reset-form .learndash-reset-button[type=submit]:active, .learndash-reset-form .learndash-reset-button[type=submit]:focus, .learndash-wrapper .ld-login-modal input[type=submit]:hover, .learndash-wrapper .ld-login-modal input[type=submit]:active, .learndash-wrapper .ld-login-modal input[type=submit]:focus, .learndash-wrapper .ld-login-button:hover, .learndash-wrapper .ld-login-button:active, .learndash-wrapper .ld-login-button:focus, .learndash-course-widget-wrap .ld-course-status-action a:hover,

                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
					#wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover,
					.tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover',
							'property' => 'background',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_text_color',
					'label'    => esc_html__( 'Button Text Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ffffff',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review,
					.woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,
                    .learndash-course-widget-wrap .ld-course-status-action a,

                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
					#wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_text_hover_color',
					'label'    => esc_html__( 'Button Text Hover Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ffffff',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover,
					.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,
                    .learndash-course-widget-wrap .ld-course-status-action a:hover,

                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
					#wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_border_color',
					'label'    => esc_html__( 'Button Border Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle, a.read-more.button, input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default, .moderation-popup .modal-container .bb-model-footer .button.report-submit, button#bbp_topic_submit, button#bbp_reply_submit, .buddypress .buddypress-wrap button.mpp-button-primary, button#mpp-edit-media-submit, .ges-change, .group-email-tooltip__close, #bplock-login-btn, #bplock-register-btn, .bgr-submit-review, #bupr_save_review,
                    .woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button,

                    .llms-button-secondary, .llms-button-primary, .llms-button-action, .llms-button-primary:focus, .llms-button-primary:active, .llms-button-action:focus, .llms-button-action:active,
					#wp-idea-stream a.button, #wp-idea-stream button:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:not(.ed_button), #wp-idea-stream input[type=reset], #wp-idea-stream input[type=submit]:not(.search-submit), a.wpis-title-button, body.single-ideas #comments .comment-reply-link,
					.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn',
							'property' => 'border-color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_buttons_border_hover_color',
					'label'    => esc_html__( 'Button Border Hover Color', 'buddyx' ),
					'section'  => 'site_skin_section',
					'default'  => '#f83939',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.buddyx-mobile-menu .dropdown-toggle:hover, a.read-more.button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover, .moderation-popup .modal-container .bb-model-footer .button.report-submit:hover, button#bbp_topic_submit:hover, button#bbp_reply_submit:hover, .buddypress .buddypress-wrap button.mpp-button-primary:hover, button#mpp-edit-media-submit:hover, .ges-change:hover, .group-email-tooltip__close:hover, #bplock-login-btn:hover, #bplock-register-btn:hover, .bgr-submit-review:hover, #bupr_save_review:hover,
                    .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover,

                    .llms-button-secondary:hover, .llms-button-primary:hover, .llms-button-action:hover, .llms-button-action.clicked,
					#wp-idea-stream a.button:focus, #wp-idea-stream a.button:hover, #wp-idea-stream button:hover:not(.ed_button):not(.search-submit):not(.submit-sort):not(.wp-embed-share-dialog-close), #wp-idea-stream input[type=button]:hover:not(.ed_button), #wp-idea-stream input[type=reset]:hover, #wp-idea-stream input[type=submit]:hover:not(.search-submit), a.wpis-title-button:focus, a.wpis-title-button:hover, body.single-ideas #comments .comment-reply-link:hover,
					.tribe-common .tribe-common-c-btn:focus, .tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:focus, .tribe-common a.tribe-common-c-btn:hover',
							'property' => 'border-color',
						),
					),
				);

				/**
				 *  Site Blog Layout
				 */
				$fields[] = array(
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
				);

				$fields[] = array(
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
				);

				$fields[] = array(
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
				);

				$fields[] = array(
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
				);

				$fields[] = array(
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
				);

				$fields[] = array(
					'type'     => 'custom',
					'settings' => 'custom-skin-divider1',
					'section'  => 'site_blog_section',
					'default'  => '<hr>',
				);

				$fields[] = array(
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
				);

				$fields[] = array(
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
				);
                                
                                $fields[] = array(
                                        'type'            => 'color',
                                        'settings'        => 'buddyx_section_title_over_overlay',
                                        'label'           => esc_attr__( 'Image Overlay Color', 'buddyx' ),
                                        'description'     => esc_attr__( 'Allow to add image overlay color on single post title layout one.', 'buddyx' ),
                                        'section'         => 'site_blog_section',
                                        'default'         => 'rgba(0, 0, 0, 0.1)',
                                        'priority'        => 10,
                                        'choices'         => array( 'alpha' => true ),
                                        'output'          => array(
                                                array(
                                                        'function' => 'css',
                                                        'element'  => '.buddyx-section-title-over.has-featured-image.has-featured-image .post-thumbnail:after',
                                                        'property' => 'background',
                                                ),
                                        ),
                                        'active_callback' => array(
                                                array(
                                                        'setting'  => 'single_post_title_layout',
                                                        'operator' => '==',
                                                        'value'    => 'buddyx-section-title-over',
                                                ),
                                        ),
                                );

				/**
				 *  Site Sidebar Layout
				 */
				$fields[] = array(
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
				);

                                $fields[] = array(
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
				);

				if ( function_exists( 'bp_is_active' ) ) {
					if ( ! class_exists( 'Youzify' ) ) {
						$fields[] = array(
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
						);

						$fields[] = array(
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
						);

						$fields[] = array(
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
						);
					}
				}

				if ( function_exists( 'is_bbpress' ) ) {
					$fields[] = array(
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
					);
				}

				if ( class_exists( 'WooCommerce' ) ) {
					$fields[] = array(
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
					);
				}

				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'sticky_sidebar_option',
					'label'    => esc_html__( 'Sticky Sidebar ?', 'buddyx' ),
					'section'  => 'site_sidebar_layout',
					'default'  => '1',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				/**
				 *  Site Footer
				 */
				$fields[] = array(
					'type'     => 'switch',
					'settings' => 'site_footer_bg',
					'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
					'section'  => 'site_footer_section',
					'default'  => 'off',
					'choices'  => array(
						'on'  => esc_html__( 'Enable', 'buddyx' ),
						'off' => esc_html__( 'Disable', 'buddyx' ),
					),
				);

				$fields[] = array(
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
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_footer_title_color',
					'label'    => esc_html__( 'Title Color', 'buddyx' ),
					'section'  => 'site_footer_section',
					'default'  => '#111111',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-footer .widget-title',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_footer_content_color',
					'label'    => esc_html__( 'Content Color', 'buddyx' ),
					'section'  => 'site_footer_section',
					'default'  => '#505050',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-footer',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_footer_links_color',
					'label'    => esc_html__( 'Link Color', 'buddyx' ),
					'section'  => 'site_footer_section',
					'default'  => '#111111',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-footer a',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_footer_links_hover_color',
					'label'    => esc_html__( 'Link Hover', 'buddyx' ),
					'section'  => 'site_footer_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-footer a:hover, .site-footer a:active',
							'property' => 'color',
						),
					),
				);

				/**
				 *  Site Copyright
				 */
				$fields[] = array(
					'type'     => 'textarea',
					'settings' => 'site_copyright_text',
					'label'    => esc_html__( 'Add Content', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'buddyx' ),
					'priority' => 10,
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_copyright_background_color',
					'label'    => esc_html__( 'Background Color', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => '#ffffff',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-info',
							'property' => 'background-color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_copyright_border_color',
					'label'    => esc_html__( 'Border Color', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => '#e8e8e8',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-info',
							'property' => 'border-color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_copyright_content_color',
					'label'    => esc_html__( 'Content Color', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => '#505050',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-info',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_copyright_links_color',
					'label'    => esc_html__( 'Link Color', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => '#111111',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-info a',
							'property' => 'color',
						),
					),
				);

				$fields[] = array(
					'type'     => 'color',
					'settings' => 'site_copyright_links_hover_color',
					'label'    => esc_html__( 'Link Hover Color', 'buddyx' ),
					'section'  => 'site_copyright_section',
					'default'  => '#ef5455',
					'choices'  => array( 'alpha' => true ),
					'priority' => 10,
					'output'   => array(
						array(
							'element'  => '.site-info a:hover',
							'property' => 'color',
						),
					),
				);

				return $fields;
	}
}
