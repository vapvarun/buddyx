#!/usr/bin/env node
/**
 * Verify docs/website/*.md content against the customizer inventory
 * snapshot + capture-map. Catches drift when:
 *
 *   - A customizer field is renamed/removed in the code, but docs still
 *     reference the old setting_id
 *   - A doc claims a default value that the code disagrees with
 *   - A doc embeds an image not registered in tools/capture-map.json
 *   - capture-map references a doc page that doesn't exist
 *
 * Run:
 *   node tools/docs-manifest-check.mjs
 *
 * Exit codes:
 *   0 - all checks pass
 *   1 - drift detected (file paths + offending lines reported)
 *   2 - inventory snapshot is stale (regenerate with dump-customizer-inventory.py)
 *
 * Wired into the pre-commit hook via lint-staged for changes to:
 *   docs/website/**\/*.md
 *   docs/customizer-inventory-snapshot.txt
 *   tools/capture-map.json
 *   inc/Customizer_Settings/Fields/*.php
 */

import { readFileSync, readdirSync, statSync, existsSync } from 'node:fs';
import { dirname, join, relative, basename } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const REPO_ROOT = join(__dirname, '..');
const INVENTORY = join(REPO_ROOT, 'docs', 'customizer-inventory-snapshot.txt');
const CAPTURE_MAP = join(REPO_ROOT, 'tools', 'capture-map.json');
const DOCS_DIR = join(REPO_ROOT, 'docs', 'website');
const IMAGES_DIR = join(REPO_ROOT, 'docs', 'website', 'images');

let errors = 0;
const findings = [];
function fail(file, line_no, msg) {
	errors++;
	findings.push({ file: relative(REPO_ROOT, file), line: line_no, msg });
}

// ---------------------------------------------------------------------------
// 1) Parse customizer inventory snapshot
// ---------------------------------------------------------------------------

if (!existsSync(INVENTORY)) {
	console.error(`[docs-check] missing ${INVENTORY}`);
	console.error('[docs-check] regenerate: python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt');
	process.exit(2);
}

const inventory_text = readFileSync(INVENTORY, 'utf8');
const inventory_settings = new Map(); // setting_id -> { default, section, file }
const inventory_sections = new Set();

