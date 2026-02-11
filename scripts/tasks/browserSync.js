/* eslint-env es6 */
'use strict';

/**
 * External dependencies
 */
import browserSync from 'browser-sync';
import log from 'fancy-log';
import colors from 'ansi-colors';
import fs from 'fs';
import { execFileSync } from 'child_process';
import path from 'path';

/**
 * Internal dependencies
 */
import { paths, rootPath } from '../lib/constants.js';
import { getThemeConfig } from '../lib/utils.js';

/**
 * Auto-detect WordPress site URL using WP-CLI.
 * Falls back to config value if WP-CLI is not available.
 *
 * @param {string} configuredUrl - The URL from config file
 * @return {string} The detected or configured proxy URL
 */
function getProxyUrl( configuredUrl ) {
	// If a specific URL is configured (not the default), use it
	if ( configuredUrl && configuredUrl !== 'buddyx.local/' ) {
		return configuredUrl;
	}

	try {
		// Navigate to WordPress root (theme is in wp-content/themes/theme-name)
		const wpRoot = path.resolve( rootPath, '..', '..', '..' );

		// Try to get site URL using WP-CLI (execFileSync avoids shell injection)
		const siteUrl = execFileSync( 'wp', [ 'option', 'get', 'siteurl' ], {
			cwd: wpRoot,
			encoding: 'utf8',
			stdio: [ 'pipe', 'pipe', 'pipe' ],
		} ).trim();

		if ( siteUrl ) {
			// Remove protocol and trailing slash for Browsersync proxy
			const proxyUrl = siteUrl
				.replace( /^https?:\/\//, '' )
				.replace( /\/$/, '' );

			log(
				colors.green(
					`Auto-detected WordPress URL: ${ colors.bold( proxyUrl ) }`
				)
			);
			return proxyUrl;
		}
	} catch ( error ) {
		// WP-CLI not available or failed, fall back to config
		log(
			colors.yellow(
				`Could not auto-detect WordPress URL (WP-CLI not available). Using config value: ${ colors.bold( configuredUrl ) }`
			)
		);
	}

	return configuredUrl;
}

/**
 * Conditionally set up BrowserSync.
 * Only run BrowserSync if config.browserSync.live = true.
 */

// Create a BrowserSync instance:
export const server = browserSync.create();

// Initialize the BrowserSync server conditionally:
export function serve( done ) {
	const config = getThemeConfig();

	if ( ! config.dev.browserSync.live ) {
		done();
		return;
	}

	// Auto-detect proxy URL or use configured value
	const proxyUrl = getProxyUrl( config.dev.browserSync.proxyURL );

	const serverConfig = {
		proxy: proxyUrl,
		port: config.dev.browserSync.bypassPort,
		liveReload: true,
		https: false,
	};

	// Only setup HTTPS certificates if HTTPS is enabled
	if ( config.dev.browserSync.https ) {
		// Use a custom path key/cert if defined, otherwise use the default path
		const certPath = Object.prototype.hasOwnProperty.call(
			config.dev.browserSync,
			'certPath'
		)
			? config.dev.browserSync.certPath
			: paths.browserSync.cert;
		const keyPath = Object.prototype.hasOwnProperty.call(
			config.dev.browserSync,
			'keyPath'
		)
			? config.dev.browserSync.keyPath
			: paths.browserSync.key;

		// Ensure the key/cert files exist
		const certFound = fs.existsSync( certPath );
		const keyFound = fs.existsSync( keyPath );

		// Let the user know if we found a cert
		if ( certFound ) {
			log(
				colors.yellow(
					`Using the SSL certificate ${ colors.bold( certPath ) }`
				)
			);
		} else {
			log(
				colors.yellow(
					`No SSL certificate found, HTTPS will ${ colors.bold(
						'not'
					) } be enabled`
				)
			);
		}

		// Let the user know if we found a key
		if ( keyFound ) {
			log(
				colors.yellow( `Using the SSL key ${ colors.bold( keyPath ) }` )
			);
		} else {
			log(
				colors.yellow(
					`No SSL key found, HTTPS will ${ colors.bold(
						'not'
					) } be enabled`
				)
			);
		}

		// Only enable HTTPS if there is a cert and a key
		if ( certFound && keyFound ) {
			log( colors.yellow( `HTTPS is ${ colors.bold( 'on' ) }` ) );
			serverConfig.https = {
				key: keyPath,
				cert: certPath,
			};
		}
	}

	// Start the BrowserSync server
	server.init( serverConfig );

	done();
}

// Reload the live site:
export function reload( done ) {
	const config = getThemeConfig();

	if ( config.dev.browserSync.live ) {
		if ( server.paused ) {
			server.resume();
		}
		server.reload();
	} else {
		server.pause();
	}

	done();
}
