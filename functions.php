<?php
/**
 * BuddyX functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package buddyx
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add LiveReload script in development mode.
 */

define( 'BUDDYX_MINIMUM_WP_VERSION', '5.4' );
define( 'BUDDYX_MINIMUM_PHP_VERSION', '8.0' );




/**
 * Load core theme files
 *
 * Loads only essential theme files that are required regardless of
 * plugin configuration or context.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_load_core() {
	static $core_loaded = false;

	if ( $core_loaded ) {
		return;
	}

	$base_path = get_template_directory();

	// Load essential core files
	$core_files = array(
		'/inc/wordpress-shims.php',
		'/inc/functions.php',
	);

	foreach ( $core_files as $file ) {
		$file_path = $base_path . $file;
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}

	$core_loaded = true;
}

/**
 * Load plugin compatibility files
 *
 * Conditionally loads plugin-specific compatibility files only when
 * the corresponding plugins are active and detected.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_load_plugin_support() {
	static $plugins_checked = false;

	if ( $plugins_checked ) {
		return;
	}

	$base_path = get_template_directory();

	// BuddyPress compatibility
	if ( function_exists( 'buddypress' ) || class_exists( 'BuddyPress' ) ) {
		$buddypress_file = $base_path . '/inc/compatibility/buddypress/buddypress-functions.php';
		if ( file_exists( $buddypress_file ) ) {
			require_once $buddypress_file;
		}
	}

	// rtMedia compatibility
	if ( class_exists( 'RTMedia' ) || function_exists( 'rtmedia_init' ) ) {
		$rtmedia_file = $base_path . '/inc/compatibility/rtmedia/rtmedia-functions.php';
		if ( file_exists( $rtmedia_file ) ) {
			require_once $rtmedia_file;
		}
	}

	// WooCommerce compatibility
	if ( class_exists( 'WooCommerce' ) || function_exists( 'WC' ) ) {
		$woocommerce_file = $base_path . '/inc/compatibility/woocommerce/woocommerce-functions.php';
		if ( file_exists( $woocommerce_file ) ) {
			require_once $woocommerce_file;
		}
	}

	// SureCart compatibility
	if ( defined( 'SURECART_PLUGIN_FILE' ) ) {
		$surecart_file = $base_path . '/inc/compatibility/surecart/surecart-functions.php';
		if ( file_exists( $surecart_file ) ) {
			require_once $surecart_file;
		}
	}

	// FluentCart compatibility
	if ( defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
		$fluentcart_file = $base_path . '/inc/compatibility/fluentcart/fluentcart-functions.php';
		if ( file_exists( $fluentcart_file ) ) {
			require_once $fluentcart_file;
		}
	}

	$plugins_checked = true;
}

/**
 * Load context-specific files
 *
 * Loads files based on current context (admin vs frontend) to avoid
 * loading unnecessary components.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_load_contextual() {
	static $context_loaded = false;

	if ( $context_loaded ) {
		return;
	}

	$base_path = get_template_directory();

	// Frontend-only components
	$frontend_files = array(
		'/inc/widgets/bp-profile-completion-widget.php',
		'/inc/class-buddyx-breadcrumbs.php',
		'/inc/extra.php',
		'/inc/login.php',
		'/inc/Webfont/class-buddyx-webfont-loader.php',
	);

	foreach ( $frontend_files as $file ) {
		$file_path = $base_path . $file;
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}

	$context_loaded = true;
}

/**
 * Optimized autoloader for BuddyX classes
 *
 * Efficiently loads BuddyX theme classes with caching to avoid
 * repeated file system checks.
 *
 * @since 2.0.0
 * @param string $class_name The fully qualified class name.
 * @return bool True if class was loaded, false otherwise.
 */
function _buddyx_autoload( $class_name ) {
	// Quick namespace validation - fail fast for non-theme classes
	if ( 0 !== strpos( $class_name, 'BuddyX\\' ) ) {
		return false;
	}

	// Cache results to avoid repeated file system operations
	static $loaded_classes = array();

	if ( isset( $loaded_classes[ $class_name ] ) ) {
		return $loaded_classes[ $class_name ];
	}

	// Process BuddyX namespace classes
	$namespace_prefix = 'BuddyX\Buddyx\\';
	if ( 0 === strpos( $class_name, $namespace_prefix ) ) {
		$relative_class = substr( $class_name, strlen( $namespace_prefix ) );
		$file_path      = get_template_directory() . '/inc/' . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file_path ) ) {
			require_once $file_path;
			$loaded_classes[ $class_name ] = true;
			return true;
		}
	}

	// Cache negative results to avoid repeated failed attempts
	$loaded_classes[ $class_name ] = false;
	return false;
}

