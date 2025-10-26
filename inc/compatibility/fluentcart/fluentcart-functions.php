<?php
/**
 * FluentCart Support for BuddyX Theme
 *
 * This file contains all the functions and hooks needed to integrate
 * FluentCart e-commerce functionality with the BuddyX theme.
 *
 * @package    BuddyX
 * @subpackage FluentCart
 * @since      1.0.0
 * @version    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if FluentCart is active.
if ( ! defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
	return;
}

/**
 * BuddyX FluentCart Support Class
 *
 * Handles all FluentCart integration functionality including cart display,
 * page templates, and customizer options.
 *
 * @since 1.0.0
 */
class BuddyX_FluentCart_Support {

	/**
	 * The single instance of the class.
	 *
	 * @var BuddyX_FluentCart_Support|null
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main instance.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return BuddyX_FluentCart_Support Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function init_hooks() {
		// Single product page modifications.
		add_action( 'wp', array( $this, 'buddyx_fluentcart_single_page_setup' ) );

		// Archive page modifications.
		add_action( 'wp', array( $this, 'buddyx_fluentcart_archive_page_setup' ) );

		// Add sub-header to FluentCart archive pages.
		add_action( 'fluent_cart/generic_template/before_content', array( $this, 'buddyx_fluentcart_render_archive_sub_header' ) );

		// Override sidebar for single products based on customizer setting.
		add_filter( 'theme_mod_sidebar_option', array( $this, 'buddyx_fluentcart_override_sidebar_option' ) );
		add_filter( 'theme_mod_single_post_sidebar_option', array( $this, 'buddyx_fluentcart_override_sidebar_option' ) );

		// Theme activation - run once.
		add_action( 'after_switch_theme', array( $this, 'buddyx_fluentcart_set_theme_defaults' ) );

		// Add customizer options.
		add_action( 'init', array( $this, 'buddyx_fluentcart_add_customizer_option' ), 20 );

		// Cart display setup - only if WooCommerce and SureCart are not active.
		if ( ! class_exists( 'WooCommerce' ) && ! defined( 'SURECART_PLUGIN_FILE' ) ) {
			add_action( 'init', array( $this, 'buddyx_fluentcart_setup_cart_display' ), 5 );
		}

		// Add body class for FluentCart pages.
		add_filter( 'body_class', array( $this, 'buddyx_fluentcart_body_classes' ) );

		// Disable floating cart button on checkout pages.
		add_filter( 'fluent_cart/buttons/enable_floating_cart_button', array( $this, 'buddyx_fluentcart_disable_floating_button' ), 10, 2 );
	}

	/**
	 * Setup modifications for FluentCart single product pages.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_single_page_setup() {
		// Early return if not a FluentCart single page.
		if ( ! is_singular( 'fluent-products' ) ) {
			return;
		}

		// Remove all actions from buddyx_sub_header hook.
		remove_all_actions( 'buddyx_sub_header' );

		// Prevent entry header from being loaded.
		add_filter( 'get_post_type', array( $this, 'buddyx_fluentcart_prevent_entry_header' ), 10, 2 );
	}

	/**
	 * Prevent entry header from showing on FluentCart products.
	 *
	 * The theme shows entry-header for post type 'post', so we temporarily
	 * change the post type to prevent this.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string   $post_type The post type.
	 * @param int|null $post_id   The post ID.
	 * @return string Modified post type.
	 */
	public function buddyx_fluentcart_prevent_entry_header( $post_type, $post_id = null ) {
		// Only modify when checking for entry header template part.
		if ( doing_action( 'get_template_part_template-parts/content/entry-header' ) ) {
			return 'fluent-products'; // Return actual type to prevent 'post' match.
		}
		return $post_type;
	}

	/**
	 * Override sidebar option for FluentCart post types.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $value Current sidebar option value.
	 * @return string Modified sidebar option.
	 */
	public function buddyx_fluentcart_override_sidebar_option( $value ) {
		// Check if we're on a FluentCart single page.
		if ( is_singular( 'fluent-products' ) ) {
			// Get the customizer setting for product sidebar, default to 'none'.
			$product_sidebar = get_theme_mod( 'fluentcart_product_sidebar', 'none' );
			return $product_sidebar;
		}
		return $value;
	}

