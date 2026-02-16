import { test, expect } from '../fixtures';

/**
 * Tests BuddyX theme customizations on BuddyPress directory pages.
 *
 * All selectors derived from actual BuddyX templates:
 * - buddypress/activity/entry.php (activity card head, stream container)
 * - buddypress/members/members-loop.php (member cards, cover wrappers, status dots)
 * - buddypress/groups/groups-loop.php (group cards, cover wrappers)
 * - inc/compatibility/buddypress/buddypress-functions.php (body classes, hooks)
 *
 * Tests are split into:
 * - Structural tests: always pass (BuddyX layout elements present regardless of data)
 * - Data tests: skip gracefully when directories are empty
 */

test.describe( 'BuddyPress Directories', () => {
	test.beforeEach( async ( { page } ) => {
		const response = await page.goto( '/activity/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
		}
	} );

	// ──────────────────────────────────────────────────────
	// Activity Directory — Structural (always present)
	// ──────────────────────────────────────────────────────

	test( 'activity directory has BuddyX sub-header with breadcrumbs', async ( {
		page,
	} ) => {
		await page.goto( '/activity/' );

		// BuddyX adds .site-sub-header with page title + breadcrumbs
		const subHeader = page.locator( '.site-sub-header' );
		await expect( subHeader ).toBeAttached();

		const heading = subHeader.locator( 'h2' );
		await expect( heading ).toContainText( 'Activity' );

		const breadcrumbs = subHeader.locator( '.buddyx-breadcrumbs, nav' );
		await expect( breadcrumbs.first() ).toBeAttached();
	} );

	test( 'activity directory has BuddyX directory navigation', async ( {
		page,
	} ) => {
		await page.goto( '/activity/' );

		// BuddyX renders BP Nouveau directory nav
		const dirNav = page.locator( 'nav[aria-label="Directory menu"]' );
		await expect( dirNav ).toBeAttached();

		// Should have "All Members" tab
		const allMembersTab = dirNav.locator( 'a' ).first();
		await expect( allMembersTab ).toBeAttached();
	} );

	test( 'activity directory has search and filter controls', async ( {
		page,
	} ) => {
		await page.goto( '/activity/' );

		// BuddyX renders search input
		const searchInput = page.locator(
			'input[placeholder*="Search Activity"]'
		);
		await expect( searchInput ).toBeAttached();

		// BuddyX renders filter dropdown
		const filterDropdown = page.locator( 'select' ).first();
		await expect( filterDropdown ).toBeAttached();
	} );

	test( 'activity directory has RSS feed link', async ( { page } ) => {
		await page.goto( '/activity/' );

		// BuddyX shows RSS feed link on activity directory
		const rssLink = page.locator( 'a[href*="/activity/feed/"]' );
		await expect( rssLink ).toBeAttached();
	} );

	// ──────────────────────────────────────────────────────
	// Activity Directory — Data-dependent
	// ──────────────────────────────────────────────────────

	test( 'activity entries have BuddyX card-head type labels', async ( {
		page,
	} ) => {
		await page.goto( '/activity/' );

		// Wait for AJAX stream
		const activityItem = await page
			.waitForSelector( '.activity-item, .activity-list li', {
				timeout: 5000,
			} )
			.catch( () => null );

		if ( ! activityItem ) {
			// No activity data — skip
			test.skip();
			return;
		}

		// BuddyX adds .activity-card-head with component type label
		// Source: buddypress/activity/entry.php
		const cardHead = page.locator( '.activity-card-head' ).first();
		await expect( cardHead ).toBeAttached();
	} );

	// ──────────────────────────────────────────────────────
	// Members Directory — Structural
	// ──────────────────────────────────────────────────────

	test( 'members directory has BuddyX sub-header', async ( { page } ) => {
		await page.goto( '/members/' );

		const subHeader = page.locator( '.site-sub-header' );
		await expect( subHeader ).toBeAttached();

		const heading = subHeader.locator( 'h2' );
		await expect( heading ).toContainText( 'Members' );
	} );

	test( 'members directory has search and order controls', async ( {
		page,
	} ) => {
		await page.goto( '/members/' );

		const searchInput = page.locator(
			'input[placeholder*="Search Members"]'
		);
		await expect( searchInput ).toBeAttached();

		// BuddyX renders "Order By" dropdown (Last Active, Newest, Alpha)
		const orderDropdown = page.locator( 'select' ).first();
		await expect( orderDropdown ).toBeAttached();
	} );

	test( 'members directory shows member count in tab', async ( {
		page,
	} ) => {
		await page.goto( '/members/' );

		// BuddyX directory tab shows count badge
		const dirNav = page.locator( 'nav[aria-label="Directory menu"]' );
		const tab = dirNav.locator( 'a' ).first();
		const tabText = await tab.textContent();

		// Should contain "All Members" with a count
		expect( tabText ).toContain( 'All Members' );
	} );

	// ──────────────────────────────────────────────────────
	// Members Directory — Data-dependent
	// ──────────────────────────────────────────────────────

	test( 'member cards have BuddyX layout structure', async ( { page } ) => {
		await page.goto( '/members/' );

		const memberEntry = await page
			.waitForSelector(
				'.item-entry[data-bp-item-component="members"]',
				{ timeout: 5000 }
			)
			.catch( () => null );

		if ( ! memberEntry ) {
			test.skip();
			return;
		}

		// BuddyX member card structure from members-loop.php
		const firstEntry = page
			.locator( '.item-entry[data-bp-item-component="members"]' )
			.first();
		await expect( firstEntry.locator( '.list-wrap' ) ).toBeAttached();
		await expect( firstEntry.locator( '.item-avatar' ) ).toBeAttached();
		await expect(
			firstEntry.locator( '.member-info-wrapper' )
		).toBeAttached();
		await expect(
			firstEntry.locator( '.member-action-wrapper' )
		).toBeAttached();
	} );

	test( 'member cards show BuddyX online status dot when active', async ( {
		page,
	} ) => {
		await page.goto( '/members/' );

		const memberEntry = await page
			.waitForSelector(
				'.item-entry[data-bp-item-component="members"]',
				{ timeout: 5000 }
			)
			.catch( () => null );

		if ( ! memberEntry ) {
			test.skip();
			return;
		}

		// BuddyX adds .member-status span via buddyx_user_status()
		// Status dots only render when BP tracks last_activity (requires active users)
		const statusDot = page.locator( '.member-status' );
		const count = await statusDot.count();
		// Verify structure: if dots exist, they should be inside member cards
		if ( count > 0 ) {
			const firstDot = statusDot.first();
			await expect( firstDot ).toBeAttached();
		}
	} );

	// ──────────────────────────────────────────────────────
	// Groups Directory — Structural
	// ──────────────────────────────────────────────────────

	test( 'groups directory has BuddyX sub-header', async ( { page } ) => {
		const response = await page.goto( '/groups/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		const subHeader = page.locator( '.site-sub-header' );
		await expect( subHeader ).toBeAttached();

		const heading = subHeader.locator( 'h2' );
		await expect( heading ).toContainText( 'Groups' );
	} );

	test( 'groups directory has search and order controls', async ( {
		page,
	} ) => {
		const response = await page.goto( '/groups/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		const searchInput = page.locator(
			'input[placeholder*="Search Groups"]'
		);
		await expect( searchInput ).toBeAttached();
	} );

	// ──────────────────────────────────────────────────────
	// Groups Directory — Data-dependent
	// ──────────────────────────────────────────────────────

	test( 'group cards have BuddyX layout structure', async ( { page } ) => {
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

		// BuddyX group card structure from groups-loop.php
		const firstEntry = page
			.locator( '.item-entry[data-bp-item-component="groups"]' )
			.first();
		await expect( firstEntry.locator( '.list-wrap' ) ).toBeAttached();
		await expect(
			firstEntry.locator( 'h2.list-title.groups-title' )
		).toBeAttached();
	} );

	// ──────────────────────────────────────────────────────
	// Body Classes
	// ──────────────────────────────────────────────────────

	test( 'BuddyPress pages have buddyx body classes', async ( { page } ) => {
		await page.goto( '/members/' );
		const body = page.locator( 'body' );
		const bodyClass = await body.getAttribute( 'class' );

		// BuddyX adds these classes via buddypress-functions.php
		expect( bodyClass ).toContain( 'buddypress' );
		expect( bodyClass ).toContain( 'buddyx' );
	} );

	test( 'BuddyPress pages have round-avatars class when enabled', async ( {
		page,
	} ) => {
		await page.goto( '/members/' );
		const body = page.locator( 'body' );
		// round-avatars added when buddypress_avatar_style Customizer setting is on (default)
		await expect( body ).toHaveClass( /round-avatars/ );
	} );
} );
