/* eslint-env es6 */
'use strict';

/**
 * External dependencies
 */
import path from 'path';
import * as process from 'node:process';

/**
 * Internal dependencies
 */
import { configValueDefined } from './utils.js';

import config from '../../config/themeConfig.js';

// Root path is where npm run commands happen
export const rootPath = process.cwd();
export const testPath = `${ rootPath }/scripts/tests`;

// Dev or production
export const isProd = process.env.NODE_ENV === 'production';

// Directory for assets (CSS, JS, images)
export const assetsDir = `${ rootPath }/assets`;

// PHPCS options
export const PHPCSOptions = {
	bin: `${ rootPath }/vendor/bin/phpcs`,
	showSniffCode: true,
	report: 'full',
	reporter: 'log',
};

// Theme config name fields and their defaults
export const nameFieldDefaults = {
	PHPNamespace: 'BuddyX\\Buddyx',
	slug: 'buddyx',
	name: 'BuddyX',
	theme_uri: 'https://wbcomdesigns.com/downloads/buddyx-theme',
	author: 'Wbcom Designs',
	author_uri: 'https://wbcomdesigns.com',
	description: 'Theme for your community with BuddyPress.',
	version: '5.0.0',
	underscoreCase: 'buddyx',
	constant: 'BUDDYX',
	camelCase: 'Buddyx',
	camelCaseVar: 'buddyx',
};

// Default Theme Paths - use dist/ folder to avoid overwriting source theme
// Use dist_slug (with hyphen) for folder/zip names, slug (without hyphen) for PHP identifiers
const distSlug = config.theme.dist_slug || config.theme.slug;
export const prodThemePath = isProd
	? path.normalize( `${ rootPath }/dist/${ distSlug }` )
	: undefined;
export const prodAssetsDir = isProd ? `${ prodThemePath }/assets` : assetsDir;

// Project paths
export const paths = {
	assetsDir,
	browserSync: {
		dir: `${ rootPath }/BrowserSync`,
		cert: `${ rootPath }/BrowserSync/buddyx-browser-sync-cert.crt`,
		caCert: `${ rootPath }/BrowserSync/buddyx-browser-sync-root-cert.crt`,
		key: `${ rootPath }/BrowserSync/buddyx-browser-sync-key.key`,
	},
	config: {
		themeConfig: `${ rootPath }/config/themeConfig.js`,
	},
	php: {
		src: [
			`${ rootPath }/**/*.php`,
			`!${ rootPath }/optional/**/*.*`,
			`!${ rootPath }/tests/**/*.*`,
			`!${ rootPath }/vendor/**/*.*`,
			`!${ rootPath }/wp-cli/**/*.*`,
			`!${ rootPath }/node_modules/**/*.*`,
			`!${ rootPath }/childify_backup/**/*.*`,
			`!${ rootPath }/scripts/**/*.*`,
			`!${ rootPath }/scrips/**/*.*`,
			`!${ rootPath }/dist/**/*.*`,
			`!${ rootPath }/rector.php`,
		],
		dest: `${ rootPath }/`,
	},
	styles: {
		editorSrc: [
			`${ assetsDir }/css/src/editor/**/*.css`,
			// Ignore partial files.
			`!${ assetsDir }/css/src/**/_*.css`,
		],
		editorSrcDir: `${ assetsDir }/css/src/editor`,
		editorDest: `${ assetsDir }/css/editor`,
		src: [
			`${ assetsDir }/css/src/**/*.css`,
			// Ignore partial files.
			`!${ assetsDir }/css/src/**/_*.css`,
			// Ignore editor source css.
			`!${ assetsDir }/css/src/editor/**/*.css`,
		],
		srcDir: `${ assetsDir }/css/src`,
		dest: `${ assetsDir }/css`,
	},
	scripts: {
		src: [
			`${ assetsDir }/js/src/**/*.js`,
			`!${ assetsDir }/js/src/**/_*.js`,
		],
		srcDir: `${ assetsDir }/js/src`,
		dest: `${ assetsDir }/js`,
	},
	images: {
		src: `${ assetsDir }/images/src/**/*.{jpg,JPG,png,svg,gif,GIF}`,
		dest: `${ assetsDir }/images/`,
	},
	fonts: {
		src: `${ assetsDir }/fonts/**/*.{woff,woff2,eot,ttf,svg}`,
		dest: `${ assetsDir }/fonts/`,
	},
	export: {
		src: [],
		stringReplaceSrc: [
			`${ rootPath }/style.css`,
			`${ rootPath }/languages/*.po`,
		],
	},
	languages: {
		src: [
			`${ rootPath }/**/*.php`,
			`!${ rootPath }/optional/**/*.*`,
			`!${ rootPath }/tests/**/*.*`,
			`!${ rootPath }/vendor/**/*.*`,
		],
		dest: `${ rootPath }/languages/${ nameFieldDefaults.slug }.pot`,
	},
};

// Add rootPath to filesToCopy and additionalFilesToCopy
const additionalFilesToCopy = configValueDefined(
	'export.additionalFilesToCopy'
)
	? config.export.additionalFilesToCopy
	: [];
const filesToCopy = configValueDefined( 'export.filesToCopy' )
	? config.export.filesToCopy
	: [];
for ( const filePath of filesToCopy.concat( additionalFilesToCopy ) ) {
	// Add the files to export src - use path.posix.join to ensure forward slashes
	// This ensures cross-platform compatibility (Windows/Mac/Linux)
	const exportPath = `${ rootPath }/${ filePath }`.replace(/\\/g, '/');
	paths.export.src.push( exportPath );
}

// Override paths for production
if ( isProd ) {
	paths.php.dest = `${ prodThemePath }/`;
	paths.styles.dest = `${ prodAssetsDir }/css/`;
	paths.styles.editorDest = `${ prodAssetsDir }/css/editor/`;
	paths.scripts.dest = `${ prodAssetsDir }/js/`;
	paths.images.dest = `${ prodAssetsDir }/images/`;
	paths.fonts.dest = `${ prodAssetsDir }/fonts/`;
	paths.languages = {
		src: `${ prodThemePath }/**/*.php`,
		dest: `${ prodThemePath }/languages/${ config.theme.slug }.pot`,
	};
}