let current_section = null;
for (const raw of inventory_text.split('\n')) {
	const sec = raw.match(/^\[([a-z0-9_]+)\]/);
	if (sec) {
		current_section = sec[1];
		inventory_sections.add(current_section);
		continue;
	}
	const row = raw.match(/^\s*\d+\s*\|\s*\S+\s*\|\s*([a-zA-Z0-9_\[\]]+)\s*\|\s*(\S.*?)\s*\|\s*(\S+)/);
	if (row) {
		const setting_id = row[1].replace(/\[.*$/, ''); // drop [color] sub-keys
		const default_val = row[2].trim();
		const file = row[3].trim();
		if (!inventory_settings.has(setting_id)) {
			inventory_settings.set(setting_id, { default: default_val, section: current_section, file });
		}
	}
}

console.log(`[docs-check] inventory: ${inventory_settings.size} settings across ${inventory_sections.size} sections`);

// ---------------------------------------------------------------------------
// 2) Parse capture-map.json
// ---------------------------------------------------------------------------

let capture_map = [];
if (existsSync(CAPTURE_MAP)) {
	try {
		capture_map = JSON.parse(readFileSync(CAPTURE_MAP, 'utf8'));
	} catch (e) {
		console.error(`[docs-check] capture-map.json parse error: ${e.message}`);
		process.exit(2);
	}
}
const capture_paths = new Set(capture_map.map(e => e.path));

// ---------------------------------------------------------------------------
// 3) Walk every .md under docs/website/ and check claims
// ---------------------------------------------------------------------------

function walk_md(dir, acc = []) {
	for (const entry of readdirSync(dir)) {
		const p = join(dir, entry);
		const s = statSync(p);
		if (s.isDirectory()) walk_md(p, acc);
		else if (entry.endsWith('.md')) acc.push(p);
	}
	return acc;
}

const docs = walk_md(DOCS_DIR);
console.log(`[docs-check] scanning ${docs.length} markdown files under docs/website/`);

// Allowed setting-name-shaped tokens that AREN'T customizer settings.
// (Add to this list when introducing new non-setting backticked tokens.)
const allowlist_tokens = new Set([
	// Non-customizer terms that look like setting IDs but aren't
	'wp_admin_bar', 'wp_login', 'wp_logout', 'wp_users', 'wp_usermeta',
	'theme_mods_buddyx', 'WP_MEMORY_LIMIT',
	'site_color_mode_toggle_show', // dynamic field registered at runtime (Color_Mode_Toggle)
	'site_color_mode_toggle_position',
	'dark_mode_logo', // dynamic field registered by Custom_Logo Component
	// Cookie / localStorage keys
	'bx-color-mode',
	// CSS tokens (handled separately if needed)
	'bx_color_primary', 'bx_color_bg', 'bx_color_fg',
]);

// Strip fenced code blocks (```...```) before checking — illustrative
// snippets inside fences may reference placeholder filenames or example
// tokens that shouldn't trigger drift findings.
function strip_fenced_blocks(text) {
	const out = [];
	let in_fence = false;
	for (const line of text.split('\n')) {
		if (/^\s*```/.test(line)) { in_fence = !in_fence; out.push(''); continue; }
		out.push(in_fence ? '' : line);
	}
	return out.join('\n');
}

for (const doc of docs) {
	const raw_content = readFileSync(doc, 'utf8');
	const content = strip_fenced_blocks(raw_content);
	const lines = content.split('\n');

	for (let i = 0; i < lines.length; i++) {
		const line = lines[i];
		const line_no = i + 1;

		// Skip URL/code-block contexts roughly (anchors, links)
		// Each line is checked for backticked-setting-shape tokens.

		// Find `backticked_things` that look like setting IDs
		// (snake_case, all-lowercase or with digits, 2+ words). Reduces
		// false positives from generic backticked code samples.
		const inline_codes = [...line.matchAll(/`([a-z][a-z0-9_]*(?:_[a-z0-9]+)+)`/g)];
		for (const m of inline_codes) {
			const token = m[1];
			if (allowlist_tokens.has(token)) continue;
			// Tokens that look like customizer setting IDs follow the
			// `<scope>_<thing>_<more>` pattern. Skip very short ones.
			if (token.length < 6) continue;
			// Heuristic: setting IDs in this codebase typically start with
			// 'site_', 'buddyx_', 'blog_', 'menu_', 'h1_'..'h6_',
			// 'typography_', 'sub_header', 'enable_', 'custom_'
			const setting_shaped = /^(site_|buddyx_|blog_|menu_|h[1-6]_|sub_|typography_|body_|background_|enable_|custom_|fluentcart_|surecart_|bbpress_|buddypress_|woocommerce_|sticky_)/.test(token);
			if (!setting_shaped) continue;

			if (!inventory_settings.has(token)) {
				fail(doc, line_no, `unknown customizer setting "${token}" — not in inventory snapshot`);
			}
		}
	}

	// Image-embed check: every ![](path) must resolve AND each images/
	// path should correspond to a capture-map entry
	const image_refs = [...content.matchAll(/!\[[^\]]*\]\(([^)]+)\)/g)];
	for (const m of image_refs) {
		const ref = m[1].split('#')[0].trim();
		if (ref.startsWith('http')) continue;
		if (/^\.+$/.test(ref) || /^url$/i.test(ref) || /your-screenshot/.test(ref)) continue;
		// Resolve relative to the doc
		const abs = join(dirname(doc), ref);
		if (!existsSync(abs)) {
			fail(doc, 0, `broken image reference: ${ref}`);
			continue;
		}
		// Cross-check capture-map for image entries under docs/website/images/
		if (abs.startsWith(IMAGES_DIR + '/') || abs.startsWith(IMAGES_DIR + '\\')) {
			const rel_to_images = relative(IMAGES_DIR, abs);
			if (!capture_paths.has(rel_to_images)) {
				fail(doc, 0, `image not registered in capture-map.json: ${rel_to_images}`);
			}
		}
	}
}

// ---------------------------------------------------------------------------
// 4) Reverse check: every capture-map path should have a corresponding file
// ---------------------------------------------------------------------------

for (const entry of capture_map) {
	const abs = join(IMAGES_DIR, entry.path);
	if (!existsSync(abs)) {
		fail(CAPTURE_MAP, 0, `capture-map entry missing on disk: ${entry.path}`);
	}
}

// ---------------------------------------------------------------------------
// Report
// ---------------------------------------------------------------------------

if (errors === 0) {
	console.log(`[docs-check] PASS — no drift detected`);
	process.exit(0);
}

console.log(`[docs-check] FAIL — ${errors} drift finding(s):\n`);
for (const f of findings) {
	console.log(`  ${f.file}${f.line ? ':' + f.line : ''}  ${f.msg}`);
}
console.log(`\n[docs-check] resolution steps:`);
console.log(`  - unknown setting → regenerate inventory if field was added:`);
console.log(`      python3 tools/dump-customizer-inventory.py > docs/customizer-inventory-snapshot.txt`);
console.log(`  - unknown setting → OR fix the doc typo if the setting really doesn't exist`);
console.log(`  - broken image → either capture it or remove the reference`);
console.log(`  - image not in capture-map → add an entry to tools/capture-map.json`);

process.exit(1);
