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
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_color_options' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_global_radius_options' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_bottom_options' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_form_radius_options' ), 20 );
	}

	/**
	 * Registers or enqueues stylesheets.
	 */
	public function buddyx_color_options() {
		$color_var = '';
		if ( class_exists( 'Kirki' ) ) {
			$site_custom_colors = get_theme_mod( 'site_custom_colors', true );

			if ( $site_custom_colors ) {
				$site_loader_bg                      = get_theme_mod( 'site_loader_bg' );
				$site_title_hover_color              = get_theme_mod( 'site_title_hover_color' );
				$site_header_bg_color                = get_theme_mod( 'site_header_bg_color' );
				$menu_hover_color                    = get_theme_mod( 'menu_hover_color' );
				$menu_active_color                   = get_theme_mod( 'menu_active_color' );
				$body_background_color               = get_theme_mod( 'body_background_color' );
				$content_background_color            = get_theme_mod( 'content_background_color' );
				$box_background_color                = get_theme_mod( 'box_background_color' );
				$secondary_background_color          = get_theme_mod( 'secondary_background_color' );
				$site_primary_color                  = get_theme_mod( 'site_primary_color' );
				$site_links_color                    = get_theme_mod( 'site_links_color' );
				$site_links_focus_hover_color        = get_theme_mod( 'site_links_focus_hover_color' );
				$site_border_color                   = get_theme_mod( 'site_border_color' );
				$site_buttons_background_color       = get_theme_mod( 'site_buttons_background_color' );
				$site_buttons_background_hover_color = get_theme_mod( 'site_buttons_background_hover_color' );
				$site_buttons_text_color             = get_theme_mod( 'site_buttons_text_color' );
				$site_buttons_text_hover_color       = get_theme_mod( 'site_buttons_text_hover_color' );
				$site_buttons_border_color           = get_theme_mod( 'site_buttons_border_color' );
				$site_buttons_border_hover_color     = get_theme_mod( 'site_buttons_border_hover_color' );
				$site_footer_title_color             = get_theme_mod( 'site_footer_title_color' );
				$site_footer_content_color           = get_theme_mod( 'site_footer_content_color' );
				$site_footer_links_color             = get_theme_mod( 'site_footer_links_color' );
				$site_footer_links_hover_color       = get_theme_mod( 'site_footer_links_hover_color' );
				$site_copyright_background_color     = get_theme_mod( 'site_copyright_background_color' );
				$site_copyright_content_color        = get_theme_mod( 'site_copyright_content_color' );
				$site_copyright_links_color          = get_theme_mod( 'site_copyright_links_color' );
				$site_copyright_links_hover_color    = get_theme_mod( 'site_copyright_links_hover_color' );

				$light_attrs = 'body { ';
				if ( isset( $site_loader_bg ) && ! empty( $site_loader_bg ) ) {
					$color      = get_theme_mod( 'site_loader_bg' );
					$color_var .= '--color-theme-loader: ' . $color . ' !important;';
				}

				if ( isset( $site_title_hover_color ) && ! empty( $site_title_hover_color ) ) {
					$color      = get_theme_mod( 'site_title_hover_color' );
					$color_var .= '--color-site-title-hover: ' . $color . ' !important;';
				}

				if ( isset( $site_header_bg_color ) && ! empty( $site_header_bg_color ) ) {
					$color      = get_theme_mod( 'site_header_bg_color' );
					$color_var .= '--color-header-bg: ' . $color . ' !important;';
				}

				if ( isset( $menu_hover_color ) && ! empty( $menu_hover_color ) ) {
					$color      = get_theme_mod( 'menu_hover_color' );
					$color_var .= '--color-menu-hover: ' . $color . ' !important;';
				}

				if ( isset( $menu_active_color ) && ! empty( $menu_active_color ) ) {
					$color      = get_theme_mod( 'menu_active_color' );
					$color_var .= '--color-menu-active: ' . $color . ' !important;';
				}

				if ( isset( $body_background_color ) && ! empty( $body_background_color ) ) {
					$color      = get_theme_mod( 'body_background_color' );
					$color_var .= '--color-theme-body: ' . $color . ' !important;';
				}

				if ( isset( $content_background_color ) && ! empty( $content_background_color ) ) {
					$color      = get_theme_mod( 'content_background_color' );
					$color_var .= '--color-layout-boxed: ' . $color . ' !important;';
				}

				if ( isset( $box_background_color ) && ! empty( $box_background_color ) ) {
					$color      = get_theme_mod( 'box_background_color' );
					$color_var .= '--color-theme-white-box: ' . $color . ' !important;';
				}

				if ( isset( $secondary_background_color ) && ! empty( $secondary_background_color ) ) {
					$color      = get_theme_mod( 'secondary_background_color' );
					$color_var .= '--global-body-lightcolor: ' . $color . ' !important;';
				}

				if ( isset( $site_primary_color ) && ! empty( $site_primary_color ) ) {
					$color      = get_theme_mod( 'site_primary_color' );
					$color_var .= '--color-theme-primary: ' . $color . ' !important;';
				}

				if ( isset( $site_links_color ) && ! empty( $site_links_color ) ) {
					$color      = get_theme_mod( 'site_links_color' );
					$color_var .= '--color-link: ' . $color . ' !important;';
				}

				if ( isset( $site_links_focus_hover_color ) && ! empty( $site_links_focus_hover_color ) ) {
					$color      = get_theme_mod( 'site_links_focus_hover_color' );
					$color_var .= '--color-link-hover: ' . $color . ' !important;';
				}

				if ( isset( $site_border_color ) && ! empty( $site_border_color ) ) {
					$color      = get_theme_mod( 'site_border_color' );
					$color_var .= '--global-border-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_background_color ) && ! empty( $site_buttons_background_color ) ) {
					$color      = get_theme_mod( 'site_buttons_background_color' );
					$color_var .= '--button-background-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_background_hover_color ) && ! empty( $site_buttons_background_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_background_hover_color' );
					$color_var .= '--button-background-hover-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_text_color ) && ! empty( $site_buttons_text_color ) ) {
					$color      = get_theme_mod( 'site_buttons_text_color' );
					$color_var .= '--button-text-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_text_hover_color ) && ! empty( $site_buttons_text_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_text_hover_color' );
					$color_var .= '--button-text-hover-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_border_color ) && ! empty( $site_buttons_border_color ) ) {
					$color      = get_theme_mod( 'site_buttons_border_color' );
					$color_var .= '--button-border-color: ' . $color . ' !important;';
				}

				if ( isset( $site_buttons_border_hover_color ) && ! empty( $site_buttons_border_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_border_hover_color' );
					$color_var .= '--button-border-hover-color: ' . $color . ' !important;';
				}

				if ( isset( $site_footer_title_color ) && ! empty( $site_footer_title_color ) ) {
					$color      = get_theme_mod( 'site_footer_title_color' );
					$color_var .= '--color-footer-title: ' . $color . ' !important;';
				}

				if ( isset( $site_footer_content_color ) && ! empty( $site_footer_content_color ) ) {
					$color      = get_theme_mod( 'site_footer_content_color' );
					$color_var .= '--color-footer-content: ' . $color . ' !important;';
				}

				if ( isset( $site_footer_links_color ) && ! empty( $site_footer_links_color ) ) {
					$color      = get_theme_mod( 'site_footer_links_color' );
					$color_var .= '--color-footer-link: ' . $color . ' !important;';
				}

				if ( isset( $site_footer_links_hover_color ) && ! empty( $site_footer_links_hover_color ) ) {
					$color      = get_theme_mod( 'site_footer_links_hover_color' );
					$color_var .= '--color-footer-link-hover: ' . $color . ' !important;';
				}

				if ( isset( $site_copyright_background_color ) && ! empty( $site_copyright_background_color ) ) {
					$color      = get_theme_mod( 'site_copyright_background_color' );
					$color_var .= '--color-copyright-bg: ' . $color . ' !important;';
				}

				if ( isset( $site_copyright_content_color ) && ! empty( $site_copyright_content_color ) ) {
					$color      = get_theme_mod( 'site_copyright_content_color' );
					$color_var .= '--color-copyright-content: ' . $color . ' !important;';
				}

				if ( isset( $site_copyright_links_color ) && ! empty( $site_copyright_links_color ) ) {
					$color      = get_theme_mod( 'site_copyright_links_color' );
					$color_var .= '--color-copyright-link: ' . $color . ' !important;';
				}

				if ( isset( $site_copyright_links_hover_color ) && ! empty( $site_copyright_links_hover_color ) ) {
					$color      = get_theme_mod( 'site_copyright_links_hover_color' );
					$color_var .= '--color-copyright-link-hover: ' . $color . ' !important;';
				}

				if ( ! empty( $color_var ) ) {
					$light_attrs .= $color_var;
				}
				$light_attrs .= '}';

				if ( ! empty( $light_attrs ) ) {
					wp_add_inline_style( 'buddyx-global', $light_attrs );
				}
			}
		}
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
