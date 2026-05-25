/**
 * Functionality smoke test for BuddyX 5.1.0 free.
 *
 * Verifies:
 *   1. Style preset swap in Customizer updates the preview pane palette
 *   2. Color-mode toggle on front-end actually switches modes + persists
 *   3. No FOUC: first paint after navigation matches saved preference
 *
 * Exit codes:
 *   0 - all checks pass
 *   1 - autologin failed
 *   2 - style preset swap did not apply
 *   3 - color-mode toggle did not switch
 *   4 - dark mode did not persist across page reload
 */

import { chromium } from 'playwright';

const BASE_URL = process.env.BUDDYX_BASE_URL || 'http://buddyx.local';
const browser = await chromium.launch({ headless: true });
const ctx = await browser.newContext({ viewport: { width: 1200, height: 900 } });
const page = await ctx.newPage();

let exitCode = 0;
const report = [];

function log(msg) { console.log(`[func] ${msg}`); }
function pass(label) { report.push(`PASS ${label}`); log(`PASS ${label}`); }
function fail(label, err) { report.push(`FAIL ${label}: ${err}`); log(`FAIL ${label}: ${err}`); }

// 1. Authenticate via mu-plugin
log(`authenticate via ${BASE_URL}/?autologin=1`);
const auth = await page.goto(`${BASE_URL}/?autologin=1`, { waitUntil: 'networkidle' });
if (!auth || !auth.ok()) { fail('autologin', auth?.status()); process.exit(1); }
pass('autologin');

// 2. Style preset swap test
try {
  log('open customizer Site Skin section');
  await page.goto(`${BASE_URL}/wp-admin/customize.php?autofocus[section]=site_skin_section`, { waitUntil: 'networkidle' });
  await page.waitForTimeout(2000);

  // Read default primary token from preview iframe BEFORE swap
  const previewFrame = page.frameLocator('iframe[title="Site Preview"]');
  const tokenBefore = await previewFrame.locator('body').evaluate(el =>
    getComputedStyle(document.documentElement).getPropertyValue('--bx-color-accent').trim() ||
    getComputedStyle(document.documentElement).getPropertyValue('--bx-color-primary').trim() ||
    'unknown'
  ).catch(() => 'unreadable');
  log(`accent token before preset swap: ${tokenBefore}`);

  // Click the "Cool" preset swatch
  const coolLabel = page.locator('label:has-text("Cool")').first();
  await coolLabel.click({ timeout: 5000 });
  await page.waitForTimeout(3000); // preset uses 'refresh' transport - wait for preview reload

  // Re-read token after swap
  const tokenAfter = await previewFrame.locator('body').evaluate(el =>
    getComputedStyle(document.documentElement).getPropertyValue('--bx-color-accent').trim() ||
    getComputedStyle(document.documentElement).getPropertyValue('--bx-color-primary').trim() ||
    'unknown'
  ).catch(() => 'unreadable');
  log(`accent token after Cool preset:  ${tokenAfter}`);

  if (tokenBefore !== tokenAfter && tokenAfter !== 'unknown' && tokenAfter !== 'unreadable') {
    pass('style preset swap updates --bx-color-accent token');
  } else {
    fail('style preset swap', `tokens match before=${tokenBefore} after=${tokenAfter}`);
    exitCode = 2;
  }
} catch (e) {
  fail('style preset swap', e.message);
  exitCode = 2;
}

// 3. Front-end color-mode toggle click + persistence
try {
  log('open front-end (logged out) to test visitor toggle');
  await ctx.clearCookies();
  await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle' });

  // Read mode before click
  const modeBefore = await page.evaluate(() => document.documentElement.getAttribute('data-bx-mode') || 'unset');
  log(`mode before click: ${modeBefore}`);

  // Click the header toggle
  await page.locator('.bx-color-mode-toggle__btn').first().click({ timeout: 5000 });
  await page.waitForTimeout(500);

  const modeAfterClick = await page.evaluate(() => document.documentElement.getAttribute('data-bx-mode'));
  log(`mode after click: ${modeAfterClick}`);

  if (modeAfterClick && modeAfterClick !== modeBefore) {
    pass(`toggle click switches data-bx-mode: ${modeBefore} -> ${modeAfterClick}`);
  } else {
    fail('toggle click', `mode unchanged: ${modeBefore} -> ${modeAfterClick}`);
    exitCode = 3;
  }

  // Persistence: reload, verify mode comes back from localStorage
  log('reload page to check localStorage persistence + FOUC-free first paint');
  await page.reload({ waitUntil: 'domcontentloaded' });

  // Read mode IMMEDIATELY after DOMContentLoaded (before any JS init)
  const modePersisted = await page.evaluate(() => document.documentElement.getAttribute('data-bx-mode'));
  log(`mode after reload (DOMContentLoaded): ${modePersisted}`);

  if (modePersisted === modeAfterClick) {
    pass('color-mode persists across reload + applied at first paint (no FOUC)');
  } else {
    fail('color-mode persistence', `expected ${modeAfterClick}, got ${modePersisted}`);
    exitCode = 4;
  }

  // Verify localStorage key exists
  const ls = await page.evaluate(() => window.localStorage.getItem('bx-color-mode'));
  log(`localStorage[bx-color-mode] = ${ls}`);
  if (ls === modeAfterClick) {
    pass(`localStorage[bx-color-mode] matches clicked mode (${ls})`);
  }

} catch (e) {
  fail('toggle persistence', e.message);
  exitCode = 3;
}

console.log('\n=== Functionality Report ===');
for (const line of report) console.log(line);
console.log('============================');

await browser.close();
process.exit(exitCode);
