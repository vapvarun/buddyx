#!/usr/bin/env node
/**
 * CSS Build Script using Lightning CSS
 * Compiles CSS files from assets/css/src to assets/css/*.min.css
 */

import { transform } from 'lightningcss';
import fs from 'node:fs';
import path from 'node:path';
import { glob } from 'glob';

const isDev = process.argv.includes('--dev');
const srcDir = 'assets/css/src';
const destDir = 'assets/css';

// Custom media query definitions to prepend
const customMediaCSS = `
@custom-media --narrow-menu-query screen and (max-width: 37.5em);
@custom-media --wide-menu-query screen and (min-width: 37.5em);
@custom-media --content-query screen and (min-width: 48em);
@custom-media --sidebar-query screen and (min-width: 60em);
`;

// Resolve @import statements manually
function resolveImports(css, basePath, visited = new Set()) {
	const currentFile = path.resolve(basePath);
	if (visited.has(currentFile)) {
		return css; // Avoid circular imports
	}
	visited.add(currentFile);

	const dir = path.dirname(currentFile);
	const importRegex = /@import\s+["']([^"']+)["'];?/g;

	return css.replace(importRegex, (match, importPath) => {
		// Resolve the import path
		let fullPath = path.resolve(dir, importPath);

		// Add .css extension if not present
		if (!fullPath.endsWith('.css')) {
			fullPath += '.css';
		}

		if (!fs.existsSync(fullPath)) {
			console.warn(`Warning: Import not found: ${importPath} in ${basePath}`);
			return '';
		}

		const importedCSS = fs.readFileSync(fullPath, 'utf8');
		// Recursively resolve imports in the imported file
		return resolveImports(importedCSS, fullPath, visited);
	});
}

async function buildCSS() {
	// Get all non-partial CSS files
	const files = await glob(`${srcDir}/**/*.css`, {
		ignore: [`${srcDir}/**/_*.css`]
	});

	if (files.length === 0) {
		console.log('No CSS files found to build');
		return;
	}

	// Ensure destination directory exists
	if (!fs.existsSync(destDir)) {
		fs.mkdirSync(destDir, { recursive: true });
	}

	for (const file of files) {
		try {
			const filename = path.basename(file, '.css');
			const relativePath = path.relative(srcDir, path.dirname(file));
			const outputDir = path.join(destDir, relativePath);

			// Ensure subdirectories exist
			if (!fs.existsSync(outputDir)) {
				fs.mkdirSync(outputDir, { recursive: true });
			}

			const outputFile = path.join(outputDir, `${filename}.min.css`);

			// Read and resolve imports
			let css = fs.readFileSync(file, 'utf8');
			css = customMediaCSS + resolveImports(css, file);

			// Transform CSS
			const result = transform({
				filename: path.resolve(file),
				code: Buffer.from(css),
				minify: !isDev,
				sourceMap: isDev,
				drafts: {
					customMedia: true
				},
				targets: {
					chrome: 80 << 16,
					firefox: 75 << 16,
					safari: 13 << 16
				},
				errorRecovery: true
			});

			// Write output
			fs.writeFileSync(outputFile, result.code);

			if (isDev && result.map) {
				fs.writeFileSync(`${outputFile}.map`, result.map);
			}

			console.log(`Built: ${outputFile}`);
		} catch (error) {
			console.error(`Error building ${file}:`, error.message);
			process.exit(1);
		}
	}

	console.log(`CSS build complete (${isDev ? 'development' : 'production'})`);
}

buildCSS().catch(err => {
	console.error('CSS build failed:', err);
	process.exit(1);
});
