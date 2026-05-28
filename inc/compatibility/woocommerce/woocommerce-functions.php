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

if ( ! function_exists( 'buddyx_render_cart_icon' ) ) {
	/**
	 * Renders the shopping cart icon with item count.
	 *
	 * Only renders when WooCommerce is active AND a valid cart page is
	 * configured. If the cart page option points at a deleted page,
	 * `wc_get_cart_url()` silently falls back to home_url() — clicking
	 * the cart icon would then send shoppers to the homepage instead of
	 * a cart, which is worse than not showing the icon at all.
	 */
	function buddyx_render_cart_icon() {
		// Bail if WooCommerce isn't active.
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_cart_url' ) ) {
			return;
		}

		// Bail if no valid cart page is configured (deleted / missing / unpublished).
		$cart_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'cart' ) : 0;
		if ( $cart_page_id <= 0 || 'publish' !== get_post_status( $cart_page_id ) ) {
			return;
		}

		$cart_url = wc_get_cart_url();
		if ( empty( $cart_url ) || $cart_url === home_url() || $cart_url === home_url( '/' ) ) {
			return;
		}

		// Enqueue WC cart fragment script when there's something in the cart.
		if ( WC()->cart && WC()->cart->cart_contents_count > 0 ) {
			wp_enqueue_script( 'woocommerce-cart' );
		}
		?>
		<div class="cart">
			<a href="<?php echo esc_url( $cart_url ); ?>" title="<?php esc_attr_e( 'View Shopping Cart', 'buddyx' ); ?>">
				<span class="fa fa-shopping-cart"></span>
				<?php
				$count = WC()->cart ? WC()->cart->cart_contents_count : 0;
				if ( $count > 0 ) {
					?>
					<sup><?php echo esc_html( $count ); ?></sup>
					<?php
				}
				?>
			</a>
		</div>
		<?php
	}
}

/**
 * Woocommerce_cart_collaterals
 */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10 );

/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'buddyx_header_add_to_cart_fragment' );

// Update the cart icon fragment for AJAX requests.
if ( ! function_exists( 'buddyx_header_add_to_cart_fragment' ) ) {
	/**
	 * Adds or updates the cart icon fragment for AJAX requests.
	 * This function is used to dynamically update the cart icon in the header when items are added to the cart.
	 *
	 * @param array $fragments An associative array of HTML fragments to be updated.
	 * @return array Updated fragments with the cart icon HTML.
	 */
	function buddyx_header_add_to_cart_fragment( $fragments ) {
		if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
			return $fragments;
		}

		// Skip the fragment update if no valid cart page is configured —
		// otherwise we would refresh the icon to point at the homepage.
		$cart_page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'cart' ) : 0;
		if ( $cart_page_id <= 0 || 'publish' !== get_post_status( $cart_page_id ) ) {
			return $fragments;
		}

		$cart_url = wc_get_cart_url();
		if ( empty( $cart_url ) || $cart_url === home_url() || $cart_url === home_url( '/' ) ) {
			return $fragments;
		}

		if ( WC()->cart->get_cart_contents_count() > 0 ) {
			$fragments['.menu-icons-wrapper .cart a'] = '<a class="menu-icons-wrapper cart" href="' . esc_url( $cart_url ) . '" title="' . esc_attr__( 'View your shopping cart', 'buddyx' ) . '"><span class="fa fa-shopping-cart"></span><sup>' . esc_html( WC()->cart->get_cart_contents_count() ) . '</sup></a>';
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

// Check if the Infinite Loader class exists.
function buddyx_add_body_class_for_infinite_loade( $classes ) {
    
    if ( class_exists( 'Infinite_Loader_For_Woocommerce' ) ) {

        if (is_post_type_archive('product') || is_product_category() || is_product_tag()) {
            $classes[] = 'infinite-loader-active';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'buddyx_add_body_class_for_infinite_loade' );
