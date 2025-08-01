<?php
/**
 * SureCart Support for BuddyX Theme
 *
 * This file contains all the functions and hooks needed to integrate
 * SureCart e-commerce functionality with the BuddyX theme.
 *
 * @package    BuddyX
 * @subpackage SureCart
 * @since      1.0.0
 * @version    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if SureCart is active.
if ( ! defined( 'SURECART_PLUGIN_FILE' ) ) {
	return;
}

/**
 * BuddyX SureCart Support Class
 *
 * Handles all SureCart integration functionality including cart display,
 * page templates, and customizer options.
 *
 * @since 1.0.0
 */
class BuddyX_SureCart_Support {

	/**
	 * The single instance of the class.
	 *
	 * @var BuddyX_SureCart_Support|null
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
	 * @return BuddyX_SureCart_Support Main instance.
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
		add_action( 'wp', array( $this, 'buddyx_surecart_single_page_setup' ) );
		
		// Override sidebar settings for SureCart post types.
		add_filter( 'theme_mod_sidebar_option', array( $this, 'buddyx_surecart_override_sidebar_option' ) );
		add_filter( 'theme_mod_single_post_sidebar_option', array( $this, 'buddyx_surecart_override_sidebar_option' ) );
		
		// Page template assignment hooks - only fire when options change.
		add_action( 'update_option_surecart_shop_page_id', array( $this, 'buddyx_surecart_assign_page_template' ), 10, 2 );
		add_action( 'update_option_surecart_checkout_page_id', array( $this, 'buddyx_surecart_assign_page_template' ), 10, 2 );
		add_action( 'update_option_surecart_cart_page_id', array( $this, 'buddyx_surecart_assign_page_template' ), 10, 2 );
		add_action( 'update_option_surecart_dashboard_page_id', array( $this, 'buddyx_surecart_assign_page_template' ), 10, 2 );
		
		// Theme activation - run once.
		add_action( 'after_switch_theme', array( $this, 'buddyx_surecart_set_theme_defaults' ) );
		
		// Customizer and cart setup - only if needed.
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'init', array( $this, 'buddyx_surecart_add_customizer_option' ), 20 );
			add_action( 'init', array( $this, 'buddyx_surecart_setup_cart_display' ), 5 );
		}
	}

	/**
	 * Setup modifications for SureCart single product pages.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_surecart_single_page_setup() {
		// Early return if not a SureCart single page.
		if ( ! is_singular( array( 'sc_product', 'sc_collection', 'sc_upsell' ) ) ) {
			return;
		}

		// Add inline CSS to hide duplicate elements.
		add_action( 'wp_head', array( $this, 'buddyx_surecart_add_single_page_styles' ), 999 );
		
		// Remove all actions from buddyx_sub_header hook.
		$this->buddyx_surecart_remove_sub_header_actions();
		
		// Prevent entry header from being loaded.
		add_filter( 'get_post_type', array( $this, 'buddyx_surecart_prevent_entry_header' ), 10, 2 );
	}

	/**
	 * Remove sub-header actions for SureCart pages.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function buddyx_surecart_remove_sub_header_actions() {
		// Remove all actions hooked to buddyx_sub_header.
		remove_all_actions( 'buddyx_sub_header' );
	}

	/**
	 * Prevent entry header from showing on SureCart products.
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
	public function buddyx_surecart_prevent_entry_header( $post_type, $post_id = null ) {
		// Only modify when checking for entry header template part.
		if ( doing_action( 'get_template_part_template-parts/content/entry-header' ) ) {
			return 'sc_product'; // Return actual type to prevent 'post' match.
		}
		return $post_type;
	}

	/**
	 * Override sidebar option for SureCart post types.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $value Current sidebar option value.
	 * @return string Modified sidebar option.
	 */
	public function buddyx_surecart_override_sidebar_option( $value ) {
		// Check if we're on a SureCart single page.
		if ( is_singular( array( 'sc_product', 'sc_collection', 'sc_upsell' ) ) ) {
			return 'none'; // This will trigger the no-sidebar layout.
		}
		return $value;
	}

	/**
	 * Add CSS to hide duplicate elements on single pages.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_surecart_add_single_page_styles() {
		?>
		<style id="buddyx-surecart-single-styles">
			/* Hide duplicate title and featured image */
			.single-sc_product .entry-header .entry-title,
			.single-sc_collection .entry-header .entry-title,
			.single-sc_upsell .entry-header .entry-title,
			.single-sc_product .entry-header-post,
			.single-sc_collection .entry-header-post,
			.single-sc_upsell .entry-header-post,
			.single-sc_product .post-thumbnail,
			.single-sc_collection .post-thumbnail,
			.single-sc_upsell .post-thumbnail,
			.single-sc_product .featured-wrap,
			.single-sc_collection .featured-wrap,
			.single-sc_upsell .featured-wrap {
				display: none !important;
			}
			
