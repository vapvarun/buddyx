import { test, expect } from '../fixtures';

/**
 * Tests BuddyX theme customizations on BuddyPress member profile pages.
 *
 * Selectors from actual BuddyX templates:
 * - buddypress/members/single/home.php (.site-wrapper.member-home, .bp-wrap)
 * - buddypress/members/single/cover-image-header.php (#cover-image-container, #item-header-avatar, .avatar-wrap)
 * - buddypress/members/single/member-header.php (.item-header-cover-image-wrapper)
 * - inc/compatibility/buddypress/buddypress-functions.php (body classes)
 *
 * All tests skip when no members exist (empty install).
 */

test.describe( 'BuddyPress Member Profiles', () => {
	let memberUrl: string | null = null;

	test.beforeEach( async ( { page } ) => {
		// Check if BuddyPress is active
		const response = await page.goto( '/members/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		// Wait for AJAX member list to populate
		const memberEntry = await page
			.waitForSelector(
				'.item-entry[data-bp-item-component="members"]',
				{ timeout: 5000 }
			)
			.catch( () => null );

		if ( ! memberEntry ) {
			// No members exist — skip all profile tests
			test.skip();
			return;
		}

		const link = page
			.locator(
				'.item-entry[data-bp-item-component="members"] .item-avatar a'
			)
			.first();
		memberUrl = await link.getAttribute( 'href' );
		if ( ! memberUrl ) {
			test.skip();
		}
	} );

	test( 'member profile uses BuddyX member-home wrapper', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );
		// Source: buddypress/members/single/home.php
		const wrapper = page.locator( '.site-wrapper.member-home' );
		await expect( wrapper ).toBeAttached();
	} );

	test( 'member profile has BuddyX cover image header', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );

		// Source: buddypress/members/single/cover-image-header.php or member-header.php
		const headerContainer = page.locator(
			'#item-header.users-header.single-headers'
		);
		await expect( headerContainer ).toBeAttached();

		// Both templates render .item-header-cover-image-wrapper
		const coverWrapper = page.locator(
			'.item-header-cover-image-wrapper'
		);
		await expect( coverWrapper ).toBeAttached();
	} );

	test( 'member profile has BuddyX avatar-wrap structure', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );

		// Source: cover-image-header.php wraps avatar in #item-header-avatar > .avatar-wrap
		const avatarArea = page.locator( '#item-header-avatar' );
		await expect( avatarArea ).toBeAttached();

		const avatarWrap = avatarArea.locator( '.avatar-wrap' );
		await expect( avatarWrap ).toBeAttached();
	} );

	test( 'member profile shows user nicename heading', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );

		// Source: cover-image-header.php renders h2.user-nicename
		const nicename = page.locator( 'h2.user-nicename' );
		await expect( nicename ).toBeAttached();

		const text = await nicename.textContent();
		expect( text?.trim().length ).toBeGreaterThan( 0 );
	} );

	test( 'member profile has #item-header-content area', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );

		// Source: cover-image-header.php renders #item-header-content
		const headerContent = page.locator( '#item-header-content' );
		await expect( headerContent ).toBeAttached();
	} );

	test( 'member profile has bp-wrap and item-body', async ( { page } ) => {
		await page.goto( memberUrl! );

		// Source: buddypress/members/single/home.php
		const bpWrap = page.locator( '.bp-wrap' );
		await expect( bpWrap ).toBeAttached();

		const itemBody = page.locator( '#item-body' );
		await expect( itemBody ).toBeAttached();
	} );

	test( 'member profile has correct BuddyX body classes', async ( {
		page,
	} ) => {
		await page.goto( memberUrl! );

		const bodyClass = await page
			.locator( 'body' )
			.getAttribute( 'class' );

		expect( bodyClass ).toContain( 'buddypress' );
		expect( bodyClass ).toContain( 'buddyx' );
		expect( bodyClass ).toContain( 'round-avatars' );
	} );
} );
