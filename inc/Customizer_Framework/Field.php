<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Field — type→control dispatcher.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Field
 *
 * Maps each of 20 field type strings to a (setting class, control class,
 * is_custom_control) triple. On register, instantiates the appropriate
 * setting and control with normalized args.
 *
 * Public API:
 *   Field::add( $type, $args ) — register a field for later instantiation
 *
 * Extensibility:
 *   apply_filters( 'buddyx_customizer_field_type_map', $type_map )
 *     lets BuddyX Pro / extensions register additional control types
 *     or override existing ones via a single add_filter() call.
 */
class Field {

	/**
	 * type => [ setting class, control class, is_custom_control ]
	 *
	 * - is_custom_control true means we instantiate the class directly;
	 *   false means we pass args to add_control() and let core build it.
	 *
	 * @var array<string, array{0:string,1:string,2:bool}>
	 */
	protected static $type_map = array(
		// 12 custom controls
		'color'           => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Color',           true ),
		'typography'      => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Typography',      true ),
		'radio_image'     => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Radio_Image',     true ),
		'switch'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Toggle',         true ),
		'dimension'       => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Dimension',       true ),
		'custom'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Custom_HTML',     true ),
		'checkbox'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Checkbox',        true ),
		'slider'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Slider',          true ),
		'radio_buttonset' => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Radio_Buttonset', true ),
		'repeater'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Repeater',        true ),
		'upload'          => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Upload',          true ),
		'sortable'        => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Sortable',        true ),
		// 6 core types dispatched via add_control( id, args ) shortcut form.
		'text'            => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		'textarea'        => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		'url'             => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		'select'          => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		'radio'           => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		'dropdown-pages'  => array( '\\WP_Customize_Setting', '\\WP_Customize_Control',                  false ),
		// Image: instantiate WP core's image control directly (the add_control
		// shortcut form does not support 'image' as a type string — it would
		// render as a plain text input).
		'image'           => array( '\\WP_Customize_Setting', '\\WP_Customize_Image_Control',            true ),
		// Background is a Kirki-shape composite (color/image/repeat/position/
		// size/attachment) — six sub-inputs, structured-array value.
		'background'      => array( '\\WP_Customize_Setting', '\\BuddyX\\Buddyx\\Customizer_Framework\\Controls\\Background', true ),
	);

	/**
	 * Register a field for later instantiation on customize_register.
	 *
	 * @param string $type Field type string (one of the 20 supported keys).
	 * @param array  $args Field args; must include 'settings' and 'section'.
	 */
	public static function add( string $type, array $args ): void {
		Component::register_field( $type, $args );
	}

	/**
	 * Add the underlying setting + control to the customizer manager.
	 * Called from Component::register() during customize_register.
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 * @param array                 $args Field args (must include '_type').
	 */
	public static function register_with_manager( \WP_Customize_Manager $wp_customize, array $args ): void {
		// Allow Pro / extensions to add or override control types.
		$type_map = apply_filters( 'buddyx_customizer_field_type_map', self::$type_map );

		$type = $args['_type'] ?? '';
		if ( ! isset( $type_map[ $type ] ) ) {
			return;
		}
		list( $setting_class, $control_class, $is_custom ) = $type_map[ $type ];

		if ( empty( $args['settings'] ) ) {
			return;
		}

		// Allow callers to mutate args (e.g. force postMessage on settings rendered via dynamic CSS).
		$args = apply_filters( 'buddyx_customizer_field_args', $args, $wp_customize );

		$setting_id  = $args['settings'];
		$transport   = self::resolve_transport( $args );
		$default     = $args['default'] ?? '';
		$sanitize_cb = self::resolve_sanitize_callback( $type, $args );

		$wp_customize->add_setting( $setting_id, array(
			'default'           => $default,
			'transport'         => $transport,
			'sanitize_callback' => $sanitize_cb,
			'capability'        => $args['capability'] ?? 'edit_theme_options',
		) );

		$control_args = self::build_control_args( $type, $args );

		if ( $is_custom ) {
			self::require_control( $control_class );
			$wp_customize->add_control( new $control_class( $wp_customize, $setting_id, $control_args ) );
		} else {
			$control_args['type'] = self::map_core_type( $type );
			$wp_customize->add_control( $setting_id, $control_args );
		}
	}

	/**
	 * Resolve Kirki-style 'auto' transport to postMessage if output is provided,
	 * else refresh. Pass-through for refresh/postMessage.
	 */
	protected static function resolve_transport( array $args ): string {
		$t = $args['transport'] ?? 'refresh';
		if ( 'auto' === $t ) {
			return ! empty( $args['output'] ) ? 'postMessage' : 'refresh';
		}
		return in_array( $t, array( 'refresh', 'postMessage' ), true ) ? $t : 'refresh';
	}

	/**
	 * Pick a sane sanitize callback by type, unless the consumer overrode it.
	 */
	protected static function resolve_sanitize_callback( string $type, array $args ): callable {
		if ( isset( $args['sanitize_callback'] ) && is_callable( $args['sanitize_callback'] ) ) {
			return $args['sanitize_callback'];
		}
		switch ( $type ) {
			case 'color':
				return 'sanitize_hex_color';
			case 'url':
				return 'esc_url_raw';
			case 'textarea':
				return 'sanitize_textarea_field';
			case 'switch':
			case 'checkbox':
				return array( __CLASS__, 'sanitize_bool_int' );
			case 'select':
			case 'radio':
			case 'radio_image':
			case 'radio_buttonset':
				return 'sanitize_key';
			case 'image':
			case 'upload':
				return 'esc_url_raw';
			case 'background':
				return array( __CLASS__, 'sanitize_background' );
			case 'repeater':
			case 'sortable':
				return array( __CLASS__, 'sanitize_json_array' );
			case 'typography':
				return array( __CLASS__, 'sanitize_typography' );
			case 'dimension':
				return array( __CLASS__, 'sanitize_dimension' );
			default:
				return 'sanitize_text_field';
		}
	}

