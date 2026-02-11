#!/usr/bin/env node
/**
 * BuddyX Distribution Script
 *
 * Creates a distribution-ready zip file with proper version tagging.
 *
 * Usage:
 *   npm run dist              # Interactive mode, prompts for version
 *   npm run dist -- 1.2.3     # Use specified version
 *   npm run dist -- --current # Use current version from style.css
 *
 * @package buddyx
 */

import { execFile as execFileCb, spawn } from 'node:child_process';
import { promisify } from 'node:util';
import fs from 'node:fs';
import path from 'node:path';
import readline from 'node:readline';
import { fileURLToPath } from 'node:url';

const execFile = promisify(execFileCb);
const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '..');

/**
 * Get current version from style.css
 */
function getCurrentVersion() {
	const stylePath = path.join(rootDir, 'style.css');
	const content = fs.readFileSync(stylePath, 'utf8');
	const match = content.match(/Version:\s*(\d+\.\d+\.\d+)/i);
	return match ? match[1] : '1.0.0';
}

/**
 * Update version in style.css
 */
function updateStyleVersion(version) {
	const stylePath = path.join(rootDir, 'style.css');
	let content = fs.readFileSync(stylePath, 'utf8');
	content = content.replace(
		/(Version:\s*)(\d+\.\d+\.\d+)/i,
		`$1${version}`
	);
	fs.writeFileSync(stylePath, content);
	console.log(`Updated style.css version to ${version}`);
}

/**
 * Update version in config/config.default.json
 */
function updateConfigVersion(version) {
	const configPath = path.join(rootDir, 'config', 'config.default.json');
	if (fs.existsSync(configPath)) {
		const config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
		config.theme.version = version;
		fs.writeFileSync(configPath, JSON.stringify(config, null, 2) + '\n');
		console.log(`Updated config.default.json version to ${version}`);
	}
}

/**
 * Prompt user for version number
 */
async function promptForVersion(currentVersion) {
	const rl = readline.createInterface({
		input: process.stdin,
		output: process.stdout,
	});

	return new Promise((resolve) => {
		console.log(`\nCurrent version: ${currentVersion}`);
		console.log('Suggested versions:');

		const parts = currentVersion.split('.').map(Number);
		const patch = `${parts[0]}.${parts[1]}.${parts[2] + 1}`;
		const minor = `${parts[0]}.${parts[1] + 1}.0`;
		const major = `${parts[0] + 1}.0.0`;

		console.log(`  1. Patch: ${patch}`);
		console.log(`  2. Minor: ${minor}`);
		console.log(`  3. Major: ${major}`);
		console.log(`  4. Custom version`);
		console.log(`  5. Keep current (${currentVersion})`);

		rl.question('\nSelect option (1-5) or enter version: ', (answer) => {
			rl.close();
			answer = answer.trim();

			if (answer === '1') resolve(patch);
			else if (answer === '2') resolve(minor);
			else if (answer === '3') resolve(major);
			else if (answer === '4') {
				const rl2 = readline.createInterface({
					input: process.stdin,
					output: process.stdout,
				});
				rl2.question('Enter version (x.y.z): ', (ver) => {
					rl2.close();
					resolve(ver.trim() || currentVersion);
				});
			} else if (answer === '5' || answer === '') {
				resolve(currentVersion);
			} else if (/^\d+\.\d+\.\d+$/.test(answer)) {
				resolve(answer);
			} else {
				console.log('Invalid input, using current version.');
				resolve(currentVersion);
			}
		});
	});
}

/**
 * Run the bundle command using spawn for better output handling
 */
function runBundle() {
	return new Promise((resolve, reject) => {
		console.log('\nRunning production bundle...');
		console.log('This may take a few minutes.\n');

		// Determine npm command based on platform
		const npmCmd = process.platform === 'win32' ? 'npm.cmd' : 'npm';

		const child = spawn(npmCmd, ['run', 'bundle'], {
			cwd: rootDir,
			env: { ...process.env, NODE_ENV: 'production' },
			stdio: 'inherit', // Pass through output to console
		});

		child.on('error', (error) => {
			reject(new Error(`Failed to start bundle process: ${error.message}`));
		});

		child.on('close', (code) => {
			if (code === 0) {
				resolve(true);
			} else {
				reject(new Error(`Bundle process exited with code ${code}`));
			}
		});
	});
}

