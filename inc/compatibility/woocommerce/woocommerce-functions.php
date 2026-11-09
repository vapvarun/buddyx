<?php
/**
 * The `buddyx()` woocommerce functions.
 *
 * @link    https://wbcomdesigns.com/
 * @package buddyx
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'buddyx_cart_icon_url' ) ) {
	/**
	 * The cart page URL for the header cart icon, or '' when there is no valid cart page.
	 *
	 * If the cart page option points at a deleted or unpublished page,
	 * `wc_get_cart_url()` silently falls back to home_url(); the icon would
	 * then send shoppers to the homepage, which is worse than not showing it.
	 *
	 * @return string
	 */
	function buddyx_cart_icon_url() {
		if ( ! WC()->cart ) {
			return '';
		}
		$cart_page_id = wc_get_page_id( 'cart' );
		if ( $cart_page_id <= 0 || 'publish' !== get_post_status( $cart_page_id ) ) {
			return '';
		}
		$cart_url = wc_get_cart_url();
		if ( empty( $cart_url ) || home_url() === $cart_url || home_url( '/' ) === $cart_url ) {
			return '';
		}
		return $cart_url;
	}
}

if ( ! function_exists( 'buddyx_cart_icon_link' ) ) {
	/**
	 * The header cart link with its item count. The header and the AJAX cart
	 * fragment both print this, so the icon looks the same after add to cart.
	 *
	 * @param string $cart_url Cart page URL from buddyx_cart_icon_url().
	 * @return string Link HTML, built from escaped parts.
	 */
	function buddyx_cart_icon_link( $cart_url ) {
		$count = WC()->cart->get_cart_contents_count();
		return sprintf(
			'<a href="%1$s" title="%2$s">%3$s%4$s</a>',
			esc_url( $cart_url ),
			esc_attr__( 'View Shopping Cart', 'buddyx' ),
			buddyx_get_icon( 'shopping-cart' ),
			$count > 0 ? '<sup>' . esc_html( $count ) . '</sup>' : ''
		);
	}
}

if ( ! function_exists( 'buddyx_render_cart_icon' ) ) {
	/**
	 * Renders the header cart icon with its item count, when a valid cart page is set.
	 */
	function buddyx_render_cart_icon() {
		$cart_url = buddyx_cart_icon_url();
		if ( '' === $cart_url ) {
			return;
		}

		// Enqueue WC cart fragment script when there's something in the cart.
		if ( WC()->cart->get_cart_contents_count() > 0 ) {
			wp_enqueue_script( 'woocommerce-cart' );
		}

		echo '<div class="cart">' . buddyx_cart_icon_link( $cart_url ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- buddyx_cart_icon_link() escapes every part.
	}
}

/**
 * Woocommerce_cart_collaterals
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10 );

/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'buddyx_header_add_to_cart_fragment' );

if ( ! function_exists( 'buddyx_header_add_to_cart_fragment' ) ) {
	/**
	 * Refreshes the header cart link after an AJAX add to or remove from cart.
	 *
	 * Replaces only the link, so listeners on the wrapper keep working; the
	 * count also clears when the last item is removed.
	 *
	 * @param array $fragments Cart fragments keyed by CSS selector.
	 * @return array
	 */
	function buddyx_header_add_to_cart_fragment( $fragments ) {
		$cart_url = buddyx_cart_icon_url();
		if ( '' !== $cart_url ) {
			$fragments['.menu-icons-wrapper .cart a'] = buddyx_cart_icon_link( $cart_url );
		}
		return $fragments;
	}
}

// Disable WooCommerce sidebar.
if ( ! function_exists( 'buddyx_disable_woo_commerce_sidebar' ) ) {
	/**
	 * Removes the default WooCommerce sidebar.
	 * This function is hooked to the 'init' action to ensure it runs after WooCommerce is fully loaded.
	 * It removes the 'woocommerce_get_sidebar' function from the 'woocommerce_sidebar' action hook.
	 */
	function buddyx_disable_woo_commerce_sidebar() {
		// Remove the default WooCommerce sidebar.
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
}

// Hook the function to the 'init' action.
add_action( 'init', 'buddyx_disable_woo_commerce_sidebar' );

/**
 * Add a body class on WooCommerce product archives when Infinite Loader for WooCommerce is active.
 *
 * @param array $classes Body classes.
 * @return array Filtered body classes.
 */
function buddyx_add_body_class_for_infinite_loade( $classes ) {

	if ( class_exists( 'Infinite_Loader_For_Woocommerce' ) ) {

		if ( is_post_type_archive( 'product' ) || is_product_category() || is_product_tag() ) {
			$classes[] = 'infinite-loader-active';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'buddyx_add_body_class_for_infinite_loade' );
