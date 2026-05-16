<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Component class
 *
 * In-house Customizer framework — replaces Kirki dependency.
 * Designed to be portable across Wbcom themes (BuddyX, BuddyX Pro).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Class Component
 *
 * Static-class API. Accumulates panels/sections/fields registered via
 * the static add()/register_*() methods, then on customize_register
 * iterates them and registers with WP_Customize_Manager.
 *
 * Public API:
 *   Component::boot( $config )                — initialize once per theme
 *   Component::register_panel( $id, $args )   — accumulate a panel
 *   Component::register_section( $id, $args ) — accumulate a section
 *   Component::register_field( $type, $args ) — accumulate a field
 *
 * Usage is via the Panel/Section/Field wrapper classes; consumers
 * generally don't call this class directly.
 */
class Component {

	/**
	 * Framework configuration.
	 *
	 * @var array
	 */
	protected static $config = array(
		'config_id'  => 'buddyx_customizer',
		'assets_url' => '',
	);

	/**
	 * Accumulated panels keyed by id.
	 *
	 * @var array<string, array>
	 */
	protected static $panels = array();

	/**
	 * Accumulated sections keyed by id.
	 *
	 * @var array<string, array>
	 */
	protected static $sections = array();

	/**
	 * Accumulated fields (numeric, order preserved).
	 *
	 * @var array<int, array>
	 */
	protected static $fields = array();

	/**
	 * Whether boot() has already run (idempotency guard).
	 *
	 * @var bool
	 */
	protected static $booted = false;

