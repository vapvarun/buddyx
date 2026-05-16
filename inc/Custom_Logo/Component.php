<?php
/**
 * BuddyX\Buddyx\Custom_Logo\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Custom_Logo;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function add_filter;
use function add_theme_support;
use function apply_filters;
use function get_bloginfo;
use function get_theme_mod;

/**
 * Class for adding custom logo support.
 *
 * Registers the WP custom-logo theme feature and a companion `dark_mode_logo`
 * customizer field. When set, the dark logo renders alongside the light logo
 * in the same anchor; CSS shows the appropriate one based on the
 * `[data-bx-mode]` attribute the Color_Mode_Toggle component sets on `<html>`.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_logo';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_logo_support' ) );
		add_action( 'init', array( $this, 'action_register_dark_mode_logo_field' ), 20 );
		add_filter( 'get_custom_logo', array( $this, 'filter_inject_dark_mode_logo' ), 99 );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_logo_support() {
		add_theme_support(
			'custom-logo',
			apply_filters(
				'buddyx_custom_logo_args',
				array(
					'height'      => 250,
					'width'       => 250,
					'flex-width'  => true,
					'flex-height' => true,
				)
			)
		);
	}

	/**
	 * Registers the dark_mode_logo customizer field under Site Identity.
	 *
	 * Placed at priority 9 so it appears right after the standard custom logo
	 * control (priority 8 in WP core). Falls back gracefully if the
	 * Customizer_Framework hasn't loaded.
	 */
	public function action_register_dark_mode_logo_field() {
		if ( ! class_exists( '\\BuddyX\\Buddyx\\Customizer_Framework\\Field' ) ) {
			return;
		}
		\BuddyX\Buddyx\Customizer_Framework\Field::add(
			'image',
			array(
				'settings'    => 'dark_mode_logo',
				'label'       => esc_html__( 'Dark Mode Logo', 'buddyx' ),
				'description' => esc_html__( 'Logo shown when the theme is in dark mode. Falls back to the standard logo if empty.', 'buddyx' ),
				'section'     => 'title_tagline',
				'priority'    => 9,
				'default'     => '',
			)
		);
	}

	/**
	 * Injects a dark-logo `<img>` alongside the light logo when the customer
	 * has set `dark_mode_logo`. CSS handles the show/hide based on
	 * `[data-bx-mode]` (see assets/css/src/_header.css).
	 *
	 * @param string $html Original custom-logo HTML emitted by WP core.
	 * @return string
	 */
	public function filter_inject_dark_mode_logo( $html ) {
		$dark = (string) get_theme_mod( 'dark_mode_logo', '' );
		if ( '' === $dark ) {
			return $html;
		}
		// Tag the existing (light) logo image so CSS can target it.
		$html = str_replace( 'class="custom-logo"', 'class="custom-logo custom-logo--light"', $html );
		// Inject the dark logo as a sibling <img> inside the same anchor.
		$dark_img = sprintf(
			'<img src="%1$s" class="custom-logo custom-logo--dark" alt="%2$s" decoding="async" />',
			esc_url( $dark ),
			esc_attr( get_bloginfo( 'name' ) )
		);
		return str_replace( '</a>', $dark_img . '</a>', $html );
	}
}
