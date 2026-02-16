import { defineConfig, devices } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const configPath = path.resolve( process.cwd(), 'config/config.json' );
const config = JSON.parse( fs.readFileSync( configPath, 'utf-8' ) );

const proxyURL = config.dev.browserSync.proxyURL || 'buddyx.local';
const protocol = config.dev.browserSync.https ? 'https' : 'http';
const wpBaseUrl = process.env.WP_BASE_URL || `${ protocol }://${ proxyURL }`;

process.env.WP_ADMIN_USER =
	process.env.WP_ADMIN_USER || config.dev.admin?.user || 'admin';
process.env.WP_ADMIN_PASSWORD =
	process.env.WP_ADMIN_PASSWORD || config.dev.admin?.password || 'password';
process.env.WP_USERNAME = process.env.WP_USERNAME || process.env.WP_ADMIN_USER;
process.env.WP_PASSWORD =
	process.env.WP_PASSWORD || process.env.WP_ADMIN_PASSWORD;

export default defineConfig( {
	testDir: './tests/e2e/specs',
	fullyParallel: true,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 2 : 0,
	workers: process.env.CI ? 1 : undefined,
	reporter: 'html',
	use: {
		baseURL: wpBaseUrl,
		trace: 'on-first-retry',
		screenshot: 'only-on-failure',
		ignoreHTTPSErrors: true,
	},

	projects: [
		{
			name: 'chromium',
			use: { ...devices[ 'Desktop Chrome' ] },
		},

		{
			name: 'firefox',
			use: { ...devices[ 'Desktop Firefox' ] },
		},

		{
			name: 'webkit',
			use: { ...devices[ 'Desktop Safari' ] },
		},
	],
} );
