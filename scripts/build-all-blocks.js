#!/usr/bin/env node
// Block Build Script using esbuild
//
// Scans assets/blocks/*/src/index.{js,ts,tsx} and builds each block.
// Outputs to assets/blocks/*/build/index.js
// Rewrites @wordpress/* imports to window.wp.* globals.
//
// Usage:
//   node scripts/build-all-blocks.js          # production build
//   node scripts/build-all-blocks.js --watch  # watch mode

import * as esbuild from 'esbuild';
import { glob } from 'glob';
import path from 'node:path';
import fs from 'node:fs';

const isWatch = process.argv.includes( '--watch' );
const blocksDir = 'assets/blocks';

/**
 * WordPress package → window.wp global mapping.
 * e.g. @wordpress/blocks → window.wp.blocks
 */
const wpPackages = [
	'blocks',
	'block-editor',
	'components',
	'compose',
	'data',
	'element',
	'hooks',
	'i18n',
	'server-side-render',
	'primitives',
	'rich-text',
	'url',
	'api-fetch',
	'dom-ready',
	'edit-post',
	'editor',
	'plugins',
	'notices',
];

/**
 * esbuild plugin: Rewrite @wordpress/* imports to window.wp.* globals.
 */
const transformImportsPlugin = {
	name: 'wordpress-externals',
	setup( build ) {
		// Mark all @wordpress/* as external
		build.onResolve( { filter: /^@wordpress\// }, ( args ) => {
			return { path: args.path, namespace: 'wp-external' };
		} );

		// Also treat react/react-dom as external (WordPress provides them)
		build.onResolve( { filter: /^react(-dom)?$/ }, ( args ) => {
			return { path: args.path, namespace: 'wp-external' };
		} );

		build.onLoad(
			{ filter: /.*/, namespace: 'wp-external' },
			( args ) => {
				let globalVar;

				if ( args.path === 'react' ) {
					globalVar = 'window.React';
				} else if ( args.path === 'react-dom' ) {
					globalVar = 'window.ReactDOM';
				} else {
					// @wordpress/block-editor → wp.blockEditor
					const pkg = args.path.replace( '@wordpress/', '' );
					const camelCase = pkg.replace( /-([a-z])/g, ( _, c ) =>
						c.toUpperCase()
					);
					globalVar = `window.wp.${ camelCase }`;
				}

				return {
					contents: `module.exports = ${ globalVar };`,
					loader: 'js',
				};
			}
		);
	},
};

async function buildBlocks() {
	const entryPoints = await glob(
		`${ blocksDir }/*/src/index.{js,ts,tsx}`
	);

	if ( entryPoints.length === 0 ) {
		console.log( 'No blocks found to build.' );
		return;
	}

	for ( const entry of entryPoints ) {
		const blockDir = path.resolve( entry, '..', '..' );
		const blockName = path.basename( blockDir );
		const outdir = path.join( blockDir, 'build' );

		if ( ! fs.existsSync( outdir ) ) {
			fs.mkdirSync( outdir, { recursive: true } );
		}

		const buildOptions = {
			entryPoints: [ entry ],
			outfile: path.join( outdir, 'index.js' ),
			bundle: true,
			minify: ! isWatch,
			sourcemap: isWatch ? 'inline' : false,
			target: [ 'es2020' ],
			format: 'iife',
			jsx: 'automatic',
			jsxImportSource: '@wordpress/element',
			plugins: [ transformImportsPlugin ],
			define: {
				'process.env.NODE_ENV': isWatch
					? '"development"'
					: '"production"',
			},
			logLevel: 'info',
		};

		if ( isWatch ) {
			const ctx = await esbuild.context( buildOptions );
			await ctx.watch();
			console.log( `Watching: ${ blockName }` );
		} else {
			await esbuild.build( buildOptions );
			console.log( `Built: ${ blockName }` );
		}
	}

	console.log(
		`Block build complete (${ isWatch ? 'watching' : 'production' })`
	);
}

buildBlocks().catch( ( err ) => {
	console.error( 'Block build failed:', err );
	process.exit( 1 );
} );