/**
 * Get theme slug from config
 */
function getThemeSlug() {
	const configPath = path.join(rootDir, 'config', 'config.default.json');
	try {
		const config = JSON.parse(fs.readFileSync(configPath, 'utf8'));
		return config.theme?.slug || 'buddyx';
	} catch {
		return 'buddyx';
	}
}

/**
 * Move and rename the generated zip with version
 */
function renameZipWithVersion(version) {
	const themeSlug = getThemeSlug();
	const distDir = path.join(rootDir, 'dist');

	// The bundle process creates the zip in dist/ folder
	const sourceZip = path.join(distDir, `${themeSlug}.zip`);
	const versionedZip = path.join(distDir, `${themeSlug}-${version}.zip`);

	if (fs.existsSync(sourceZip)) {
		// Remove existing versioned zip if present
		if (fs.existsSync(versionedZip)) {
			fs.unlinkSync(versionedZip);
		}

		// Rename with version
		fs.renameSync(sourceZip, versionedZip);

		console.log(`\nCreated: dist/${themeSlug}-${version}.zip`);

		// Get file size
		const stats = fs.statSync(versionedZip);
		const sizeMB = (stats.size / (1024 * 1024)).toFixed(2);
		console.log(`Size: ${sizeMB} MB`);

		return versionedZip;
	} else {
		console.error(`Warning: ${themeSlug}.zip not found at ${sourceZip}`);
		return null;
	}
}

/**
 * Create a git tag for the release using execFile (safe)
 */
async function createGitTag(version) {
	const rl = readline.createInterface({
		input: process.stdin,
		output: process.stdout,
	});

	return new Promise((resolve) => {
		rl.question(`\nCreate git tag v${version}? (y/N): `, async (answer) => {
			rl.close();

			if (answer.toLowerCase() === 'y') {
				try {
					// Use execFile with separate args (safe from injection)
					await execFile('git', ['tag', '-a', `v${version}`, '-m', `Release v${version}`], {
						cwd: rootDir,
					});
					console.log(`Created git tag: v${version}`);
					resolve(true);
				} catch (error) {
					console.error('Failed to create git tag:', error.message);
					resolve(false);
				}
			} else {
				console.log('Skipped git tag creation.');
				resolve(false);
			}
		});
	});
}

/**
 * Main distribution function
 */
async function main() {
	console.log('='.repeat(50));
	console.log('BuddyX Distribution Script');
	console.log('='.repeat(50));

	// Parse command line arguments
	const args = process.argv.slice(2);
	let version;

	const currentVersion = getCurrentVersion();

	if (args.includes('--current')) {
		version = currentVersion;
		console.log(`Using current version: ${version}`);
	} else if (args.length > 0 && /^\d+\.\d+\.\d+$/.test(args[0])) {
		version = args[0];
		console.log(`Using specified version: ${version}`);
	} else {
		version = await promptForVersion(currentVersion);
	}

	// Update version in files if changed
	if (version !== currentVersion) {
		updateStyleVersion(version);
		updateConfigVersion(version);
	}

	// Run the bundle
	try {
		await runBundle();
	} catch (error) {
		console.error('\nDistribution failed:', error.message);
		console.error('Please fix the errors and try again.');
		process.exit(1);
	}

	// Rename zip with version
	const zipPath = renameZipWithVersion(version);

	if (zipPath) {
		console.log('\n' + '='.repeat(50));
		console.log('Distribution complete!');
		console.log('='.repeat(50));
		console.log(`\nVersion: ${version}`);
		console.log(`Zip file: ${zipPath}`);

		// Optionally create git tag
		if (!args.includes('--no-tag')) {
			await createGitTag(version);
		}
	}
}

// Run the script
main().catch((error) => {
	console.error('Error:', error.message);
	process.exit(1);
});
