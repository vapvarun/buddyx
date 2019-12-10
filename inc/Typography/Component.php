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
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections( $wp_customize ) {
		$wp_customize->add_section(
			'typography_section',
			array(
				'title'       => esc_html__( 'Typography', 'buddyx' ),
				'priority'    => 10,
				'description' => '',
			)
		);
	}

	/**
	 *  Add Body Typography Option
	 */
	public function add_fields( $fields ) {
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'typography_option',
			'label'       => esc_attr__( 'Body Font', 'buddyx' ),
			'section'     => 'typography_section',
			'default'     => array(
				'font-family'    => 'Roboto',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.5',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'body',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'choice'   => 'font-family',
					'element'  => 'body',
					'property' => 'font-family',
				),
				array(
					'choice'   => 'variant',
					'element'  => 'body',
					'property' => 'font-weight',
				),
				array(
					'choice'   => 'font-size',
					'element'  => 'body',
					'property' => 'font-size',
				),
				array(
					'choice'   => 'line-height',
					'element'  => 'body',
					'property' => 'line-height',
				),
				array(
					'choice'   => 'text-transform',
					'element'  => 'body',
					'property' => 'text-transform',
                ),
                array(
					'choice'   => 'color',
					'element'  => 'body',
					'property' => 'color',
				),
			),
		);

		return $fields;
	}
}
