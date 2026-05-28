// eslint.config.js - flat config for @wordpress/eslint-plugin 25.x + ESLint 9.

import { createRequire } from 'module';
import tsParser from '@typescript-eslint/parser';
import globals from 'globals';

// @wordpress/eslint-plugin 25.x exposes flat configs via its default export's
// `configs` map. The package's exports field restricts deep paths so we have
// to use the top-level entry, not configs/recommended directly.
const require = createRequire( import.meta.url );
const wpPlugin = require( '@wordpress/eslint-plugin' );
const wpRecommendedConfig = wpPlugin.configs.recommended;

export default [
	// Global ignores (alternative to .eslintignore)
	{
		ignores: [
			'assets/js/libs/**',
			'node_modules/**',
			'vendor/**',
			'config/**',
			'gulpfile.babel.js', // Parsing error: 'import' and 'export' may appear only with 'sourceType: module'
			'**/*.min.{js,ts,jsx,tsx}', // All minified files
		],
	},

	// Convert WordPress recommended configuration
	...wpRecommendedConfig,

	// Configuration files: explicitly allow devDependencies
	{
		files: [
			'eslint.config.js',
			'webpack.config.js',
			'*.config.js',
			'gulpfile.js',
		],
		rules: {
			'import/no-extraneous-dependencies': [
				'error',
				{
					devDependencies: [
						'eslint.config.js',
						'webpack.config.js',
						'*.config.js',
						'gulpfile.js',
					],
				},
			],
		},
	},

	// JSX/TSX files - Use TypeScript parser for JSX support
	{
		files: [ '**/*.jsx', '**/*.tsx' ],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			parser: tsParser,
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
			},
			globals: {
				wp: 'readonly',
				buddyxScreenReaderText: 'readonly',
				jQuery: 'readonly',
				$: 'readonly',
				window: 'readonly',
				document: 'readonly',
				console: 'readonly',
			},
		},
	},

	// TypeScript-specific configuration
	{
		files: [ '**/*.ts', '**/*.tsx' ],
		rules: {
			// Relax JSDoc rules for TypeScript
			'jsdoc/no-undefined-types': 'off', // TypeScript types are often not defined in JSDoc
		},
	},

	// Console rule for all file types
	{
		files: [ '**/*.{js,jsx,ts,tsx}' ],
		rules: {
			'no-console': 'warn',
		},
	},

	// Overides rules for gulp folder. Replaces the former gulp/.eslintrc.json.
	{
		files: [ 'gulp/**/*.js', 'gulpfile.js' ],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			globals: {
				...globals.node, // Node globals for tooling. No node env in new syntax.
			},
		},
		rules: {
			'no-console': 'off',
			semi: 'error',
			'no-unused-vars': 'error',
			'jsdoc/no-undefined-types': [
				'error',
				{ definedTypes: [ 'Stream' ] },
			],
			'import/no-extraneous-dependencies': [
				'error',
				{
					devDependencies: true, // allow dev deps in tooling files
					optionalDependencies: true,
					peerDependencies: true,
				},
			],
		},
	},
];
