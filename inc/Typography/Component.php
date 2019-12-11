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
	public function get_slug(): string {
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

	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . get_theme_mod( 'site-layout' );

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections( $wp_customize ) {
		$wp_customize->add_section(
		'typography_section', array(
			'title'			 => esc_html__( 'Typography', 'buddyx' ),
			'priority'		 => 10,
			'description'	 => '',
		)
		);

		// Site Layout
		$wp_customize->add_panel(
		'site_layout_panel', array(
			'title'			 => esc_html__( 'Site Layout', 'buddyx' ),
			'priority'		 => 10,
			'description'	 => '',
		)
		);

		$wp_customize->add_section(
		'site_layout', array(
			'title'			 => esc_html__( 'Site Layout', 'buddyx' ),
			'priority'		 => 10,
			'description'	 => '',
			'panel'			 => 'site_layout_panel',
		)
		);

		// Site Loader
		$wp_customize->add_section(
		'site_loader', array(
			'title'			 => esc_html__( 'Site Loader', 'buddyx' ),
			'priority'		 => 10,
			'description'	 => '',
			'panel'			 => 'site_layout_panel',
		)
		);

		// Site Sidebar Layout
		$wp_customize->add_section(
		'site_sidebar_layout', array(
			'title'			 => esc_html__( 'Sidebar Layout', 'buddyx' ),
			'priority'		 => 10,
			'description'	 => '',
		)
		);
	}

	/**
	 *  Add Body Typography Option
	 */
	public function add_fields( $fields ) {
		$fields[] = array(
			'type'		 => 'typography',
			'settings'	 => 'typography_option',
			'label'		 => esc_attr__( 'Body Font', 'buddyx' ),
			'section'	 => 'typography_section',
			'default'	 => array(
				'font-family'	 => 'Roboto',
				'variant'		 => 'regular',
				'font-size'		 => '14px',
				'line-height'	 => '1.5',
				'letter-spacing' => '0',
				'color'			 => '#525252',
				'text-transform' => 'none',
				'text-align'	 => 'left',
			),
			'priority'	 => 10,
			'output'	 => array(
				array(
					'element' => 'body',
				),
			),
			'transport'	 => 'postMessage',
			'js_vars'	 => array(
				array(
					'choice'	 => 'font-family',
					'element'	 => 'body',
					'property'	 => 'font-family',
				),
				array(
					'choice'	 => 'variant',
					'element'	 => 'body',
					'property'	 => 'font-weight',
				),
				array(
					'choice'	 => 'font-size',
					'element'	 => 'body',
					'property'	 => 'font-size',
				),
				array(
					'choice'	 => 'line-height',
					'element'	 => 'body',
					'property'	 => 'line-height',
				),
				array(
					'choice'	 => 'text-transform',
					'element'	 => 'body',
					'property'	 => 'text-transform',
				),
				array(
					'choice'	 => 'color',
					'element'	 => 'body',
					'property'	 => 'color',
				),
			),
		);

		// Site Layout
		$fields[] = array(
			'type'		 => 'radio-image',
			'settings'	 => 'site-layout',
			'label'		 => esc_attr__( 'Site Layout', 'buddyx' ),
			'section'	 => 'site_layout',
			'priority'	 => 10,
			'default'	 => 'wide',
			'choices'	 => [
				'boxed'	 => get_template_directory_uri() . '/assets/images/site-layout/boxed.png',
				'wide'	 => get_template_directory_uri() . '/assets/images/site-layout/wide.png',
			],
		);

		// Site Container Width
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

		// Site Loader
		$fields[] = array(
			'type'		 => 'switch',
			'settings'	 => 'site-loader',
			'label'		 => esc_html__( 'Site Loader', 'directory24' ),
			'section'	 => 'site_loader',
			'default'	 => '2',
			'choices'	 => array(
				'on'	 => esc_attr__( 'Yes', 'directory24' ),
				'off'	 => esc_attr__( 'No', 'directory24' )
			),
		);

		$fields[] = array(
			'type'		 => 'color',
			'settings'	 => 'site-loader-bg',
			'label'		 => esc_html__( 'Site Loader Background', 'directory24' ),
			'section'	 => 'site_loader',
			'default'	 => '#00b7f1',
			'priority'	 => 10,
			'transport'	 => 'auto',
			'output'	 => array(
				array(
					'element'	 => '.loader',
					'property'	 => 'background-color',
				),
			),
		);

		// Site Sidebar Layout
		$fields[] = array(
			'type'		 => 'radio-image',
			'settings'	 => 'sidebar_option',
			'label'		 => esc_attr__( 'Sidebar Layout', 'buddyx' ),
			'section'	 => 'site_sidebar_layout',
			'priority'	 => 10,
			'default'	 => 'right',
			'choices'	 => [
				'none'	 => get_template_directory_uri() . '/assets/images/sidebar-layout/without-sidebar.png',
				'left'	 => get_template_directory_uri() . '/assets/images/sidebar-layout/left-sidebar.png',
				'right'	 => get_template_directory_uri() . '/assets/images/sidebar-layout/right-sidebar.png',
				'both'	 => get_template_directory_uri() . '/assets/images/sidebar-layout/both-sidebar.png',
			],
		);

		return $fields;
	}

}
