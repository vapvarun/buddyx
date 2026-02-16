import { test, expect } from '../fixtures';

/**
 * Tests BuddyX theme customizations on BuddyPress single group pages.
 *
 * Selectors from actual BuddyX templates:
 * - buddypress/groups/single/home.php (.site-wrapper.group-home, .bp-wrap)
 * - buddypress/groups/single/cover-image-header.php (#cover-image-container, .bp-group-title)
 * - buddypress/groups/single/group-header.php (.highlight.group-status)
 * - buddypress/groups/single/members-loop.php (#members-list)
 *
 * All tests skip when no groups exist (empty install).
 */

test.describe( 'BuddyPress Single Group', () => {
	let groupUrl: string | null = null;

	test.beforeEach( async ( { page } ) => {
		const response = await page.goto( '/groups/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		const groupEntry = await page
			.waitForSelector(
				'.item-entry[data-bp-item-component="groups"]',
				{ timeout: 5000 }
			)
			.catch( () => null );

		if ( ! groupEntry ) {
			test.skip();
			return;
		}

		const link = page
			.locator(
				'.item-entry[data-bp-item-component="groups"] .item-avatar a'
			)
			.first();
		groupUrl = await link.getAttribute( 'href' );
		if ( ! groupUrl ) {
			test.skip();
		}
	} );

	test( 'single group uses BuddyX group-home wrapper', async ( {
		page,
	} ) => {
		await page.goto( groupUrl! );
		// Source: buddypress/groups/single/home.php
		const wrapper = page.locator( '.site-wrapper.group-home' );
		await expect( wrapper ).toBeAttached();
	} );

	test( 'single group has BuddyX group header structure', async ( {
		page,
	} ) => {
		await page.goto( groupUrl! );

		// Source: buddypress/groups/single/cover-image-header.php or group-header.php
		const header = page.locator(
			'#item-header.groups-header.single-headers'
		);
		await expect( header ).toBeAttached();

		const coverWrapper = page.locator(
			'.item-header-cover-image-wrapper'
		);
		await expect( coverWrapper ).toBeAttached();
	} );

	test( 'single group shows bp-group-title', async ( { page } ) => {
		await page.goto( groupUrl! );

		// Source: cover-image-header.php and group-header.php
		const title = page.locator( 'h2.bp-group-title' );
		await expect( title ).toBeAttached();

		const text = await title.textContent();
		expect( text?.trim().length ).toBeGreaterThan( 0 );
	} );

	test( 'single group shows status badge', async ( { page } ) => {
		await page.goto( groupUrl! );

		// Source: group-header.php renders p.highlight.group-status
		const statusBadge = page.locator( '.highlight.group-status' );
		await expect( statusBadge ).toBeAttached();
	} );

	test( 'single group has bp-wrap and item-body', async ( { page } ) => {
		await page.goto( groupUrl! );

		// Source: buddypress/groups/single/home.php
		const bpWrap = page.locator( '.bp-wrap' );
		await expect( bpWrap ).toBeAttached();

		const itemBody = page.locator( '#item-body' );
		await expect( itemBody ).toBeAttached();
	} );
} );