			/* Hide sub-header/breadcrumb section */
			.single-sc_product .buddyx-breadcrumbs,
			.single-sc_collection .buddyx-breadcrumbs,
			.single-sc_upsell .buddyx-breadcrumbs,
			.single-sc_product .buddyx-breadcrumbs-wrapper,
			.single-sc_collection .buddyx-breadcrumbs-wrapper,
			.single-sc_upsell .buddyx-breadcrumbs-wrapper {
				display: none !important;
			}
			
			/* Ensure proper spacing and full width */
			.single-sc_product .entry-content,
			.single-sc_collection .entry-content,
			.single-sc_upsell .entry-content {
				margin-top: 0;
			}
			
			.single-sc_product .site-main,
			.single-sc_collection .site-main,
			.single-sc_upsell .site-main {
				width: 100%;
				max-width: 100%;
			}
		</style>
		<?php
	}

	/**
	 * Assign page template when SureCart creates a page.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param mixed $old_value The old option value.
	 * @param mixed $new_value The new option value.
	 * @return void
	 */
	public function buddyx_surecart_assign_page_template( $old_value, $new_value ) {
		// Validate and sanitize the new value.
		$new_value = absint( $new_value );
		
		// Only process if we have a valid new page ID.
		if ( ! $new_value || $new_value === $old_value ) {
			return;
		}
		
		// Verify the page exists.
		if ( ! get_post( $new_value ) ) {
			return;
		}
		
		// Set full width template.
		update_post_meta( $new_value, '_wp_page_template', 'page-templates/full-width.php' );
	}

	/**
	 * Set default options on theme activation.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_surecart_set_theme_defaults() {
		// Check if defaults have been set.
		if ( get_option( 'buddyx_surecart_defaults_set', false ) ) {
			return;
		}
		
		// Get SureCart page IDs.
		$page_ids = array(
			absint( get_option( 'surecart_shop_page_id', 0 ) ),
			absint( get_option( 'surecart_checkout_page_id', 0 ) ),
			absint( get_option( 'surecart_cart_page_id', 0 ) ),
			absint( get_option( 'surecart_dashboard_page_id', 0 ) ),
		);
		
		// Set template for each existing page.
		foreach ( array_filter( $page_ids ) as $page_id ) {
			if ( get_post( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', 'page-templates/full-width.php' );
			}
		}
		
		// Mark defaults as set.
		update_option( 'buddyx_surecart_defaults_set', true );
	}

	/**
	 * Add SureCart cart option to customizer.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_surecart_add_customizer_option() {
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
				'tooltip'     => esc_html__( 'Display SureCart cart icon in header', 'buddyx' ),
				'transport'   => 'refresh',
			)
		);
	}

	/**
	 * Setup cart display functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function buddyx_surecart_setup_cart_display() {
		// Create compatibility functions.
		$this->buddyx_surecart_create_compatibility_functions();

		// Add cart styles.
		add_action( 'wp_footer', array( $this, 'buddyx_surecart_add_cart_styles' ) );
	}

	/**
	 * Create compatibility functions for cart display.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	private function buddyx_surecart_create_compatibility_functions() {
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
			 * Render the SureCart cart icon in the header.
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
				?>
				<div class="menu-icons-wrapper cart-widget-wrapper">
					<div class="cart">
						<a href="#" class="cart-icon-wrap" aria-label="<?php esc_attr_e( 'View Shopping Cart', 'buddyx' ); ?>">
							<?php echo do_blocks( '<!-- wp:surecart/cart-menu-icon {"cart_icon":"shopping-cart","cart_menu_always_shown":true} /-->' ); ?>
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
	public function buddyx_surecart_add_cart_styles() {
		// Check if cart is enabled.
		$cart_enabled = get_theme_mod( 'site_header_enable_cart', true );
		if ( ! $cart_enabled ) {
			return;
		}
	}
}

/**
 * Initialize the SureCart support.
 *
 * @since 1.0.0
 * @return BuddyX_SureCart_Support Main instance.
 */
function buddyx_surecart() {
	return BuddyX_SureCart_Support::instance();
}

// Initialize on init.
add_action( 'init', 'buddyx_surecart', 0 );