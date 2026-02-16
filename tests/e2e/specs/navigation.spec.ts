import { test, expect } from '../fixtures';

test.describe( 'Navigation', () => {
	test( 'mobile menu toggle is visible on small viewport', async ( {
		page,
	} ) => {
		await page.setViewportSize( { width: 375, height: 812 } );
		await page.goto( '/' );

		const menuToggle = page.locator( '.menu-toggle' );
		const isVisible = await menuToggle.isVisible();

		// Menu toggle should be present on mobile viewport
		expect( isVisible ).toBe( true );
	} );

	test( 'site has a main navigation', async ( { page } ) => {
		await page.goto( '/' );
		const nav = page.locator( 'nav, [role="navigation"]' );
		await expect( nav.first() ).toBeAttached();
	} );
} );
