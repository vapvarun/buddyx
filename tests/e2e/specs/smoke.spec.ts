import { test, expect } from '../fixtures';

test.describe( 'Smoke Tests', () => {
	test( 'homepage loads successfully', async ( { page } ) => {
		const response = await page.goto( '/' );
		expect( response?.status() ).toBeLessThan( 400 );
	} );

	test( 'body has buddyx class', async ( { page } ) => {
		await page.goto( '/' );
		const body = page.locator( 'body' );
		await expect( body ).toHaveClass( /buddyx/ );
	} );

	test( 'page has valid title', async ( { page } ) => {
		await page.goto( '/' );
		const title = await page.title();
		expect( title.length ).toBeGreaterThan( 0 );
	} );
} );