/**
 * Register autoloader safely
 *
 * Registers the custom autoloader only if it's not already registered
 * and Composer autoloader is not available.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_register_autoloader() {
	$template_dir = get_template_directory();

	// Prefer Composer autoloader if available
	if ( file_exists( $template_dir . '/vendor/autoload.php' ) ) {
		require_once $template_dir . '/vendor/autoload.php';
		return;
	}

	// Check if custom autoloader is already registered
	$current_functions  = spl_autoload_functions() ?: array();
	$already_registered = false;

	foreach ( $current_functions as $function ) {
		if ( ( is_string( $function ) && '_buddyx_autoload' === $function ) ||
			( is_array( $function ) && isset( $function[1] ) && '_buddyx_autoload' === $function[1] ) ) {
			$already_registered = true;
			break;
		}
	}

	if ( ! $already_registered ) {
		spl_autoload_register( '_buddyx_autoload' );
	}
}

/**
 * Initialize theme components
 *
 * Main initialization function that loads all theme components
 * in the correct order with proper guards against multiple execution.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_initialize() {
	static $initialized = false;

	if ( $initialized ) {
		return;
	}

	// Load components in dependency order
	buddyx_load_core();
	buddyx_load_contextual();
	buddyx_load_plugin_support();

	// Initialize main theme class if available
	if ( function_exists( 'BuddyX\Buddyx\buddyx' ) ) {
		call_user_func( 'BuddyX\Buddyx\buddyx' );
	}

	$initialized = true;
}

/**
 * Setup theme default options
 *
 * Sets up default BuddyPress Nouveau appearance options on theme activation.
 * Uses static guard to prevent multiple executions.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_setup_theme_defaults() {
	static $defaults_configured = false;

	if ( $defaults_configured ) {
		return;
	}

	$option_key = 'buddyx_theme_is_activated';

	if ( ! get_option( $option_key, false ) ) {
		$bp_nouveau_appearance = array(
			'members_layout'         => 3,
			'members_friends_layout' => 3,
			'groups_layout'          => 3,
			'members_group_layout'   => 3,
			'group_front_page'       => 0,
			'group_front_boxes'      => 0,
			'user_front_page'        => 0,
			'user_nav_display'       => 1,
			'group_nav_display'      => 1,
		);

		update_option( 'bp_nouveau_appearance', $bp_nouveau_appearance );
		update_option( $option_key, 1 );
	}

	$defaults_configured = true;
}

/**
 * Clean up unused assets
 *
 * Removes styles and scripts for inactive plugins to reduce memory usage
 * and prevent unnecessary HTTP requests.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_cleanup_unused_assets() {
	// Remove WooCommerce assets if plugin is not active
	if ( ! class_exists( 'WooCommerce' ) ) {
		wp_dequeue_style( 'buddyx-woocommerce' );
		wp_deregister_style( 'buddyx-woocommerce' );
	}

	// Remove BuddyPress assets if plugin is not active
	if ( ! function_exists( 'buddypress' ) && ! class_exists( 'BuddyPress' ) ) {
		wp_dequeue_style( 'buddyx-buddypress' );
		wp_deregister_style( 'buddyx-buddypress' );
	}
}

/**
 * Add conditional theme support
 *
 * Adds theme support features only for active plugins to avoid
 * unnecessary functionality loading.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_add_conditional_theme_support() {
	// WooCommerce theme support
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	// BuddyPress theme support
	if ( function_exists( 'buddypress' ) || class_exists( 'BuddyPress' ) ) {
		add_theme_support( 'buddypress-use-nouveau' );
	}
}

/**
 * Apply WooCommerce optimizations
 *
 * Removes default WooCommerce elements that are replaced by theme
 * implementations to avoid conflicts and duplication.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_woocommerce_optimizations() {
	if ( class_exists( 'WooCommerce' ) ) {
		// Remove default WooCommerce breadcrumbs (theme provides its own)
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}
}

/**
 * Display BuddyPress legacy template notice
 *
 * Shows admin notice if BuddyPress is active but not using the required
 * Nouveau template pack.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_buddypress_legacy_notice() {
	if ( ! is_admin() ) {
		return;
	}

	if ( function_exists( 'buddypress' ) &&
		function_exists( 'bp_get_theme_compat_id' ) &&
		'nouveau' !== bp_get_theme_compat_id() ) {
		?>
		<div class="notice notice-error">
			<p>
				<?php
				printf(
					/* translators: %1$s: opening link tag, %2$s: closing link tag */
					esc_html__( 'BuddyX requires the BuddyPress Template Pack "BP Nouveau" to be active. Please activate this Template Pack from the %1$sBuddyPress Options%2$s.', 'buddyx' ),
					'<a href="' . esc_url( admin_url( 'admin.php?page=bp-settings' ) ) . '">',
					'</a>'
				);
				?>
			</p>
		</div>
		<?php
	}
}

