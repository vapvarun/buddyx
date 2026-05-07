/**
 * Visitor color-mode toggle — light → dark → auto cycle.
 *
 * Pairs with the bootstrap script in inc/Tokens/Component.php which sets
 * <html data-bx-mode> from localStorage in <head> (FOUC-safe). This module
 * just handles the click cycle, syncs the button state, and writes back
 * to localStorage.
 *
 * @package
 */

(function () {
	'use strict';

	const STORAGE_KEY = 'bx-color-mode';
	const ORDER = ['light', 'dark', 'auto'];
	const LABELS = {
		light: 'Light mode (click to switch to dark)',
		dark: 'Dark mode (click to switch to system)',
		auto: 'System mode (click to switch to light)',
	};

	/** Read mode from localStorage; fall back to current <html> attribute. */
	function readPersisted() {
		try {
			const v = window.localStorage.getItem(STORAGE_KEY);
			if (v && ORDER.indexOf(v) !== -1) {
				return v;
			}
		} catch (e) {
			/* private mode */
		}
		const attr = document.documentElement.getAttribute('data-bx-mode');
		return ORDER.indexOf(attr) !== -1 ? attr : 'light';
	}

	/**
	 * Write mode to localStorage (best-effort; never throws).
	 * @param {string} mode One of light|dark|auto.
	 */
	function writePersisted(mode) {
		try {
			window.localStorage.setItem(STORAGE_KEY, mode);
		} catch (e) {
			/* private mode — accept ephemeral state */
		}
	}

	/**
	 * Apply mode to <html> + sync all buttons + dispatch event.
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
	 * Cycle to the next mode in the rotation.
	 * @param {string} current Current mode.
	 * @return {string} Next mode in the cycle.
	 */
	function cycle(current) {
		const idx = ORDER.indexOf(current);
		return ORDER[(idx + 1) % ORDER.length];
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
			applyMode(readPersisted());
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
