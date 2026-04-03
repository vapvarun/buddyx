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
	public function get_slug(): string {
		return 'dynamic_style';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_dynamic_inline_styles' ), 20 );
	}

	/**
	 * Collects all dynamic inline CSS and outputs it in a single wp_add_inline_style call.
	 */
	public function buddyx_dynamic_inline_styles() {
		$css  = $this->buddyx_color_options();
		$css .= $this->buddyx_global_radius_options();
		$css .= $this->buddyx_bottom_options();
		$css .= $this->buddyx_form_radius_options();

		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'buddyx-global', $css );
		}
	}

	/**
	 * Returns color CSS variables as a CSS string.
	 *
	 * @return string
	 */
	protected function buddyx_color_options(): string {
		if ( ! class_exists( 'Kirki' ) ) {
			return '';
		}

		$site_custom_colors = get_theme_mod( 'site_custom_colors', true );
		if ( ! $site_custom_colors ) {
			return '';
		}

		$mods      = get_theme_mods();
		$color_var = '';

		// Simple hex color mods: mod_key => CSS variable name.
		$simple_mods = array(
			'site_loader_bg'                      => '--color-theme-loader',
			'site_title_hover_color'              => '--color-site-title-hover',
			'site_header_bg_color'                => '--color-header-bg',
			'menu_hover_color'                    => '--color-menu-hover',
			'menu_active_color'                   => '--color-menu-active',
			'body_background_color'               => '--color-theme-body',
			'content_background_color'            => '--color-layout-boxed',
			'box_background_color'                => '--color-theme-white-box',
			'secondary_background_color'          => '--global-body-lightcolor',
			'site_primary_color'                  => '--color-theme-primary',
			'site_links_color'                    => '--color-link',
			'site_links_focus_hover_color'        => '--color-link-hover',
			'site_border_color'                   => '--global-border-color',
			'site_buttons_background_color'       => '--button-background-color',
			'site_buttons_background_hover_color' => '--button-background-hover-color',
			'site_buttons_text_color'             => '--button-text-color',
			'site_buttons_text_hover_color'       => '--button-text-hover-color',
			'site_buttons_border_color'           => '--button-border-color',
			'site_buttons_border_hover_color'     => '--button-border-hover-color',
			'site_footer_title_color'             => '--color-footer-title',
			'site_footer_content_color'           => '--color-footer-content',
			'site_footer_links_color'             => '--color-footer-link',
			'site_footer_links_hover_color'       => '--color-footer-link-hover',
			'site_copyright_background_color'     => '--color-copyright-bg',
			'site_copyright_content_color'        => '--color-copyright-content',
			'site_copyright_links_color'          => '--color-copyright-link',
			'site_copyright_links_hover_color'    => '--color-copyright-link-hover',
		);

		foreach ( $simple_mods as $mod_key => $css_var ) {
			$value = isset( $mods[ $mod_key ] ) ? $mods[ $mod_key ] : '';
			if ( ! empty( $value ) ) {
				$color = sanitize_hex_color( $value );
				if ( $color ) {
					$color_var .= $css_var . ': ' . $color . ' !important;';
				}
			}
		}

		// Typography mods (array format with 'color' key): mod_key => CSS variable name.
		$typography_mods = array(
			'site_title_typography_option'  => '--color-site-title',
			'site_tagline_typography_option' => '--color-site-tagline',
			'menu_typography_option'         => '--color-menu',
			'site_sub_header_typography'     => '--color-subheader-title',
			'typography_option'              => '--global-font-color',
			'h1_typography_option'           => '--color-h1',
			'h2_typography_option'           => '--color-h2',
			'h3_typography_option'           => '--color-h3',
			'h4_typography_option'           => '--color-h4',
			'h5_typography_option'           => '--color-h5',
			'h6_typography_option'           => '--color-h6',
		);

		foreach ( $typography_mods as $mod_key => $css_var ) {
			$value = isset( $mods[ $mod_key ] ) ? $mods[ $mod_key ] : array();
			if ( isset( $value['color'] ) && ! empty( $value['color'] ) ) {
				$color = sanitize_hex_color( $value['color'] );
				if ( $color ) {
					$color_var .= $css_var . ': ' . $color . ' !important;';
				}
			}
		}

		if ( empty( $color_var ) ) {
			return '';
		}

		return 'body { ' . $color_var . '}';
	}

	/**
	 * Returns global border radius CSS as a string.
	 *
	 * @return string
	 */
	protected function buddyx_global_radius_options(): string {
		if ( ! class_exists( 'Kirki' ) ) {
			return '';
		}

		$site_global_border_radius = get_theme_mod( 'site_global_border_radius' );
		if ( empty( $site_global_border_radius ) ) {
			return '';
		}

		$sanitized_radius = sanitize_text_field( $site_global_border_radius );
		if ( ! $sanitized_radius ) {
			return '';
		}

		return 'body {--global-border-radius: ' . $sanitized_radius . ' !important;}';
	}

	/**
	 * Returns button border radius CSS as a string.
	 *
	 * @return string
	 */
	protected function buddyx_bottom_options(): string {
		if ( ! class_exists( 'Kirki' ) ) {
			return '';
		}

		$site_button_border_radius = get_theme_mod( 'site_button_border_radius' );
		if ( empty( $site_button_border_radius ) ) {
			return '';
		}

		$sanitized_radius = sanitize_text_field( $site_button_border_radius );
		if ( ! $sanitized_radius ) {
			return '';
		}

		return 'body {--button-border-radius: ' . $sanitized_radius . ' !important;}';
	}

	/**
	 * Returns form border radius CSS as a string.
	 *
	 * @return string
	 */
	protected function buddyx_form_radius_options(): string {
		if ( ! class_exists( 'Kirki' ) ) {
			return '';
		}

		$site_form_border_radius = get_theme_mod( 'site_form_border_radius' );
		if ( empty( $site_form_border_radius ) ) {
			return '';
		}

		$sanitized_radius = sanitize_text_field( $site_form_border_radius );
		if ( ! $sanitized_radius ) {
			return '';
		}

		return 'body {--form-border-radius: ' . $sanitized_radius . ' !important;}';
	}
}
