/**
 * BuddyX Customizer Framework — live preview JS.
 *
 * Loaded on the customizer preview iframe. Reads window.buddyxCustomizerOutputs
 * (injected by Component::enqueue_preview()) and updates inline CSS in <head>
 * without a full refresh whenever a postMessage-transported setting changes.
 *
 * Output payload shape:
 *   {
 *     "site_primary_color": [
 *       { "element": ".site-primary", "property": "color", "units": "" }
 *     ],
 *     "body_typography": [
 *       { "element": "body", "property": "", "units": "" }
 *     ]
 *   }
 *
 * @package buddyx
 */
(function () {
	'use strict';

	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	function ensureStyle() {
		let el = document.getElementById('buddyx-customizer-preview-css');
		if (!el) {
			el = document.createElement('style');
			el.id = 'buddyx-customizer-preview-css';
			document.head.appendChild(el);
		}
		return el;
	}

	function setCss(settingId, css) {
		const el = ensureStyle();
		const re = new RegExp('/\\*\\s*' + settingId + '\\s*\\*/[\\s\\S]*?/\\*\\s*end\\s*\\*/');
		const block = '/* ' + settingId + ' */' + css + '/* end */';
		el.textContent = re.test(el.textContent) ? el.textContent.replace(re, block) : el.textContent + block;
	}

	/**
	 * Build CSS for a Typography object value (matches PHP Output_Builder).
	 */
	function typographyCss(element, val) {
		// Kirki legacy 'variant' -> font-weight (+ font-style for italic combos)
		if (val.variant && !val['font-weight']) {
			let v = String(val.variant).toLowerCase();
			let style = '';
			if (v.indexOf('italic') !== -1) {
				style = 'italic';
				v = v.replace('italic', '').trim();
			}
			if (v === 'regular' || v === '') {
				v = '400';
			} else if (v === 'bold') {
				v = '700';
			}
			val['font-weight'] = v;
			if (style && !val['font-style']) {
				val['font-style'] = style;
			}
		}

		const map = {
			'font-family': 'font-family',
			'font-weight': 'font-weight',
			'font-size': 'font-size',
			'line-height': 'line-height',
			'letter-spacing': 'letter-spacing',
			'text-transform': 'text-transform',
			'font-style': 'font-style',
		};
		let decls = '';
		Object.entries(map).forEach(([k, p]) => {
			if (val[k]) {
				decls += p + ':' + val[k] + ';';
			}
		});
		return decls ? element + '{' + decls + '}' : '';
	}

	if (window.buddyxCustomizerOutputs) {
		Object.entries(window.buddyxCustomizerOutputs).forEach(([settingId, rules]) => {
			wp.customize(settingId, (value) => {
				value.bind((newVal) => {
					let css = '';
					rules.forEach((r) => {
						if (newVal && typeof newVal === 'object' && !Array.isArray(newVal)) {
							// Typography (or any structured value) — emit multi-property block
							css += typographyCss(r.element, newVal);
						} else if (newVal !== '' && newVal !== null && typeof newVal !== 'undefined') {
							const property = r.property || 'color';
							const units = r.units || '';
							css += r.element + '{' + property + ':' + newVal + units + ';}';
						}
					});
					setCss(settingId, css);
				});
			});
		});
	}
})();