	/**
	 * Initialize the framework. No-op on second call.
	 *
	 * @param array $config Optional config overrides:
	 *                      - config_id  (string) Asset handle prefix.
	 *                      - assets_url (string) Base URL for framework assets (typically get_template_directory_uri()).
	 */
	public static function boot( array $config = array() ): void {
		if ( self::$booted ) {
			return;
		}
		self::$config = array_merge( self::$config, $config );
		self::$booted = true;

		add_action( 'customize_register', array( __CLASS__, 'register' ), 99 );
		add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'enqueue_controls' ) );
		add_action( 'customize_preview_init', array( __CLASS__, 'enqueue_preview' ) );
		add_action( 'wp_head', array( __CLASS__, 'output_inline_css' ), 100 );
	}

	/**
	 * Register a panel to be added on customize_register.
	 */
	public static function register_panel( string $id, array $args ): void {
		self::$panels[ $id ] = $args;
	}

	/**
	 * Register a section to be added on customize_register.
	 */
	public static function register_section( string $id, array $args ): void {
		self::$sections[ $id ] = $args;
	}

	/**
	 * Register a field to be added on customize_register.
	 *
	 * @param string $type Field type (e.g. 'color', 'typography').
	 * @param array  $args Field args; must include 'settings' (setting id) and 'section'.
	 */
	public static function register_field( string $type, array $args ): void {
		self::$fields[] = array_merge( array( '_type' => $type ), $args );
	}

	/**
	 * Get all accumulated fields. Used by Output_Builder.
	 *
	 * @return array
	 */
	public static function get_fields(): array {
		return self::$fields;
	}

	/**
	 * customize_register callback. Iterates accumulated panels/sections/fields
	 * and registers each with the customizer manager.
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 */
	public static function register( \WP_Customize_Manager $wp_customize ): void {
		foreach ( self::$panels as $id => $args ) {
			$wp_customize->add_panel( $id, $args );
		}
		foreach ( self::$sections as $id => $args ) {
			$wp_customize->add_section( $id, $args );
		}
		require_once __DIR__ . '/Field.php';
		foreach ( self::$fields as $field_args ) {
			Field::register_with_manager( $wp_customize, $field_args );
		}
	}

	/**
	 * Get a config value by key.
	 */
	public static function get_config( string $key ): string {
		return self::$config[ $key ] ?? '';
	}

	/**
	 * Enqueue customizer-controls.js + .css on the customizer admin page.
	 */
	public static function enqueue_controls(): void {
		$base   = trailingslashit( self::get_config( 'assets_url' ) );
		$handle = self::get_config( 'config_id' ) . '-controls';
		wp_enqueue_script(
			$handle,
			$base . 'inc/Customizer_Framework/assets/customizer-controls.js',
			array( 'customize-controls', 'wp-color-picker', 'jquery-ui-sortable' ),
			'5.1.0',
			true
		);
		wp_enqueue_style(
			$handle,
			$base . 'inc/Customizer_Framework/assets/customizer-controls.css',
			array( 'wp-color-picker' ),
			'5.1.0'
		);

		// Export array-form active_callback conditions so customizer-controls.js
		// can re-evaluate a control's `active` state live when a dependency
		// setting changes. Without this the PHP active_callback only runs on
		// initial load, so toggles like "Set Custom Colors?" had no live effect.
		$active_callbacks = array();
		foreach ( self::$fields as $f ) {
			if ( empty( $f['settings'] ) || empty( $f['active_callback'] ) || ! is_array( $f['active_callback'] ) ) {
				continue;
			}
			$active_callbacks[ $f['settings'] ] = array_values( $f['active_callback'] );
		}
		wp_add_inline_script(
			$handle,
			'window.buddyxCustomizerActiveCallbacks = ' . wp_json_encode( $active_callbacks ) . ';',
			'before'
		);

		// Tooltip popover map intentionally emitted empty.
		//
		// The `tooltip` arg on each field is now rendered as the control's
		// inline description (see Field::build_control_args). The standalone
		// click-to-open popover variant overlaid adjacent toggle/switch
		// controls and blocked clicks on the next setting in the section -
		// reported as making the customizer almost unusable on 5.1.0. The
		// empty map keeps customizer-controls.js's existing
		// renderTooltipTriggers() loop safe (no entries -> no icons drawn)
		// without removing the JS routine, which lets us re-enable popovers
		// later with anchored positioning if we choose.
		wp_add_inline_script(
			$handle,
			'window.buddyxCustomizerTooltips = {};',
			'before'
		);

		// Export style-variation -> per-control colour defaults. When the
		// customer picks a Style preset, the per-control reset button
		// reverts to the picked palette's value rather than the theme's
		// hard-coded default. Map shape: { variation_slug: { setting_id:
		// hex } }. Built by reading styles/<slug>.json and routing each
		// palette swatch (accent/base/contrast/...) to the per-control
		// colour settings the Skin section exposes.
		$variation_defaults = self::collect_style_variation_defaults();
		wp_add_inline_script(
			$handle,
			'window.buddyxStyleVariationDefaults = ' . wp_json_encode( $variation_defaults ) . ';',
			'before'
		);

		// Authoritative "theme baseline" defaults sourced from each
		// preset-managed field's PHP `default` arg. Used by the JS to
		// restore the reset target when the customer reverts to the
		// empty Default preset (avoids racing the customizer's async
		// control-registration timing to snapshot defaults client-side).
		$original_defaults = array();
		foreach ( self::$variation_setting_targets as $palette_slug => $setting_ids ) {
			foreach ( $setting_ids as $setting_id ) {
				foreach ( self::$fields as $f ) {
					if ( ( $f['settings'] ?? '' ) === $setting_id && isset( $f['default'] ) ) {
						$original_defaults[ $setting_id ] = (string) $f['default'];
						break;
					}
				}
			}
		}
		wp_add_inline_script(
			$handle,
			'window.buddyxOriginalColorDefaults = ' . wp_json_encode( $original_defaults ) . ';',
			'before'
		);
	}

	/**
	 * Map of palette swatch slugs (as they appear in styles/<slug>.json
	 * `settings.color.palette[*].slug`) to the per-control customizer
	 * colour setting IDs they should populate when the customer picks a
	 * variation. Same routing buddyx_get_style_variation_swatch_choices
	 * implies for the swatch preview.
	 *
	 * @var array<string, array<int, string>>
	 */
	protected static array $variation_setting_targets = array(
		'accent'     => array(
			'site_primary_color',
			'site_links_color',
			'site_buttons_background_color',
			'site_buttons_border_color',
			'menu_hover_color',
			'menu_active_color',
			'site_title_hover_color',
		),
		'accent-2'   => array(
			'site_links_focus_hover_color',
			'site_buttons_background_hover_color',
			'site_buttons_border_hover_color',
			'site_footer_links_hover_color',
			'site_copyright_links_hover_color',
		),
		'base'       => array(
			'body_background_color',
			'content_background_color',
			'site_header_bg_color',
			'site_footer_background_color',
			'site_copyright_background_color',
		),
		'base-2'     => array(
			'secondary_background_color',
			'box_background_color',
		),
		'contrast'   => array(
			'body_text_color',
			'headings_color',
			'site_title_color',
			'menu_color',
			'subheader_title_color',
			'site_footer_title_color',
			'site_footer_links_color',
			'site_copyright_links_color',
		),
		'contrast-2' => array(
			'site_footer_content_color',
			'site_copyright_content_color',
		),
	);

	/**
	 * Build the variation -> per-control defaults map from the styles
	 * directory. Skips silently if the directory is missing or a file is
	 * unreadable - the customer just sees the theme's hard-coded default
	 * on the reset button instead of a variation-specific one.
	 *
	 * @return array<string, array<string, string>>
	 */
	protected static function collect_style_variation_defaults(): array {
		$style_dir = get_template_directory() . '/styles';
		if ( ! is_dir( $style_dir ) ) {
			return array();
		}

		$out = array();
		foreach ( glob( $style_dir . '/*.json' ) ?: array() as $file ) {
			$slug = basename( $file, '.json' );
			$data = json_decode( (string) @file_get_contents( $file ), true );
			if ( ! is_array( $data ) ) {
				continue;
			}
			$palette = $data['settings']['color']['palette'] ?? array();
			$by_slug = array();
			foreach ( (array) $palette as $entry ) {
				if ( isset( $entry['slug'], $entry['color'] ) ) {
					$by_slug[ $entry['slug'] ] = (string) $entry['color'];
				}
			}
			if ( array() === $by_slug ) {
				continue;
			}

			$defaults = array();
			foreach ( self::$variation_setting_targets as $palette_slug => $setting_ids ) {
				if ( ! isset( $by_slug[ $palette_slug ] ) ) {
					continue;
				}
				$colour = $by_slug[ $palette_slug ];
				foreach ( $setting_ids as $setting_id ) {
					$defaults[ $setting_id ] = $colour;
				}
			}

			if ( array() !== $defaults ) {
				$out[ $slug ] = $defaults;
			}
		}
		return $out;
	}

	/**
	 * Enqueue customizer-preview.js (live preview engine) and inject the
	 * output-rules payload so the JS can update inline CSS without a full refresh.
	 */
	public static function enqueue_preview(): void {
		$base = trailingslashit( self::get_config( 'assets_url' ) );
		wp_enqueue_script(
			self::get_config( 'config_id' ) . '-preview',
			$base . 'inc/Customizer_Framework/assets/customizer-preview.js',
			array( 'customize-preview', 'jquery' ),
			'5.1.0',
			true
		);

		$payload = array();
		foreach ( self::$fields as $f ) {
			if ( empty( $f['output'] ) || empty( $f['settings'] ) ) {
				continue;
			}
			$type                      = $f['_type'] ?? '';
			$payload[ $f['settings'] ] = array(
				'_type' => $type,
				'rules' => array_map(
					static function ( $r ) {
						return array(
							'element'  => $r['element']  ?? '',
							'property' => $r['property'] ?? '',
							'units'    => $r['units']    ?? '',
						);
					},
					(array) $f['output']
				),
			);
		}
		wp_add_inline_script(
			self::get_config( 'config_id' ) . '-preview',
			'window.buddyxCustomizerOutputs = ' . wp_json_encode( $payload ) . ';',
			'before'
		);
	}

	/**
	 * Emit accumulated inline CSS into <head> based on each field's output rules.
	 */
	public static function output_inline_css(): void {
		require_once __DIR__ . '/Output_Builder.php';
		$css = Output_Builder::collect( self::$fields );
		if ( '' === $css ) {
			return;
		}
		printf(
			"<style id=\"%s-css\">%s</style>\n",
			esc_attr( self::get_config( 'config_id' ) ),
			$css // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — CSS literal generated from sanitized values.
		);
	}
}
