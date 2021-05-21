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

/**
 * Checks if the BP Template pack is Nouveau when BuddyPress is active.
 *
 * @return boolean False if BuddyPress is not active or Nouveau is the active Template Pack.
 *                 True otherwise.
 */
function buddyx_template_pack_check() {
	$retval = false;

	if ( function_exists( 'buddypress' ) ) {
		$retval = 'nouveau' !== bp_get_theme_compat_id();
	}

	return $retval;
}

function buddyx_buddypress_legacy_notice() {
	if ( buddyx_template_pack_check() ) {
		?>
		<div class="error"><p>
		<?php printf( esc_html__( 'BuddyX requires the BuddyPress Template Pack "BP Nouveau" to be active. Please activate this Template Pack from the %sBuddyPress Options.%s', 'buddyx' ), '<a href="'. admin_url('admin.php?page=bp-settings').'" >', '</a>' ); ?>
		</p></div>
		<?php
	}
}

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], BUDDYX_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), BUDDYX_MINIMUM_PHP_VERSION, '<' ) || buddyx_template_pack_check() ) {
	require get_template_directory() . '/inc/back-compat.php';
	add_action( 'admin_notices', 'buddyx_buddypress_legacy_notice' );
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Include Kirki
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

// Require plugin.php to use is_plugin_active() below
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Load theme breadcrumbs function.
require get_template_directory() . '/inc/class-buddyx-breadcrumbs.php';

// Load BuddyPress PRofile Completion widget.
require get_template_directory() . '/inc/widgets/bp-profile-completion-widget.php';

// Load theme extra function.
require get_template_directory() . '/inc/extra.php';

// bp_nouveau_appearance default option
$optionKey = 'buddyx_theme_is_activated';
if ( ! get_option( $optionKey ) ) {

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
	update_option( $optionKey, 1 );
}

//
// Add WooCommerce Support
// ------------------------------------------------------------------------------
if ( ! function_exists( 'buddyx_woocommerce_support' ) ) {

	function buddyx_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'buddyx_woocommerce_support' );
}

//
// force add theme support for BP nouveau
// ------------------------------------------------------------------------------
if ( ! function_exists( 'buddyx_buddypress_nouveau_support' ) ) {

	function buddyx_buddypress_nouveau_support() {
		add_theme_support( 'buddypress-use-nouveau' );
	}

	add_action( 'after_setup_theme', 'buddyx_buddypress_nouveau_support' );
}

/**
 * Remove WooCommerce the breadcrumbs
 */
add_action( 'init', 'buddyx_remove_wc_breadcrumbs' );
function buddyx_remove_wc_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

/**
 * Remove WooCommerce CSS if WooCommerce not activated
 */
function buddyx_woo_dequeue_styles() {
	wp_dequeue_style( 'buddyx-woocommerce' );
	wp_deregister_style( 'buddyx-woocommerce' );
}
if ( ! class_exists( 'WooCommerce' ) ) {
	add_action( 'wp_print_styles', 'buddyx_woo_dequeue_styles' );
}


/*
 * Return $bp_is_directory false when buddypress register page view.
 *
 */
add_filter( 'bp_nouveau_theme_compat_page_templates_directory_only', 'buddyx_page_templates_directory_only' );
function buddyx_page_templates_directory_only( $bp_is_directory ) {
	global $wp_query;
	$bp_pages = get_option( 'bp-pages' );

	/* Register page id and BuddyBoss saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && get_the_ID() != 0 && $bp_pages['register'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Register page id and BuddyPress saved register page equal then bp_is_directory set false */
	if ( isset( $bp_pages['register'] ) && $bp_pages['register'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['register'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	/* Activate page id and BuddyBoss saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && get_the_ID() != 0 && $bp_pages['activate'] == get_the_ID() ) {
		$bp_is_directory = false;
	}

	/* Activate page id and BuddyPress saved activate page equal then bp_is_directory set false */
	if ( isset( $bp_pages['activate'] ) && $bp_pages['activate'] != '' && $wp_query->queried_object_id != 0 && $bp_pages['activate'] == $wp_query->queried_object_id ) {
		$bp_is_directory = false;
	}

	return $bp_is_directory;
}
