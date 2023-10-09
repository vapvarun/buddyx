<?php
/**
 * BuddyX\Buddyx\Dynamic_Style\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Dynamic_Style;

use BuddyX\Buddyx\Component_Interface;
use function BuddyX\Buddyx\buddyx;
use function add_action;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'dynamic_style';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_global_radius_options' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_bottom_options' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_form_radius_options' ), 20 );
	}

	/**
	 * Global border radius options.
	 */
	public function buddyx_global_radius_options() {
		$global_radius_var = '';
		if ( class_exists( 'Kirki' ) ) {

			$site_global_border_radius = get_theme_mod( 'site_global_border_radius' );

			if ( isset( $site_global_border_radius ) && ! empty( $site_global_border_radius ) ) {
				$global_radius_var .= '--global-border-radius: ' . $site_global_border_radius . ' !important;';
			}

			// Output border radius.
			if ( ! empty( $global_radius_var ) ) {
				$radius_attrs = 'body {' . $global_radius_var . '}';
				wp_add_inline_style( 'buddyx-global', $radius_attrs );
			}
		}
	}

	/**
	 * Buttons border radius options
	 */
	public function buddyx_bottom_options() {
		$button_radius_var = '';
		if ( class_exists( 'Kirki' ) ) {

			$site_button_border_radius = get_theme_mod( 'site_button_border_radius' );

			if ( isset( $site_button_border_radius ) && ! empty( $site_button_border_radius ) ) {
				$button_radius_var .= '--button-border-radius: ' . $site_button_border_radius . ' !important;';
			}

			// Output border radius.
			if ( ! empty( $button_radius_var ) ) {
				$radius_attrs = 'body {' . $button_radius_var . '}';
				wp_add_inline_style( 'buddyx-global', $radius_attrs );
			}
		}
	}

	/**
	 * Buttons border radius options
	 */
	public function buddyx_form_radius_options() {
		$form_radius_var = '';
		if ( class_exists( 'Kirki' ) ) {

			$site_form_border_radius = get_theme_mod( 'site_form_border_radius' );

			if ( isset( $site_form_border_radius ) && ! empty( $site_form_border_radius ) ) {
				$form_radius_var .= '--form-border-radius: ' . $site_form_border_radius . ' !important;';
			}

			// Output border radius.
			if ( ! empty( $form_radius_var ) ) {
				$radius_attrs = 'body {' . $form_radius_var . '}';
				wp_add_inline_style( 'buddyx-global', $radius_attrs );
			}
		}
	}
}
