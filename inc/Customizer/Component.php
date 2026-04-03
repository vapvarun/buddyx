<?php
/**
 * BuddyX\Buddyx\Customizer\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer;

use BuddyX\Buddyx\Component_Interface;
use WP_Customize_Manager;

use function BuddyX\Buddyx\buddyx;
use function add_action;
use function add_filter;
use function bloginfo;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;

/**
 * Class for managing Customizer integration.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'action_enqueue_customize_preview_js' ) );

		add_action(
			'customize_controls_enqueue_scripts',
			function () {
				$css_uri = get_theme_file_uri( '/assets/css/' );
				wp_enqueue_style( 'buddyx-customizer', $css_uri . 'buddyx-customizer.min.css', '', buddyx()->get_asset_version( get_theme_file_path( '/assets/css/buddyx-customizer.min.css' ) ) );
				if ( class_exists( 'Kirki' ) ) {
					wp_enqueue_script(
						'buddyx-customizer-controls',
						get_theme_file_uri( '/assets/js/customizer-controls.min.js' ),
						array( 'customize-controls', 'jquery', 'kirki_field_dependencies' ),
						buddyx()->get_asset_version( get_theme_file_path( '/assets/js/customizer-controls.min.js' ) ),
						true
					);

					wp_add_inline_style(
						'buddyx-customizer',
						'.customize-control.buddyx-force-hidden-by-mode{display:none !important;}'
					);
				}
			}
		);

		if ( class_exists( 'Kirki' ) ) {
			add_filter( 'kirki_field_add_setting_args', array( $this, 'filter_dynamic_preview_setting_args' ), 20, 2 );
		}
	}

	/**
	 * Force postMessage transport for BuddyX settings rendered by theme dynamic CSS.
	 *
	 * Kirki 5.2.2 no longer live-previews these reliably because BuddyX renders
	 * many colors/radii from theme-generated CSS variables instead of Kirki output.
	 *
	 * @param array                $args Setting args.
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @return array
	 */
	public function filter_dynamic_preview_setting_args( array $args, WP_Customize_Manager $wp_customize ): array {
		unset( $wp_customize );

		if ( empty( $args['settings'] ) || ! is_string( $args['settings'] ) ) {
			return $args;
		}

		$dynamic_preview_settings = array(
			'site_custom_colors',
			'site_loader_bg',
			'site_title_typography_option[color]',
			'site_title_hover_color',
			'site_tagline_typography_option[color]',
			'site_header_bg_color',
			'menu_typography_option[color]',
			'menu_hover_color',
			'menu_active_color',
			'body_background_color',
			'typography_option[color]',
			'content_background_color',
			'box_background_color',
			'secondary_background_color',
			'site_primary_color',
			'site_links_color',
			'site_links_focus_hover_color',
			'h1_typography_option[color]',
			'h2_typography_option[color]',
			'h3_typography_option[color]',
			'h4_typography_option[color]',
			'h5_typography_option[color]',
			'h6_typography_option[color]',
			'site_buttons_background_color',
			'site_buttons_background_hover_color',
			'site_buttons_text_color',
			'site_buttons_text_hover_color',
			'site_buttons_border_color',
			'site_buttons_border_hover_color',
			'site_footer_title_color',
			'site_footer_content_color',
			'site_footer_links_color',
			'site_footer_links_hover_color',
			'site_copyright_background_color',
			'site_copyright_content_color',
			'site_copyright_links_color',
			'site_copyright_links_hover_color',
			'site_global_border_radius',
			'site_button_border_radius',
			'site_form_border_radius',
		);

		if ( in_array( $args['settings'], $dynamic_preview_settings, true ) ) {
			$args['transport'] = 'postMessage';
		}

		return $args;
	}

	/**
	 * Adds postMessage support for site title and description, plus a custom Theme Options section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => function () {
						bloginfo( 'name' );
					},
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => function () {
						bloginfo( 'description' );
					},
				)
			);
		}

		/**
		 * Theme options.
		 */
		$wp_customize->add_section(
			'theme_options',
			array(
				'title'    => __( 'Theme Options', 'buddyx' ),
				'priority' => 130, // Before Additional CSS.
			)
		);
	}

	/**
	 * Enqueues JavaScript to make Customizer preview reload changes asynchronously.
	 */
	public function action_enqueue_customize_preview_js() {
		wp_enqueue_script(
			'buddyx-customizer',
			get_theme_file_uri( '/assets/js/customizer.min.js' ),
			array( 'customize-preview' ),
			buddyx()->get_asset_version( get_theme_file_path( '/assets/js/customizer.min.js' ) ),
			true
		);
	}
}