	/**
	 * Coerce truthy values to 1, falsy to 0. Used for switch + checkbox.
	 *
	 * Accepts every shape Kirki produced + theme defaults:
	 *   - '1', 1, true  → 1
	 *   - 'on', 'yes', 'true', 'enable' → 1 (Kirki choices key, default values)
	 *   - '0', 0, false, '', null, 'off', 'no', 'disable' → 0
	 *
	 * This catches a customer-data-loss bug where switches with default 'on'
	 * (e.g. site_custom_colors, site_breadcrumbs, buddypress_avatar_style)
	 * would silently flip OFF on save because the strict comparison missed
	 * the 'on' string.
	 */
	public static function sanitize_bool_int( $value ): int {
		if ( is_bool( $value ) ) {
			return $value ? 1 : 0;
		}
		if ( is_int( $value ) ) {
			return $value ? 1 : 0;
		}
		$truthy = array( '1', 'on', 'yes', 'true', 'enable' );
		return in_array( strtolower( (string) $value ), $truthy, true ) ? 1 : 0;
	}

	/**
	 * Sanitize repeater/sortable JSON-or-array values to a JSON string.
	 * Always emits a valid JSON array string.
	 */
	public static function sanitize_json_array( $value ): string {
		if ( is_array( $value ) ) {
			return wp_json_encode( $value );
		}
		$decoded = json_decode( (string) $value, true );
		return is_array( $decoded ) ? wp_json_encode( $decoded ) : '[]';
	}

	/**
	 * Sanitize a typography array. Whitelisted keys, plain text values.
	 * Tolerant of Kirki legacy 'variant' key which is normalized at read time
	 * by Output_Builder, not here.
	 */
	public static function sanitize_typography( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}
		$out  = array();
		$keys = array(
			'font-family',
			'font-weight',
			'variant',
			'font-size',
			'line-height',
			'letter-spacing',
			'text-transform',
			'font-style',
			'text-align',
			'text-decoration',
			'color',
		);
		foreach ( $keys as $k ) {
			if ( isset( $value[ $k ] ) ) {
				$out[ $k ] = sanitize_text_field( (string) $value[ $k ] );
			}
		}
		return $out;
	}

	/**
	 * Sanitize a Background composite value — array with the 6 background-* keys.
	 * Whitelists keys; passes string values through sanitize_text_field; URL key
	 * goes through esc_url_raw.
	 */
	public static function sanitize_background( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}
		$out  = array();
		$keys = array(
			'background-color',
			'background-image',
			'background-repeat',
			'background-position',
			'background-size',
			'background-attachment',
		);
		foreach ( $keys as $k ) {
			if ( ! isset( $value[ $k ] ) ) {
				continue;
			}
			$v = (string) $value[ $k ];
			$out[ $k ] = ( 'background-image' === $k ) ? esc_url_raw( $v ) : sanitize_text_field( $v );
		}
		return $out;
	}

	/**
	 * Sanitize a dimension string like '120px' / '1.5rem'.
	 * Allows numeric + (px|em|rem|%|vh|vw); falls back to '' on invalid.
	 */
	public static function sanitize_dimension( $value ): string {
		$v = trim( (string) $value );
		if ( '' === $v ) {
			return '';
		}
		if ( preg_match( '/^-?\d+(\.\d+)?(px|em|rem|%|vh|vw)?$/i', $v ) ) {
			return $v;
		}
		return '';
	}

	/**
	 * Build the args array passed to add_control() / control constructor.
	 *
	 * Strips framework-internal/setting keys; everything else passes through so
	 * Kirki-shape args (fields, row_label, multiple, mode, etc.) reach control
	 * classes whose public properties match. Compiles array-form active_callback
	 * to a closure.
	 */
	protected static function build_control_args( string $type, array $args ): array {
		// Strip keys consumed by Field::register_with_manager / add_setting.
		$internal = array( '_type', 'settings', 'default', 'transport', 'sanitize_callback', 'capability' );
		$out      = array_diff_key( $args, array_flip( $internal ) );

		$out['label']       = $args['label']       ?? '';
		$out['description'] = $args['description'] ?? '';
		$out['section']     = $args['section'];
		$out['priority']    = $args['priority']    ?? 10;

		// Compile array-form active_callback (Kirki shape) to a closure.
		if ( isset( $out['active_callback'] ) && is_array( $out['active_callback'] ) ) {
			require_once __DIR__ . '/Active_Callback.php';
			$out['active_callback'] = Active_Callback::compile( $out['active_callback'] );
		}
		return $out;
	}

	/**
	 * Map our string field type to a core WP_Customize_Control 'type' attr
	 * for the core-dispatched types.
	 */
	protected static function map_core_type( string $type ): string {
		$map = array(
			'text'           => 'text',
			'textarea'       => 'textarea',
			'url'            => 'url',
			'select'         => 'select',
			'radio'          => 'radio',
			'dropdown-pages' => 'dropdown-pages',
		);
		return $map[ $type ] ?? 'text';
	}

	/**
	 * require_once the file housing a custom control class. Core WP control
	 * classes are already autoloaded; this only loads framework controls in
	 * Controls/ that follow PSR-4 (short class name + .php).
	 */
	protected static function require_control( string $class ): void {
		if ( class_exists( $class, false ) ) {
			return;
		}
		$short = substr( $class, strrpos( $class, '\\' ) + 1 );
		$file  = __DIR__ . '/Controls/' . $short . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}
