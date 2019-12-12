<?php
/**
 * Brndle\Brndle\Typography_Options\Component class
 *
 * @package buddyx
 */

namespace Brndle\Brndle\Typography;

use Brndle\Brndle\Component_Interface;
use Brndle\Brndle\Kirki;
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
		return 'typography_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		add_filter( 'body_class', [ $this, 'site_width_body_classes' ] );
	}

	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout');

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

		// Site Sidebar Layout
		$wp_customize->add_section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Sidebar Layout', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
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
			'label'			 => esc_attr__( 'Max Content Layout Width', 'reign' ),
			'description'	 => esc_attr__( 'Select the maximum content width for your website (px)' ),
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
			'label'		 => esc_html__( 'Site Loader', 'directory24' ),
			'section'	 => 'site_loader',
			'default'	 => '2',
			'choices'	 => array(
				'on'	 => esc_attr__( 'Yes', 'directory24' ),
				'off'	 => esc_attr__( 'No', 'directory24' )
			),
		);

		$fields[] = array(
			'type' => 'color',
			'settings'	 => 'site_loader_bg',
			'label'		 => esc_html__( 'Site Loader Background', 'directory24' ),
			'section'	 => 'site_loader',
			'default'	 => '#00b7f1',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.loader',
					'property' => 'background-color',
				),
			),
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '38px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'color'          => '#333332',
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
			'default'     => '#00b7f1',
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
				'font-family'    => 'Roboto',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h1',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h2_typography_option',
			'label'       => esc_attr__( 'H2 Tag Settings', 'buddyx' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '22px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '20px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
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
				'font-family'    => 'Rubik',
				'variant'        => '500',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0.02em',
				'color'          => '#252525',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation ul li a',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_hover_color',
			'label'       => esc_attr__( 'Menu Hover Color', 'buddyx' ),
			'section'     => 'menu_typography_section',
			'default'     => '#00b7f1',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation ul li a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_active_color',
			'label'       => esc_attr__( 'Menu Active Color', 'buddyx' ),
			'section'     => 'menu_typography_section',
			'default'     => '#00b7f1',
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation ul li.current-menu-item a',
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
				'font-family'    => 'Roboto',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#525252',
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

		return $fields;
	}
}
