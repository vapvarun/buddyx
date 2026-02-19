#!/usr/bin/env node
/**
 * JavaScript Build Script using esbuild
 * Compiles JS files from assets/js/src to assets/js/*.min.js
 */

import * as esbuild from 'esbuild';
import { glob } from 'glob';
import path from 'node:path';
import fs from 'node:fs';

const isDev = process.argv.includes('--dev');
const srcDir = 'assets/js/src';
const destDir = 'assets/js';

/**
 * Copy pre-built vendor files (.cjs) to output directory
 * These are UMD bundles that don't need esbuild processing
 */
async function copyVendorFiles() {
	const vendorFiles = await glob(`${srcDir}/**/*.cjs`);

	if (vendorFiles.length === 0) {
		console.log('No vendor .cjs files found');
		return;
	}

	for (const file of vendorFiles) {
		const filename = path.basename(file, '.cjs');
		const relativePath = path.relative(srcDir, path.dirname(file));
		const outputDir = path.join(destDir, relativePath);

		// Ensure subdirectories exist
		if (!fs.existsSync(outputDir)) {
			fs.mkdirSync(outputDir, { recursive: true });
		}

		const outputFile = path.join(outputDir, `${filename}.min.js`);
		fs.copyFileSync(file, outputFile);
		console.log(`Copied vendor: ${outputFile}`);
	}
}

async function buildJS() {
	// Get all non-partial JS/TS files (exclude _*.js, _*.ts, _*.tsx)
	const files = await glob(`${srcDir}/**/*.{js,ts,tsx}`, {
		ignore: [`${srcDir}/**/_*.{js,ts,tsx}`]
	});

	if (files.length === 0) {
		console.log('No JS/TS files found to build');
		return;
	}

	// Ensure destination directory exists
	if (!fs.existsSync(destDir)) {
		fs.mkdirSync(destDir, { recursive: true });
	}

	// Build each entry file
	for (const file of files) {
		try {
			const ext = path.extname(file);
			const filename = path.basename(file, ext);
			const relativePath = path.relative(srcDir, path.dirname(file));
			const outputDir = path.join(destDir, relativePath);

			// Ensure subdirectories exist
			if (!fs.existsSync(outputDir)) {
				fs.mkdirSync(outputDir, { recursive: true });
			}

			const outputFile = path.join(outputDir, `${filename}.min.js`);

			await esbuild.build({
				entryPoints: [file],
				outfile: outputFile,
				bundle: true,
				minify: !isDev,
				sourcemap: isDev ? 'inline' : false,
				target: ['es2020'],
				format: 'iife',
				external: [
					'jquery',
					'wp',
					'@wordpress/*'
				],
				define: {
					'process.env.NODE_ENV': isDev ? '"development"' : '"production"'
				}
			});

			console.log(`Built: ${outputFile}`);
		} catch (error) {
			console.error(`Error building ${file}:`, error.message);
			process.exit(1);
		}
	}

	console.log(`JS build complete (${isDev ? 'development' : 'production'})`);
}

async function main() {
	await copyVendorFiles();
	await buildJS();
}

main().catch(err => {
	console.error('JS build failed:', err);
	process.exit(1);
});
