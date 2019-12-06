<?php
/**
 * Brndle\Brndle\Jetpack\Component class
 *
 * @package buddyx
 */
namespace Brndle\Brndle\Kirki;

use Brndle\Brndle\Component_Interface;
use function Brndle\Brndle\buddyx;
use function add_filter;
use Kirki;

/**
 * Class for adding kirki plugin support.
 */
class Component implements Component_Interface {

    public function get_slug(): string {
        return 'kirki';
    }

    /**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter('kirki/config', array($this, 'configure_kirki'));
        add_filter('kirki/fields', array($this, 'add_filed'));
	}

    /**
     *  Kirki Configuration
     */
    public function configure_kirki( $config ) {
        if( ! class_exists('Kirki' ) ) {
            Kirki::add_config(
                'buddyx_kirki', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'theme_mod',
                ) 
            );
        }

        // Typography
        Kirki::add_section(
            'typography_section', array(
            'title'          => esc_html__('Typography', 'buddyx'),
            'priority'       => 10,
            ) 
        );
    }

    // Typography
    public function add_filed() {
        Kirki::add_field(
            'buddyx_kirki',
            array(
                'type'        => 'typography',
                'settings'    => 'typography_option',
                'label'       => esc_html__('Body Typography', 'buddyx'),
                'section'     => 'typography_section',
                'default'     => [
                    'font-family'    => 'Roboto',
                    'variant'        => 'regular',
                    'font-size'      => '14px',
                    'line-height'    => '1.5',
                    'letter-spacing' => '0',
                    'color'          => '#333333',
                    'text-transform' => 'none',
                    'text-align'     => 'left',
                ],
                'priority'    => 10,
                'transport'   => 'auto',
                'output'      => [
                    [
                        'element' => 'body',
                    ],
                ],
            )
        );
    }
}
