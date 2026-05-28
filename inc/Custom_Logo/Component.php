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
use function esc_attr;
use function esc_html__;
use function esc_url;
use function file_exists;
use function file_get_contents;
use function get_bloginfo;
use function get_template_directory;
use function get_theme_mod;
use function is_array;
use function json_decode;
use function preg_match;

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
		add_filter( 'body_class', array( $this, 'filter_body_classes_for_dark_style_variation' ) );
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
	 * has set `dark_mode_logo`. CSS handles the show/hide based on either
	 * `[data-bx-mode]` or a body class when the active style preset itself
	 * is a dark scheme (see assets/css/src/_header.css).
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

	/**
	 * Adds a body class when the active style preset is visually dark.
	 *
	 * The Dark preset can make the site render on dark surfaces even when the
	 * visitor-facing color mode remains "light". Tagging the body lets the
	 * logo CSS follow the actual preset scheme without changing existing
	 * color-mode behavior.
	 *
	 * @param array $classes Existing body classes.
	 * @return array
	 */
	public function filter_body_classes_for_dark_style_variation( array $classes ): array {
		if ( $this->active_variation_is_dark_scheme() ) {
			$classes[] = 'buddyx-dark-style-variation';
		}
		return $classes;
	}

	/**
	 * Whether the current style preset is visually dark.
	 *
	 * Mirrors the preset-scheme inference used by the token system so the
	 * dark logo also activates for custom dark presets, not just the bundled
	 * "Dark" variation slug.
	 */
	protected function active_variation_is_dark_scheme(): bool {
		$variation_slug = (string) get_theme_mod( 'site_style_variation', '' );
		if ( '' === $variation_slug ) {
			return false;
		}

		$override = apply_filters( 'buddyx_variation_is_dark_scheme', null, $variation_slug );
		if ( null !== $override ) {
			return (bool) $override;
		}

		$data = $this->load_variation_data( $variation_slug );
		if ( ! is_array( $data ) ) {
			return false;
		}

		$palette = $data['settings']['color']['palette'] ?? array();
		if ( ! is_array( $palette ) || empty( $palette ) ) {
			return false;
		}

		foreach ( $palette as $entry ) {
			if ( is_array( $entry ) && 'base' === ( $entry['slug'] ?? '' ) ) {
				$base_hex = (string) ( $entry['color'] ?? '' );
				return '' !== $base_hex && $this->hex_is_dark( $base_hex );
			}
		}

		return false;
	}

	/**
	 * Load a style variation JSON file from styles/<slug>.json.
	 *
	 * @param string $variation_slug Variation slug.
	 * @return array|null
	 */
	protected function load_variation_data( string $variation_slug ): ?array {
		$safe_slug = preg_match( '/^[a-z0-9_-]+$/i', $variation_slug ) ? $variation_slug : '';
		if ( '' === $safe_slug ) {
			return null;
		}

		$path = get_template_directory() . '/styles/' . $safe_slug . '.json';
		if ( ! file_exists( $path ) ) {
			return null;
		}

		$data = json_decode( (string) file_get_contents( $path ), true );
		return is_array( $data ) ? $data : null;
	}

	/**
	 * Whether a hex color reads as dark.
	 *
	 * @param string $hex Hex color with or without a leading hash.
	 * @return bool
	 */
	protected function hex_is_dark( string $hex ): bool {
		$hex = ltrim( trim( $hex ), '#' );
		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		if ( ! preg_match( '/^[a-f0-9]{6}$/i', $hex ) ) {
			return false;
		}

		$channels = array(
			hexdec( substr( $hex, 0, 2 ) ) / 255,
			hexdec( substr( $hex, 2, 2 ) ) / 255,
			hexdec( substr( $hex, 4, 2 ) ) / 255,
		);

		$linear = array_map(
			static function ( float $channel ): float {
				return ( $channel <= 0.03928 ) ? $channel / 12.92 : pow( ( $channel + 0.055 ) / 1.055, 2.4 );
			},
			$channels
		);

		$luminance = ( 0.2126 * $linear[0] ) + ( 0.7152 * $linear[1] ) + ( 0.0722 * $linear[2] );
		return $luminance < 0.5;
	}
}
