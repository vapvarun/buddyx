<?php
/**
 * Buddyx functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package buddyx
 */

define( 'BUDDYX_MINIMUM_WP_VERSION', '4.5' );
define( 'BUDDYX_MINIMUM_PHP_VERSION', '7.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], BUDDYX_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), BUDDYX_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

//Include Kirki
require get_template_directory() . '/external/require_plugins.php';
require_once get_template_directory() . '/external/include-kirki.php';
require_once get_template_directory() . '/external/kirki-utils.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @access private
	 *
	 * @param string $class_name Class name to load.
	 * @return bool True if the class was loaded, false otherwise.
	 */
	function _buddyx_autoload( $class_name ) {
		$namespace = 'BuddyX\Buddyx';

		if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( '_buddyx_autoload' );
}

// Load the `buddyx()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'BuddyX\Buddyx\buddyx' );

// Load theme extra function.
require get_template_directory() . '/inc/extra.php';

// bp_nouveau_appearance default option
$optionKey = "buddyx_theme_is_activated";
if ( !get_option( $optionKey ) ) {

	$bp_nouveau_appearance = array(
		'members_layout'		 => 3,
		'members_friends_layout' => 3,
		'groups_layout'			 => 3,
		'members_group_layout'	 => 3,
		'group_front_page'		 => 0,
		'group_front_boxes'		 => 0,
		'user_front_page'		 => 0,
		'user_nav_display'		 => 1,
		'group_nav_display'		 => 1,
	);
	update_option( 'bp_nouveau_appearance', $bp_nouveau_appearance );
	update_option( $optionKey, 1 );
}

//
// Add WooCommerce Support
// ------------------------------------------------------------------------------
if ( !function_exists( 'buddyx_woocommerce_support' ) ) {

	function buddyx_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'buddyx_woocommerce_support' );
}

/**
 * Remove WooCommerce the breadcrumbs
 */
add_action( 'init', 'woo_remove_wc_breadcrumbs' );
function woo_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

/**
 * Remove WooCommerce CSS if WooCommerce not activated
 */
function woo_dequeue_styles() {
    wp_dequeue_style( 'buddyx-woocommerce' );
        wp_deregister_style( 'buddyx-woocommerce' );
}
if ( !class_exists( 'WooCommerce' ) ) {
add_action( 'wp_print_styles', 'woo_dequeue_styles' );
}

/**
 * Added function for theme updater
 */
function buddyx_theme_updater() {
	require( get_template_directory() . '/updater/theme-updater.php' );
}
add_action( 'after_setup_theme', 'buddyx_theme_updater' );
