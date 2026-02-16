import { test, expect } from '../fixtures';
import AxeBuilder from '@axe-core/playwright';

/**
 * Accessibility tests for BuddyX BuddyPress pages.
 *
 * Runs axe-core WCAG 2.0 AA scans on BuddyPress directories and profiles.
 * Relaxed thresholds — logs all violations, only fails on excessive critical count.
 *
 * These test the BuddyX theme markup from:
 * - buddypress/members/members-loop.php
 * - buddypress/groups/groups-loop.php
 * - buddypress/members/single/cover-image-header.php
 * - inc/compatibility/buddypress/buddypress-functions.php
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

test.describe( 'BuddyPress Accessibility', () => {
	test.beforeEach( async ( { page } ) => {
		const response = await page.goto( '/activity/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
		}
	} );

	test( 'activity directory a11y scan', async ( { page } ) => {
		await page.goto( '/activity/' );

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Activity directory', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );

	test( 'members directory a11y scan', async ( { page } ) => {
		await page.goto( '/members/' );

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Members directory', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );

	test( 'groups directory a11y scan', async ( { page } ) => {
		const response = await page.goto( '/groups/' );
		if ( ! response || response.status() >= 400 ) {
			test.skip();
			return;
		}

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Groups directory', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );

	test( 'member profile a11y scan', async ( { page } ) => {
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

		const link = page
			.locator(
				'.item-entry[data-bp-item-component="members"] .item-avatar a'
			)
			.first();
		const href = await link.getAttribute( 'href' );
		if ( ! href ) {
			test.skip();
			return;
		}

		await page.goto( href );

		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		logViolations( 'Member profile', results.violations );

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect( critical.length ).toBeLessThanOrEqual( 3 );
	} );
} );
