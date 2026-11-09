/**
 * Visitor color-mode toggle — light ↔ dark toggle.
 *
 * Pairs with the bootstrap script in inc/Tokens/Component.php which sets
 * <html data-bx-mode> in <head> (FOUC-safe; color-mode-bootstrap.js). This module
 * just handles the click cycle, syncs the button state, and writes back
 * to localStorage.
 *
 * @package
 */

(function () {
	'use strict';

	const STORAGE_KEY = 'bx-color-mode';
	// Light ↔ dark only. The "auto"/system option was retired in 5.1.4; a stale
	// 'auto' in localStorage is no longer valid and resolves to light.
	const ORDER = ['light', 'dark'];
	// Translated labels are passed from PHP (buddyxColorModeLabels); the English
	// strings are only a fallback if that data is missing.
	const LABELS = Object.assign(
		{
			light: 'Light mode (click to switch to dark)',
			dark: 'Dark mode (click to switch to light)',
		},
		window.buddyxColorModeLabels || {}
	);

	// Short visible text for the mobile-menu button; the long sentence stays in aria-label.
	const SHORT_LABELS = Object.assign(
		{ light: 'Light mode', dark: 'Dark mode' },
		window.buddyxColorModeShortLabels || {}
	);

	/** The mode the bootstrap applied (it already weighed storage, stamp and owner default). */
	function readPersisted() {
		const attr = document.documentElement.getAttribute('data-bx-mode');
		return ORDER.indexOf(attr) !== -1 ? attr : 'light';
	}

	/**
	 * Write mode to localStorage with the default it was chosen against, so the
	 * bootstrap can drop it if the owner later changes the default (best-effort).
	 * @param {string} mode One of light|dark.
	 */
	function writePersisted(mode) {
		try {
			window.localStorage.setItem(STORAGE_KEY, mode);
			window.localStorage.setItem(
				'bx-color-mode-against',
				document.documentElement.getAttribute('data-bx-default') ||
					'light'
			);
		} catch {
			/* private mode — accept ephemeral state */
		}
	}

	/**
	 * Apply mode to <html data-bx-mode> + sync all buttons + dispatch event.
	 * The attribute is the single source of truth; no body class is mirrored.
	 * @param {string} mode One of light|dark|auto.
	 */
	function applyMode(mode) {
		document.documentElement.setAttribute('data-bx-mode', mode);
		const buttons = document.querySelectorAll('.bx-color-mode-toggle__btn');
		for (let i = 0; i < buttons.length; i++) {
			const btn = buttons[i];
			btn.setAttribute('data-mode', mode);
			btn.setAttribute(
				'aria-pressed',
				mode === 'dark' ? 'true' : 'false'
			);
			btn.setAttribute('aria-label', LABELS[mode]);
			btn.setAttribute('data-label', SHORT_LABELS[mode]);
			const sr = btn.querySelector('.screen-reader-text');
			if (sr) {
				sr.textContent = LABELS[mode];
			}
		}
		document.dispatchEvent(
			new CustomEvent('bx:color-mode-change', {
				detail: { mode },
			})
		);
	}

	/**
	 * Toggle between the two modes.
	 * @param {string} current Current mode.
	 * @return {string} The other mode.
	 */
	function cycle(current) {
		return current === 'dark' ? 'light' : 'dark';
	}

	/**
	 * Click handler — delegated so dynamically-added buttons (mobile menu open) still work.
	 * @param {MouseEvent} e Click event.
	 */
	function onClick(e) {
		const btn =
			e.target.closest && e.target.closest('.bx-color-mode-toggle__btn');
		if (!btn) {
			return;
		}
		e.preventDefault();
		const current = btn.getAttribute('data-mode') || readPersisted();
		const next = cycle(current);
		writePersisted(next);
		applyMode(next);
	}

	/**
	 * Re-sync on bfcache restore so back/forward shows current state.
	 * @param {PageTransitionEvent} e Page-show event.
	 */
	function onPageShow(e) {
		if (e.persisted) {
			// The visitor may have toggled on a later page; storage holds that choice.
			let stored = null;
			try {
				stored = window.localStorage.getItem(STORAGE_KEY);
			} catch {
				/* private mode */
			}
			applyMode(ORDER.indexOf(stored) !== -1 ? stored : readPersisted());
		}
	}

	/** Boot: sync button state to whatever bootstrap script already applied. */
	function init() {
		applyMode(readPersisted());
		document.addEventListener('click', onClick);
		window.addEventListener('pageshow', onPageShow);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