	/**
	 * Setup modifications for FluentCart archive pages.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_archive_page_setup() {
		// Early return if not a FluentCart archive page.
		if ( ! is_tax( 'product-categories' ) && ! is_tax( 'product-brands' ) && ! is_post_type_archive( 'fluent-products' ) ) {
			return;
		}
	}

	/**
	 * Render sub-header on FluentCart archive pages.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_render_archive_sub_header() {
		// Only render on FluentCart archive pages.
		if ( ! is_tax( 'product-categories' ) && ! is_tax( 'product-brands' ) && ! is_post_type_archive( 'fluent-products' ) ) {
			return;
		}

		// Render the theme's sub-header.
		do_action( 'buddyx_sub_header' );
	}

	/**
	 * Set default options on theme activation.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_set_theme_defaults() {
		// Check if defaults have been set.
		if ( get_option( 'buddyx_fluentcart_defaults_set', false ) ) {
			return;
		}

		// Set FluentCart pages to use full width template if they exist.
		$checkout_page_id = get_option( 'fluent_cart_checkout_page_id', 0 );
		if ( $checkout_page_id && get_post( $checkout_page_id ) ) {
			update_post_meta( $checkout_page_id, '_wp_page_template', 'page-templates/full-width.php' );
		}

		// Mark defaults as set.
		update_option( 'buddyx_fluentcart_defaults_set', true );
	}

	/**
	 * Add FluentCart cart option to customizer.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_add_customizer_option() {
		// Only add if Kirki is available.
		if ( ! class_exists( '\\Kirki\\Field\\Checkbox_Switch' ) ) {
			return;
		}

		new \Kirki\Field\Checkbox_Switch(
			array(
				'settings'    => 'site_header_enable_cart',
				'label'       => esc_html__( 'Enable Cart Icon?', 'buddyx' ),
				'section'     => 'site_header_primary_section',
				'default'     => '1',
				'priority'    => 10,
				'choices'     => array(
					'on'  => esc_html__( 'Yes', 'buddyx' ),
					'off' => esc_html__( 'No', 'buddyx' ),
				),
				'tooltip'     => esc_html__( 'Display FluentCart cart icon in header', 'buddyx' ),
				'transport'   => 'refresh',
			)
		);

		// Add product sidebar option.
		if ( class_exists( '\\Kirki\\Field\\Radio_Image' ) ) {
			new \Kirki\Field\Radio_Image(
				array(
					'settings'    => 'fluentcart_product_sidebar',
					'label'       => esc_html__( 'Single Product Sidebar', 'buddyx' ),
					'section'     => 'site_sidebar_layout',
					'default'     => 'none',
					'priority'    => 10,
					'choices'     => array(
						'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
						'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
						'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
						'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					),
					'tooltip'     => esc_html__( 'Choose sidebar layout for single product page.', 'buddyx' ),
					'transport'   => 'refresh',
				)
			);
		}
	}

	/**
	 * Setup cart display functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_setup_cart_display() {
		// Create compatibility functions.
		$this->buddyx_fluentcart_create_compatibility_functions();

		// Add cart styles.
		add_action( 'wp_head', array( $this, 'buddyx_fluentcart_add_cart_styles' ) );
		add_action( 'wp_footer', array( $this, 'buddyx_fluentcart_add_cart_scripts' ) );
	}

	/**
	 * Create compatibility functions for cart display.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function buddyx_fluentcart_create_compatibility_functions() {
		// Create dummy is_woocommerce function if it doesn't exist.
		if ( ! function_exists( 'is_woocommerce' ) ) {
			/**
			 * Dummy is_woocommerce function for compatibility.
			 *
			 * @since 1.0.0
			 * @return bool Always returns false.
			 */
			function is_woocommerce() {
				return false;
			}
		}

		// Override cart icon rendering function.
		if ( ! function_exists( 'buddyx_render_cart_icon' ) ) {
			/**
			 * Render the FluentCart cart icon in the header.
			 *
			 * @since 1.0.0
			 * @return void
			 */
			function buddyx_render_cart_icon() {
				// Check if cart is enabled.
				$cart_enabled = get_theme_mod( 'site_header_enable_cart', true );
				if ( ! $cart_enabled ) {
					return;
				}

				// Get cart count from FluentCart.
				$item_count = 0;

				if ( class_exists( '\\FluentCart\\Api\\Resource\\FrontendResource\\CartResource' ) ) {
					$cart_status = \FluentCart\Api\Resource\FrontendResource\CartResource::getStatus();
					if ( ! empty( $cart_status['cart_data'] ) ) {
						$item_count = count( $cart_status['cart_data'] );
					}
				}
				?>
				<div class="menu-icons-wrapper cart-widget-wrapper">
					<div class="cart">
						<a href="#" class="cart-icon-wrap fcart-cart-toogle-button" aria-label="<?php esc_attr_e( 'View Shopping Cart', 'buddyx' ); ?>">
							<span class="fa fa-shopping-cart"></span>
							<?php if ( $item_count > 0 ) : ?>
								<sup class="count fc-cart-count"><?php echo esc_html( $item_count ); ?></sup>
							<?php endif; ?>
						</a>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Add cart-related styles.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_add_cart_styles() {
		// Check if cart is enabled.
		$cart_enabled = get_theme_mod( 'site_header_enable_cart', true );
		if ( ! $cart_enabled ) {
			return;
		}
		?>
		<style id="buddyx-fluentcart-cart-styles">
			/* Hide FluentCart floating button on specific pages */
			body.fluent-cart-checkout .fc-cart-drawer-wrapper,
			body.fluent-cart-receipt .fc-cart-drawer-wrapper {
				display: none !important;
			}
		</style>
		<?php
	}

	/**
	 * Add cart-related scripts.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_fluentcart_add_cart_scripts() {
		// Check if cart is enabled.
		$cart_enabled = get_theme_mod( 'site_header_enable_cart', true );
		if ( ! $cart_enabled ) {
			return;
		}
		?>
		<script id="buddyx-fluentcart-cart-scripts">
			(function() {
				// Update cart count when cart changes
				document.addEventListener('fluent_cart_updated', function(e) {
					const cartCount = document.querySelector('.fc-cart-count');
					if (cartCount && e.detail && e.detail.cart_data) {
						const itemCount = e.detail.cart_data.length;
						if (itemCount > 0) {
							cartCount.textContent = itemCount;
							cartCount.style.display = 'inline-block';
						} else {
							cartCount.style.display = 'none';
						}
					}
				});

				// Handle cart icon click to toggle FluentCart drawer
				document.addEventListener('DOMContentLoaded', function() {
					const cartIcon = document.querySelector('.fcart-cart-toogle-button');
					if (cartIcon) {
						cartIcon.addEventListener('click', function(e) {
							e.preventDefault();
							// Trigger FluentCart drawer toggle
							const event = new CustomEvent('fluent_cart_toggle_drawer');
							document.dispatchEvent(event);
						});
					}
				});
			})();
		</script>
		<?php
	}

	/**
	 * Add body classes for FluentCart pages.
	 *
	 * @since 1.0.0
	 * @param array $classes Body classes.
	 * @return array Modified body classes.
	 */
	public function buddyx_fluentcart_body_classes( $classes ) {
		// Check if we're on FluentCart checkout page.
		$checkout_page_id = get_option( 'fluent_cart_checkout_page_id', 0 );
		$receipt_page_id = get_option( 'fluent_cart_receipt_page_id', 0 );

		if ( is_page( $checkout_page_id ) ) {
			$classes[] = 'fluent-cart-checkout';
		}

		if ( is_page( $receipt_page_id ) ) {
			$classes[] = 'fluent-cart-receipt';
		}

		if ( is_singular( 'fluent-products' ) ) {
			$classes[] = 'fluent-cart-product';
		}

		return $classes;
	}

	/**
	 * Disable FluentCart floating button on checkout/receipt pages.
	 *
	 * @since 1.0.0
	 * @param bool $enabled Whether floating button is enabled.
	 * @param array $args Additional arguments.
	 * @return bool
	 */
	public function buddyx_fluentcart_disable_floating_button( $enabled, $args ) {
		$post_id = get_queried_object_id();
		$content = get_post_field( 'post_content', $post_id );

		$shortcodes = [ 'fluent_cart_cart', 'fluent_cart_receipt' ];

		foreach ( $shortcodes as $shortcode ) {
			if ( has_shortcode( $content, $shortcode ) ) {
				return false;
			}
		}

		return $enabled;
	}

}

/**
 * Initialize the FluentCart support.
 *
 * @since 1.0.0
 * @return BuddyX_FluentCart_Support Main instance.
 */
function buddyx_fluentcart() {
	return BuddyX_FluentCart_Support::instance();
}

// Initialize on init.
add_action( 'init', 'buddyx_fluentcart', 0 );