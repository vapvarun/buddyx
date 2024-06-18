<?php
/**
 * BuddyX\Buddyx\Typography_Options\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Kirki_Option;

use function add_action;
use function add_filter;
use BuddyX\Buddyx\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		if ( class_exists( 'Kirki' ) ) {
			add_action( 'init', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'init', array( $this, 'add_fields' ) );
		}
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_single_blog_post_body_classes' ) );
		if ( class_exists( 'SFWD_LMS' ) ) {
			add_filter( 'body_class', array( $this, 'site_learndash_body_classes' ) );
		}
		if ( class_exists( 'BuddyPress' ) ) {
			add_filter( 'body_class', array( $this, 'site_buddypress_body_classes' ) );
		}
	}

	/**
	 * Site layout body class.
	 */
	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = get_theme_mod( 'site_sticky_header', buddyx_defaults( 'site-sticky-header' ) );
		if ( $sticky_header ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site single blog post body class.
	 */
	public function site_single_blog_post_body_classes( array $classes ): array {

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
	 * BuddyPress body class.
	 */
	public function site_buddypress_body_classes( array $classes ): array {
		$buddypress_avatar_style = get_theme_mod( 'buddypress_avatar_style', buddyx_defaults( 'buddypress-avatar-style' ) );
		if ( $buddypress_avatar_style ) {
			$classes[] = 'round-avatars';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections() {
		// Site Layout.
		new \Kirki\Panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		new \Kirki\Section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Page Mapping.
		new \Kirki\Section(
			'page_mapping',
			array(
				'title'       => esc_html__( 'Page Mapping', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		new \Kirki\Panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		new \Kirki\Section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sub Header.
		new \Kirki\Section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Skin.
		new \Kirki\Section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Blog Layout.
		new \Kirki\Section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		new \Kirki\Section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// WP Login.
		new \Kirki\Panel(
			'site_wp_login',
			array(
				'title'       => esc_html__( 'WP Login', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_wp_login_logo',
			array(
				'title'       => esc_html__( 'Logo', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_wp_login',
			)
		);

		// BuddyPress Option.
		if ( class_exists( 'BuddyPress' ) ) {
			if ( ! class_exists( 'Youzify' ) ) {
				new \Kirki\Panel(
					'site_buddypress_panel',
					array(
						'title'       => esc_html__( 'Community Settings', 'buddyx' ),
						'priority'    => 31,
						'description' => '',
					)
				);
			}

			new \Kirki\Section(
				'site_buddypress_general_section',
				array(
					'title'       => esc_html__( 'General Setting', 'buddyx' ),
					'priority'    => 30,
					'description' => '',
					'panel'       => 'site_buddypress_panel',
				)
			);
		}

		// Site Footer.
		new \Kirki\Panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'buddyx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		new \Kirki\Section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Performance.
		new \Kirki\Section(
			'site_performance_section',
			array(
				'title'       => esc_html__( 'Site Performance', 'buddyx' ),
				'priority'    => 31,
				'description' => '',
			)
		);
	}

	/**
	 * Add Fields
	 */
	public function add_fields() {
		/**
		 *  Site Layout
		 */
		new \Kirki\Field\Radio_Image(
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
		new \Kirki\Field\Dimension(
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

		new \Kirki\Field\Dimension(
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

		new \Kirki\Field\Dimension(
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

		new \Kirki\Field\Dimension(
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
		new \Kirki\Field\Checkbox_Switch(
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
		new \Kirki\Field\Dropdown_Pages(
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

		new \Kirki\Field\Dropdown_Pages(
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

		new \Kirki\Field\Dropdown_Pages(
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

		/**
		 *  Site Title Typography
		 */
		new \Kirki\Field\Typography(
			array(
				'settings' => 'site_title_typography_option',
				'label'    => esc_html__( 'Site Title Settings', 'buddyx' ),
				'section'  => 'site_title_typography_section',
				'default'  => array(
					'font-family'     => 'Open Sans',
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
					'font-family'     => 'Open Sans',
					'variant'         => 'regular',
					'font-size'       => '14px',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
					'font-size'       => '30px',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
					'font-size'       => '24px',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
					'font-size'       => '22px',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
					'font-size'       => '20px',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
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
					'font-family'     => 'Open Sans',
					'variant'         => '500',
					'font-size'       => '14px',
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
					'font-family'     => 'Open Sans',
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
					'font-family'     => 'Open Sans',
					'variant'         => 'regular',
					'font-size'       => '14px',
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
						'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre, input, optgroup, select, textarea',
					),
				),
			)
		);

		/**
		 * Site Header
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_sticky_header',
				'label'    => esc_html__( 'Enable Sticky Header ?', 'buddyx' ),
				'section'  => 'site_header_section',
				'default'  => '1',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		/**
		 *  Site Search
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
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
			new \Kirki\Field\Checkbox_Switch(
				array(
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
		new \Kirki\Field\Checkbox_Switch(
			array(
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
		new \Kirki\Field\Checkbox_Switch(
			array(
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
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'site_sub_header_bg',
				'label'    => esc_html__( 'Customize Background ?', 'buddyx' ),
				'section'  => 'site_sub_header_section',
				'default'  => 'off',
				'choices'  => array(
					'on'  => esc_html__( 'Enable', 'buddyx' ),
					'off' => esc_html__( 'Disable', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Background(
			array(
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

		new \Kirki\Field\Typography(
			array(
				'settings' => 'site_sub_header_typography',
				'label'    => esc_html__( 'Content Typography', 'buddyx' ),
				'section'  => 'site_sub_header_section',
				'default'  => array(
					'font-family'     => '',
					'variant'         => '',
					'font-size'       => '',
					'line-height'     => '',
					'letter-spacing'  => '',
					// 'color'           => '#111111',
					'text-transform'  => 'none',
					'text-decoration' => '',
				),
				'priority' => 10,
				'output'   => array(
					array(
						'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
					),
				),
			)
		);

		new \Kirki\Field\Checkbox_Switch(
			array(
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

		/**
		 *  Site Blog Layout
		 */
		new \Kirki\Field\Radio_Image(
			array(
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

		new \Kirki\Field\Radio(
			array(
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

		new \Kirki\Field\Radio(
			array(
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

		new \Kirki\Field\Radio(
			array(
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

		new \Kirki\Field\Select(
			array(
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

		new \Kirki\Field\Custom(
			array(
				'settings' => 'custom-skin-divider1',
				'section'  => 'site_blog_section',
				'default'  => '<hr>',
			)
		);

		new \Kirki\Field\Radio_Image(
			array(
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

		new \Kirki\Field\Radio_Image(
			array(
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

		new \Kirki\Field\Color(
			array(
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
			)
		);

		/**
		 *  Site Sidebar Layout
		 */
		new \Kirki\Field\Radio_Image(
			array(
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

		new \Kirki\Field\Radio_Image(
			array(
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
				new \Kirki\Field\Radio_Image(
					array(
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

				new \Kirki\Field\Radio_Image(
					array(
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

				new \Kirki\Field\Radio_Image(
					array(
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
			new \Kirki\Field\Radio_Image(
				array(
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
			new \Kirki\Field\Radio_Image(
				array(
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

		new \Kirki\Field\Checkbox_Switch(
			array(
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

		/*
		 *  WP Login
		 */
		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings' => 'enable_custom_login',
				'label'    => esc_html__( 'Customize Your Logo Section', 'buddyx' ),
				'section'  => 'site_wp_login_logo',
				'default'  => '',
				'choices'  => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
			)
		);

		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings'        => 'enable_custom_login_logo',
				'label'           => esc_html__( 'Disable Logo?', 'buddyx' ),
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

		new \Kirki\Field\Image(
			array(
				'settings'        => 'custom_login_logo_image',
				'label'           => esc_attr__( 'Custom Logo', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_image_width',
				'label'           => esc_attr__( 'Logo Width', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo width (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_image_height',
				'label'           => esc_attr__( 'Logo Height', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo height (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '84px',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\Dimension(
			array(
				'settings'        => 'custom_login_logo_space',
				'label'           => esc_attr__( 'Logo Space Bottom', 'buddyx' ),
				'description'     => esc_html__( 'Select the logo bottom spacing (px)', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '0px',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\URL(
			array(
				'settings'        => 'custom_login_logo_url',
				'label'           => esc_attr__( 'Logo URL', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\Text(
			array(
				'settings'        => 'custom_login_logo_title',
				'label'           => esc_attr__( 'Logo Title', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
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

		new \Kirki\Field\Text(
			array(
				'settings'        => 'custom_login_page_title',
				'label'           => esc_attr__( 'Login Page Title', 'buddyx' ),
				'description'     => esc_attr__( 'Login page title that is shown on WordPress login page.', 'buddyx' ),
				'section'         => 'site_wp_login_logo',
				'priority'        => 10,
				'default'         => '',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'enable_custom_login',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		if ( class_exists( 'BuddyPress' ) ) {

			new \Kirki\Field\Checkbox_Switch(
				array(
					'settings'    => 'buddypress_avatar_style',
					'label'       => esc_html__( 'Avatar Style', 'buddyx' ),
					'description' => esc_html__( 'Set the round style for member and group avatars.', 'buddyx' ),
					'section'     => 'site_buddypress_general_section',
					'default'     => 'on',
					'choices'     => array(
						'on'  => esc_html__( 'Yes', 'buddyx' ),
						'off' => esc_html__( 'No', 'buddyx' ),
					),
				)
			);
		}

		/**
		 *  Site Footer
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
	}
}
