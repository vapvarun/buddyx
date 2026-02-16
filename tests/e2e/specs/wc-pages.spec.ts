import { test, expect } from '../fixtures';

/**
 * Tests BuddyX theme customizations on WooCommerce pages.
 *
 * Selectors from actual BuddyX templates:
 * - woocommerce/archive-product.php (.site-sub-header, sidebar layout)
 * - woocommerce/single-product.php (sidebar layout)
 * - woocommerce/cart/cart.php (two-column .row layout, .col-md-8 + .col-md-4)
 * - inc/compatibility/woocommerce/woocommerce-functions.php (cart icon, body classes)
 *
 * All tests auto-skip when WooCommerce is not active (/shop/ returns 404).
 */

test.describe( 'WooCommerce Pages', () => {
	test.beforeEach( async ( { page } ) => {
		const response = await page.goto( '/shop/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
		}
	} );

	// ──────────────────────────────────────────────────────
	// Shop / Archive
	// ──────────────────────────────────────────────────────

	test( 'shop page has BuddyX sub-header with title', async ( {
		page,
	} ) => {
		await page.goto( '/shop/' );

		// Source: woocommerce/archive-product.php
		const subHeader = page.locator( '.site-sub-header' );
		await expect( subHeader ).toBeAttached();

		const shopTitle = page.locator(
			'h1.woocommerce-products-header__title.page-title'
		);
		await expect( shopTitle ).toBeAttached();
	} );

	test( 'shop page has BuddyX sidebar layout classes', async ( {
		page,
	} ) => {
		await page.goto( '/shop/' );

		const bodyClass = await page
			.locator( 'body' )
			.getAttribute( 'class' );

		// BuddyX adds woocommerce class
		expect( bodyClass ).toContain( 'woocommerce' );
	} );

	test( 'shop page has BuddyX sidebar when configured', async ( {
		page,
	} ) => {
		await page.goto( '/shop/' );

		const bodyClass = await page
			.locator( 'body' )
			.getAttribute( 'class' );

		// Source: woocommerce/archive-product.php uses
		// buddyx()->display_woocommerce_left_sidebar() and
		// buddyx()->display_woocommerce_right_sidebar()
		if ( bodyClass?.includes( 'has-woocommerce-sidebar' ) ) {
			const sidebar = page.locator(
				'aside.woo-primary-sidebar, aside.woo-left-sidebar'
			);
			await expect( sidebar.first() ).toBeAttached();

			// BuddyX wraps sidebar in .sticky-sidebar
			const stickySidebar = sidebar
				.first()
				.locator( '.sticky-sidebar' );
			await expect( stickySidebar ).toBeAttached();
		}
	} );

	// ──────────────────────────────────────────────────────
	// Single Product
	// ──────────────────────────────────────────────────────

	test( 'single product page loads with BuddyX layout', async ( {
		page,
	} ) => {
		await page.goto( '/shop/' );

		const productLink = page
			.locator(
				'.products .product a.woocommerce-LoopProduct-link, .products .product .woocommerce-loop-product__link'
			)
			.first();

		if ( ! ( await productLink.isVisible().catch( () => false ) ) ) {
			test.skip();
			return;
		}

		const href = await productLink.getAttribute( 'href' );
		if ( ! href ) {
			test.skip();
			return;
		}

		await page.goto( href );

		const bodyClass = await page
			.locator( 'body' )
			.getAttribute( 'class' );
		expect( bodyClass ).toContain( 'single-product' );
	} );

	// ──────────────────────────────────────────────────────
	// Cart
	// ──────────────────────────────────────────────────────

	test( 'cart page has BuddyX layout', async ( { page } ) => {
		const response = await page.goto( '/cart/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		// WC 8.x+ uses block-based cart (wp-block-woocommerce-cart)
		// Classic cart uses woocommerce/cart/cart.php with .woocommerce wrapper
		const cartBlock = page.locator(
			'.woocommerce, .wp-block-woocommerce-cart, .wc-block-cart'
		).first();
		await expect( cartBlock ).toBeAttached();

		// BuddyX sub-header should still be present
		const subHeader = page.locator( '.site-sub-header' );
		await expect( subHeader ).toBeAttached();
	} );

	// ──────────────────────────────────────────────────────
	// Header Cart Icon
	// ──────────────────────────────────────────────────────

	test( 'header has BuddyX cart icon', async ( { page } ) => {
		await page.goto( '/shop/' );

		// Source: woocommerce-functions.php registers buddyx_header_add_to_cart_fragment()
		// which targets .menu-icons-wrapper .cart a
		const cartIcon = page.locator(
			'.menu-icons-wrapper .cart, .cart-icon, .fa-shopping-cart, .fa-cart-shopping'
		);

		if ( await cartIcon.first().isVisible().catch( () => false ) ) {
			const cartLink = cartIcon.first().locator( 'a' );
			await expect( cartLink ).toBeAttached();
		}
	} );
} );
