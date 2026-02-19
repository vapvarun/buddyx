import { test, expect } from '../fixtures';
import AxeBuilder from '@axe-core/playwright';

/**
 * Accessibility tests for BuddyX WooCommerce pages.
 *
 * Runs axe-core WCAG 2.0 AA scans on WooCommerce pages.
 * Relaxed thresholds — logs all violations, only fails on excessive critical count.
 *
 * Tests the BuddyX theme markup from:
 * - woocommerce/archive-product.php
 * - woocommerce/single-product.php
 * - woocommerce/cart/cart.php
 *
 * Auto-skips when WooCommerce is not active.
 */

function logViolations( label: string, violations: any[] ) {
	if ( violations.length > 0 ) {
		console.log( `${ label }: ${ violations.length } violations` );
		for ( const v of violations ) {
			console.log(
				`  [${ v.impact }] ${ v.id }: ${ v.help } (${ v.nodes.length } nodes)`
			);
		}
	}
}

test.describe( 'WooCommerce Accessibility', () => {
	test.beforeEach( async ( { page } ) => {
		const response = await page.goto( '/shop/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
		}
	} );

	test( 'shop page a11y scan', async ( { page } ) => {
		await page.goto( '/shop/' );

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Shop page', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );

	test( 'single product a11y scan', async ( { page } ) => {
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

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Single product', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );

	test( 'cart page a11y scan', async ( { page } ) => {
		const response = await page.goto( '/cart/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Cart page', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );
} );
