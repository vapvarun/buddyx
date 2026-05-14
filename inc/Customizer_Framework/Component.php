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
