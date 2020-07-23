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
		add_filter( 'body_class', [ $this, 'site_width_body_classes' ] );
		add_filter( 'body_class', [ $this, 'site_sticky_sidebar_body_classes' ] );
	}

	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
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
				'title'       => esc_html__( 'Site Layout', 'buddyx' ),
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
				'panel' => 'site_layout_panel',
			)
		);

		// Site Loader
		$wp_customize->add_section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'site_layout_panel',
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
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
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
				'panel' => 'site_footer_panel',
			)
		);

		// Site Copyright
		$wp_customize->add_section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'buddyx' ),
				'priority'    => 11,
				'description' => '',
				'panel' => 'site_footer_panel',
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
			'label'    => esc_attr__( 'Site Layout', 'buddyx' ),
			'section'  => 'site_layout',
			'priority' => 10,
			'default'  => 'wide',
			'choices'  => [
				'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
				'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
			],
		);

		/**
		 *  Site Container Width
		 */
		$fields[] = array(
			'type'			 => 'dimension',
			'settings'		 => 'site_container_width',
			'label'			 => esc_attr__( 'Max Content Layout Width', 'buddyx' ),
			'description'	 => esc_attr__( 'Select the maximum content width for your website (px)', 'buddyx' ),
			'section'		 => 'site_layout',
			'default'		 => '1170px',
			'priority'		 => 10,
			'transport'		 => 'auto',
			'output'		 => array(
				array(
					'element'	 => '.container',
					'function'	 => 'css',
					'property'	 => 'max-width',
				),
			),
		);

		/**
		 *  Site Loader
		 */
		$fields[] = array(
			'type' => 'switch',
			'settings'	 => 'site_loader',
			'label'		 => esc_html__( 'Site Loader ?', 'buddyx' ),
			'section'	 => 'site_loader',
			'default'	 => '2',
			'choices'	 => array(
				'on'	 => esc_attr__( 'Yes', 'buddyx' ),
				'off'	 => esc_attr__( 'No', 'buddyx' )
			),
		);

		$fields[] = array(
			'type' => 'color',
			'settings'	 => 'site_loader_bg',
			'label'		 => esc_html__( 'Site Loader Background', 'buddyx' ),
			'section'	 => 'site_loader',
			'default'	 => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-loader',
					'property' => 'background-color',
				),
			),
			'active_callback'	 => array(
				array( 'setting' => 'site_loader', 'operator' => '==', 'value' => '1' ),
			)
		);

		/**
		 *  Site Title Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_title_typography_option',
			'label'       => esc_attr__( 'Site Title Settings', 'buddyx' ),
			'section'     => 'site_title_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '600',
				'font-size'      => '38px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-title a',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_title_hover_color',
			'label'       => esc_attr__( 'Site Title Hover Color', 'buddyx' ),
			'section'     => 'site_title_typography_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-title a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_tagline_typography_option',
			'label'       => esc_attr__( 'Site Tagline Settings', 'buddyx' ),
			'section'     => 'site_title_typography_section',
			'default'     => array(
				'font-family'    => 'Lato',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#757575',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-description',
				),
			),
		);

		/**
		 *  Headings Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h1_typography_option',
			'label'       => esc_attr__( 'H1 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h1, body.buddypress article.page>.entry-header .entry-title',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h2_typography_option',
			'label'       => esc_attr__( 'H2 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h2',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h3_typography_option',
			'label'       => esc_attr__( 'H3 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '22px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h3',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h4_typography_option',
			'label'       => esc_attr__( 'H4 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '20px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h4',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h5_typography_option',
			'label'       => esc_attr__( 'H5 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h5',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h6_typography_option',
			'label'       => esc_attr__( 'H6 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h6',
				),
			),
		);

		/**
		 *  Menu Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'menu_typography_option',
			'label'       => esc_attr__( 'Menu Settings', 'buddyx' ),
			'section'     => 'menu_typography_section',
			'default'     => array(
				'font-family'    => 'Source Sans Pro',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.6',
				'letter-spacing' => '0.02em',
				'color'          => '#111111',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
				),
				array(
					'element' => '.nav--toggle-small .menu-toggle',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_hover_color',
			'label'       => esc_attr__( 'Menu Hover Color', 'buddyx' ),
			'section'     => 'menu_typography_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover',
					'property' => 'color',
				),
				array(
					'element' => '.nav--toggle-small .menu-toggle:hover',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_active_color',
			'label'       => esc_attr__( 'Menu Active Color', 'buddyx' ),
			'section'     => 'menu_typography_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation ul li.current-menu-item>a',
					'property' => 'color',
				),
			),
		);

		/**
		 * Body Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'typography_option',
			'label'       => esc_attr__( 'Settings', 'buddyx' ),
			'section'     => 'body_typography_section',
			'default'     => array(
				'font-family'    => 'Lato',
				'variant'        => 'regular',
				'font-size'      => '16px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'color'          => '#505050',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre',
				),
			),
		);

		/**
		 * Site Header
		 */
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_header_bg_color',
			'label'       => esc_attr__( 'Header Background Color', 'buddyx' ),
			'section'     => 'site_header_section',
			'default'     => '#ffffff',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container',
					'property' => 'background-color',
				),
				array(
					'element' => '.site-header-wrapper',
					'property' => 'border-color',
				),
			),
		);

		/**
		 *  Site Search
		 */
		$fields[] = array(
			'type' => 'switch',
			'settings'	 => 'site_search',
			'label'		 => esc_html__( 'Site Search ?', 'buddyx' ),
			'section'	 => 'site_header_section',
			'default'	 => '1',
			'choices'	 => array(
				'on'	 => esc_attr__( 'Yes', 'buddyx' ),
				'off'	 => esc_attr__( 'No', 'buddyx' )
			),
		);

		if( function_exists( 'is_woocommerce' ) ):
			$fields[] = array(
				'type' => 'switch',
				'settings'	 => 'site_cart',
				'label'		 => esc_html__( 'Site Cart ?', 'buddyx' ),
				'section'	 => 'site_header_section',
				'default'	 => '2',
				'choices'	 => array(
					'on'	 => esc_attr__( 'Yes', 'buddyx' ),
					'off'	 => esc_attr__( 'No', 'buddyx' )
				),
			);
		endif;
		
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
				'on'  => esc_attr__( 'Yes','buddyx' ),
				'off' => esc_attr__( 'No', 'buddyx' )
			),
		);

		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'sub_header_background_setting',
			'label'       => esc_html__( 'Background Control', 'buddyx' ),
			'section'     => 'site_sub_header_section',
			'default'     => [
				'background-color'      => 'rgba(0,0,0,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element' => '.site-sub-header',
				],
			],
			'active_callback' => array(
				array( 'setting' => 'site_sub_header_bg', 'operator' => '==', 'value' => '1' )
			)
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_sub_header_typography',
			'label'       => esc_attr__( 'Content Typography', 'buddyx' ),
			'section'     => 'site_sub_header_section',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#ffffff',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
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
				'on'  => esc_attr__( 'Yes','buddyx' ),
				'off' => esc_attr__( 'No', 'buddyx' ),
			),
		);

		/**
		 * Site Skin
		 */
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'body_background_color',
			'label'       => esc_attr__( 'Body Background Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#f7f7f9',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'body',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_primary_color',
			'label'       => esc_attr__( 'Theme Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.buddyx-breadcrumbs a, #breadcrumbs a, .pagination .current, .buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:focus, nav#object-nav.vertical .selected>a, .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.current a, .buddypress-wrap .main-navs:not(.dir-navs) li.selected a, .buddypress-wrap .bp-navs li.selected a:focus, .buddypress-wrap .bp-navs li.current a:focus,
					.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a, .media .rtm-tabs li.active a, .buddypress.widget .item-options a.selected,
					#learn-press-profile-nav .tabs>li.active>a, #learn-press-profile-nav .tabs > li ul li.active a, #learn-press-profile-nav .tabs > li ul li:hover a, #learn-press-profile-nav .tabs > li:hover:not(.active) > a',
					'property' => 'color',
				),
				array(
					'element' => '.buddypress-icons-wrapper .bp-msg sup, .buddypress-icons-wrapper .user-notifications sup, .menu-icons-wrapper .cart sup, .buddypress-wrap .bp-navs li.current a .count, .buddypress-wrap .bp-navs li.dynamic.current a .count, .buddypress-wrap .bp-navs li.selected a .count, .buddypress_object_nav .bp-navs li.current a .count, .buddypress_object_nav .bp-navs li.selected a .count, .buddypress-wrap .bp-navs li.dynamic.selected a .count, .buddypress_object_nav .bp-navs li.dynamic a .count, .buddypress_object_nav .bp-navs li.dynamic.current a .count, .buddypress_object_nav .bp-navs li.dynamic.selected a .count, .bp-navs ul li .count, .buddypress-wrap .bp-navs li.dynamic a .count, .bp-single-vert-nav .bp-navs.vertical li span, .buddypress-wrap .bp-navs li.dynamic a:hover .count, .buddypress_object_nav .bp-navs li.dynamic a:hover .count, .buddypress-wrap .rtm-bp-navs ul li.selected a:hover>span, .buddypress-wrap .rtm-bp-navs ul li.selected a>span,
					.woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a:after, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:hover:after, .entry .post-categories a,
					ul.learn-press-nav-tabs .course-nav.active:after, ul.learn-press-nav-tabs .course-nav:hover:after',
					'property' => 'background-color',
				),
				array(
					'element' => '.buddypress-wrap .bp-navs li.current a, .buddypress-wrap .bp-navs li.selected a,
					.lp-tab-sections .section-tab.active span',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_links_color',
			'label'       => esc_attr__( 'Link Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#111111',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_links_focus_hover_color',
			'label'       => esc_attr__( 'Link Hover', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a:hover, a:active, a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:hover, .rtmedia-actions-before-comments .rtmedia-comment-link:hover, .rtmedia-actions-before-comments .rtmedia-view-conversation:hover, #buddypress .rtmedia-actions-before-comments .rtmedia-like:hover, .buddypress-wrap .bp-navs li:not(.current) a:focus, .buddypress-wrap .bp-navs li:not(.current) a:hover, .buddypress-wrap .bp-navs li:not(.selected) a:focus, .buddypress-wrap .bp-navs li:not(.selected) a:hover,
					.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'=> 'custom',
			'settings' => 'custom-skin-divider',
			'section'     => 'site_skin_section',
			'default'     => '<hr>',
		);

		// Site Buttons
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_background_color',
			'label'       => esc_attr__( 'Button Background Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'choices'	  => array( 'alpha' => true ),
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default,
					.woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_background_hover_color',
			'label'       => esc_attr__( 'Button Background Hover Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#f83939',
			'priority'    => 10,
			'choices'	  => array( 'alpha' => true ),
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover,
					.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_text_color',
			'label'       => esc_attr__( 'Button Text Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ffffff',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default,
					.woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_text_hover_color',
			'label'       => esc_attr__( 'Button Text Hover Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ffffff',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover,
					.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_border_color',
			'label'       => esc_attr__( 'Button Border Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"],
					#buddypress.buddypress-wrap .activity-list .load-more a, #buddypress.buddypress-wrap .activity-list .load-newest a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset]:not(.text-button), #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button, .buddypress .buddypress-wrap .bp-list.grid .action a, .buddypress .buddypress-wrap .bp-list.grid .action button, a.bp-title-button, form#bp-data-export button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a, .buddypress .buddypress-wrap button.button, .buddypress .buddypress-wrap button.button.edit, .buddypress .buddypress-wrap .btn-default,
					.woocommerce #respond input#submit, .woocommerce #respond input#submit.alt, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button, .woocommerce a.button.alt, .woocommerce a.button.alt.disabled, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button, .woocommerce button.button.alt, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled[disabled], .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button, .woocommerce input.button.alt, .woocommerce input.button.alt.disabled, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled[disabled], .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_border_hover_color',
			'label'       => esc_attr__( 'Button Border Hover Color', 'buddyx' ),
			'section'     => 'site_skin_section',
			'default'     => '#f83939',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus,
					#buddypress.buddypress-wrap .activity-list .load-more a:hover, #buddypress.buddypress-wrap .activity-list .load-newest a:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:not(.text-button):hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress input[type=submit]:focus, .buddypress .buddypress-wrap .action button:hover, .buddypress .buddypress-wrap .bp-list.grid .action a:focus, .buddypress .buddypress-wrap .bp-list.grid .action a:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:focus, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, :hover a.bp-title-button:hover, form#bp-data-export button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content button:hover, body.bp-nouveau.media #buddypress div#item-header div#item-header-content a:hover, .buddypress .buddypress-wrap button.button:hover, .buddypress .buddypress-wrap button.button.edit:hover, .buddypress .buddypress-wrap .btn-default:hover,
					.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button.alt:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce a.button:hover, .woocommerce button.button.alt:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce button.button:hover, .woocommerce input.button.alt:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce input.button:hover, .buddypress .buddypress-wrap button.gamipress-achievement-unlock-with-points-button:hover',
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
			'label'    => esc_attr__( 'Blog Layout', 'buddyx' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'default-layout',
			'choices'  => [
				'default-layout' => get_template_directory_uri() . '/assets/images/one-column.png',
				'grid-layout' => get_template_directory_uri() . '/assets/images/one-half-column.png',
				'masonry-layout' => get_template_directory_uri() . '/assets/images/one-third-column.png',
			],
		);

		$fields[] = array(
			'type'        => 'select', 
			'settings'    => 'post_per_row',
			'label'       => esc_attr__( 'Post Per Row', 'buddyx' ),
			'section'     => 'site_blog_section',
			'default'     => '4',
			'priority'    => 10,
			'choices'     => array( 
				'12' => esc_attr__( 'Column 1', 'buddyx' ), 
				'6' => esc_attr__( 'Column 2', 'buddyx' ),
				'4' => esc_attr__( 'Column 3', 'buddyx' ),  
				'3' => esc_attr__( 'Column 4', 'buddyx' ),
			), 
			'active_callback'	 => array(
				array( 'setting' => 'blog_layout_option', 'operator' => '!==', 'value' => 'default-layout' ),
			)
		);
		
		/**
		 *  Site Sidebar Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'sidebar_option',
			'label'    => esc_attr__( 'Sidebar Layout', 'buddyx' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => [
				'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			],
		);

		if ( function_exists('bp_is_active') ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'buddypress_sidebar_option',
				'label'    => esc_attr__( 'BuddyPress Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => [
					'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				],
			);
		}

		if ( function_exists('is_bbpress') ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'bbpress_sidebar_option',
				'label'    => esc_attr__( 'bbPress Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => [
					'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				],
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'woocommerce_sidebar_option',
				'label'    => esc_attr__( 'WooCommerce Sidebar Layout', 'buddyx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => [
					'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				],
			);
		}

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'buddyx' ),
			'section'  => 'site_sidebar_layout',
			'default'  => '1',
			'choices'  => [
				'on' => esc_attr__( 'Yes','buddyx' ),
				'off'  => esc_attr__( 'No','buddyx' ),
			],
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
				'on'  => esc_attr__( 'Yes','buddyx' ),
				'off' => esc_attr__( 'No', 'buddyx' )
			),
		);

		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'background_setting',
			'label'       => esc_html__( 'Background Control', 'buddyx' ),
			'section'     => 'site_footer_section',
			'default'     => [
				'background-color'      => 'rgba(255,255,255,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element' => '.site-footer-wrapper',
				],
			],
			'active_callback' => array(
				array( 'setting' => 'site_footer_bg', 'operator' => '==', 'value' => '1' )
			)
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_title_color',
			'label'       => esc_attr__( 'Title Color', 'buddyx' ),
			'section'     => 'site_footer_section',
			'default'     => '#111111',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer .widget-title',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_content_color',
			'label'       => esc_attr__( 'Content Color', 'buddyx' ),
			'section'     => 'site_footer_section',
			'default'     => '#505050',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_links_color',
			'label'       => esc_attr__( 'Link Color', 'buddyx' ),
			'section'     => 'site_footer_section',
			'default'     => '#111111',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_links_hover_color',
			'label'       => esc_attr__( 'Link Hover', 'buddyx' ),
			'section'     => 'site_footer_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer a:hover, .site-footer a:active',
					'property' => 'color',
				),
			),
		);

		/**
		 *  Site Copyright
		 */
		$fields[] = array(
			'type'        => 'textarea',
			'settings'    => 'site_copyright_text',
			'label'       => esc_attr__( 'Add Content', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => 'Copyright &copy; 2019. All rights reserved by, <a href="#">WbcomDesigns</a>',
			'priority'    => 10,
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_background_color',
			'label'       => esc_attr__( 'Background Color', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => '#ffffff',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_border_color',
			'label'       => esc_attr__( 'Border Color', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => '#e8e8e8',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_content_color',
			'label'       => esc_attr__( 'Content Color', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => '#505050',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_links_color',
			'label'       => esc_attr__( 'Link Color', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => '#111111',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_links_hover_color',
			'label'       => esc_attr__( 'Link Hover Color', 'buddyx' ),
			'section'     => 'site_copyright_section',
			'default'     => '#ef5455',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info a:hover',
					'property' => 'color',
				),
			),
		);

		return $fields;
	}
}