/**
 * Check system requirements compatibility
 *
 * Verifies that the current environment meets minimum requirements
 * for the BuddyX theme.
 *
 * @since 2.0.0
 * @return bool True if requirements are met, false otherwise.
 */
function buddyx_compatibility_check() {
	$wp_version  = $GLOBALS['wp_version'];
	$php_version = phpversion();

	// Define minimum requirements
	$min_wp_version  = '4.5';
	$min_php_version = '7.0';

	// Check WordPress version
	if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
		return false;
	}

	// Check PHP version
	if ( version_compare( $php_version, $min_php_version, '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Load external dependencies
 *
 * Loads external libraries and dependencies required by the theme.
 *
 * @since 2.0.0
 * @return void
 */
function buddyx_load_external_dependencies() {
	$base_path = get_template_directory();

	$external_files = array(
		'/external/require_plugins.php',
		'/external/include-kirki.php',
		'/external/kirki-utils.php',
	);

	foreach ( $external_files as $file ) {
		$file_path = $base_path . $file;
		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}
}

/**
 * Filters whether the current BuddyBoss page is a directory page.
 *
 * This function checks if the currently viewed page is either the "Register" or "Activate" page
 * defined in BuddyBoss/BuddyPress settings. If so, it sets the `$bp_is_directory` flag to false
 * to indicate that these pages are not considered directory pages.
 *
 * This is useful for preventing certain directory-specific behaviors or templates from applying
 * to the registration or activation pages.
 *
 * @param bool $bp_is_directory Indicates if the current page is a BuddyBoss directory page.
 *
 * @return bool Returns false if the current page is the BuddyBoss/BuddyPress register or activate page;
 *              otherwise returns the original value of `$bp_is_directory`.
 */
function buddyx_page_templates_directory_only( $bp_is_directory ) {
	global $wp_query;
	$bp_pages = get_option( 'bp-pages' );

	/* Register page id and BuddyBoss saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && get_the_ID() != 0 && $bp_pages['register'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Register page id and buddypress saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['register'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	/* Activate page id and BuddyBoss saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && get_the_ID() != 0 && $bp_pages['activate'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Activate page id and buddypress saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['activate'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	return $bp_is_directory;
}

// =============================================================================
// THEME INITIALIZATION
// =============================================================================

// Check system compatibility before proceeding
if ( ! buddyx_compatibility_check() ) {
	// Load backward compatibility file for unsupported environments
	$back_compat_file = get_template_directory() . '/inc/back-compat.php';
	if ( file_exists( $back_compat_file ) ) {
		require_once $back_compat_file;
	}
	return;
}

// Register autoloader
buddyx_register_autoloader();

// Load external dependencies
buddyx_load_external_dependencies();

// Hook theme initialization into WordPress
add_action( 'after_setup_theme', 'buddyx_initialize', 1 );
add_action( 'after_setup_theme', 'buddyx_add_conditional_theme_support', 15 );
if ( class_exists( 'BuddyPress' ) ) {
	add_filter( 'bp_nouveau_theme_compat_page_templates_directory_only', 'buddyx_page_templates_directory_only' );
	add_action( 'init', 'buddyx_setup_theme_defaults', 5 );
}
add_action( 'init', 'buddyx_woocommerce_optimizations' );
add_action( 'wp_enqueue_scripts', 'buddyx_cleanup_unused_assets', 999 );
add_action( 'admin_notices', 'buddyx_buddypress_legacy_notice' );