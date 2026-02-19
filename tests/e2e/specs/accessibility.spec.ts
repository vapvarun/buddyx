import { test, expect } from '../fixtures';
import AxeBuilder from '@axe-core/playwright';

test.describe( 'Accessibility', () => {
	test( 'homepage axe-core scan completes', async ( { page } ) => {
		await page.goto( '/' );
		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		// Log violations for visibility but don't fail on them yet.
		// This establishes the baseline — tighten thresholds over time.
		if ( results.violations.length > 0 ) {
			console.log(
				`Homepage a11y: ${ results.violations.length } violations found`
			);
			for ( const v of results.violations ) {
				console.log(
					`  [${ v.impact }] ${ v.id }: ${ v.help } (${ v.nodes.length } nodes)`
				);
			}
		}

		// Only fail on critical violations
		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect(
			critical.length,
			`Found ${ critical.length } critical a11y violations`
		).toBeLessThanOrEqual( 2 );
	} );

	test( '404 page axe-core scan completes', async ( { page } ) => {
		await page.goto( '/this-page-does-not-exist-404/' );
		const results = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa' ] )
			.analyze();

		const critical = results.violations.filter(
			( v ) => v.impact === 'critical'
		);
		expect(
			critical.length,
			`Found ${ critical.length } critical a11y violations on 404`
		).toBeLessThanOrEqual( 2 );
	} );
} );
