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
	 * This function loads the WooCommerce cart script and displays
	 * the cart icon with the number of items in the cart if there
	 * are any items present.
	 */
	function buddyx_render_cart_icon() {
		// Check if WooCommerce is active and if the cart has items.
		if ( function_exists( 'is_woocommerce' ) && WC()->cart->cart_contents_count > 0 ) {
			// Enqueue WooCommerce cart script.
			wp_enqueue_script( 'woocommerce-cart' );
		}
		?>
		<div class="cart">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View Shopping Cart', 'buddyx' ); ?>">
				<span class="fa fa-shopping-cart"> </span>
				<?php
				// Get the count of items in the cart.
				$count = WC()->cart->cart_contents_count;
				if ( $count > 0 ) {
					// Display the item count as a superscript if there are items.
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
		// Check if there are items in the cart.
		if ( WC()->cart->get_cart_contents_count() > 0 ) {
			// Update the cart icon fragment with the current cart contents count.
			$fragments['.menu-icons-wrapper .cart a'] = '<a class="menu-icons-wrapper cart" href="' . esc_url( wc_get_cart_url() ) . '" title="' . esc_attr__( 'View your shopping cart', 'buddyx' ) . '"><span class="fa fa-shopping-cart"></span><sup>' . esc_html( WC()->cart->get_cart_contents_count() ) . '</sup></a>';
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
