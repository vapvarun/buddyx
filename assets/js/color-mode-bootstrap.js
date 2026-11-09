/**
 * Color-mode bootstrap - inlined in <head> before any CSS paints (no flash of the wrong mode).
 *
 * Shared by BuddyX and BuddyX Pro (free is the master copy). PHP prints
 * `var buddyxColorModeConfig = { def, force, legacy }` just before this file:
 *   def    - the owner's default mode ('light' | 'dark').
 *   force  - true when the owner hid the visitor toggle: the default wins and
 *            any stored choice is dropped (the visitor has no control to undo it).
 *   legacy - a choice saved by an older version outside the browser (Pro member
 *            profile), migrated once; '' when there is none.
 *   page   - a mode the owner forced on this one page ('light' | 'dark' | ''). It
 *            wins on this page only and leaves the stored choice alone.
 *
 * A stored choice carries the default it was made against ('bx-color-mode-against').
 * When the owner later changes the default, older choices are dropped so the new
 * default reaches returning visitors. Choices saved before stamping existed are kept.
 *
 * @package
 * @param {Object} config Bootstrap config printed by PHP (def, force, legacy).
 */

/* global MutationObserver */
(function (config) {
	const KEY = 'bx-color-mode';
	const STAMP = 'bx-color-mode-against';
	const root = document.documentElement;
	const def = config.def === 'dark' ? 'dark' : 'light';
	let mode = def;

	// Older versions kept the choice in cookies: free's `bxtheme` (light|dark) and
	// Pro's `buddyx_dark_mode` (true|false) with `buddyx_color_mode_against`. Read
	// them once, then expire them.
	function cookie(name) {
		const m = new RegExp('(?:^|;\\s*)' + name + '=([^;]*)').exec(
			document.cookie
		);
		if (m) {
			document.cookie =
				name + '=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
		}
		return m ? decodeURIComponent(m[1]) : '';
	}
	const proCookie = cookie('buddyx_dark_mode');
	const proAgainst = cookie('buddyx_color_mode_against');
	const freeCookie = cookie('bxtheme');
	let legacy = config.legacy || '';
	if (!legacy && proCookie) {
		legacy = proCookie === 'true' ? 'dark' : 'light';
	}
	if (!legacy && freeCookie === 'dark') {
		legacy = 'dark';
	}

	try {
		const store = window.localStorage;
		if (config.force) {
			store.removeItem(KEY);
			store.removeItem(STAMP);
		} else {
			let saved = store.getItem(KEY);
			let against = store.getItem(STAMP);
			if (!saved && (legacy === 'light' || legacy === 'dark')) {
				saved = legacy;
				against = proAgainst;
				store.setItem(KEY, saved);
				if (against) {
					store.setItem(STAMP, against);
				}
			}
			if (saved === 'light' || saved === 'dark') {
				if (!against || against === def) {
					mode = saved;
				} else {
					store.removeItem(KEY);
					store.removeItem(STAMP);
				}
			}
		}
	} catch {
		// Storage blocked (private mode): the owner default applies.
	}

	if (config.page === 'light' || config.page === 'dark') {
		mode = config.page;
	}

	root.setAttribute('data-bx-mode', mode);
	root.setAttribute('data-bx-default', def);

	// Plugins with their own light/dark palette (marked data-bx-mode-sync, e.g.
	// Tutor LMS) follow the visitor's choice, now and when the toggle changes it.
	const sync = root.getAttribute('data-bx-mode-sync');
	if (sync) {
		const mirror = function () {
			root.setAttribute(sync, root.getAttribute('data-bx-mode'));
		};
		mirror();
		new MutationObserver(mirror).observe(root, {
			attributes: true,
			attributeFilter: ['data-bx-mode'],
		});
	}
})(window.buddyxColorModeConfig || {});
