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
			'text-align': 'text-align',
			'text-decoration': 'text-decoration',
			'color': 'color',
		};
		let decls = '';
		Object.entries(map).forEach(([k, p]) => {
			if (val[k]) {
				decls += p + ':' + val[k] + ';';
			}
		});
		return decls ? element + '{' + decls + '}' : '';
	}

	/**
	 * Build CSS for a Background object value (matches PHP Output_Builder).
	 */
	function backgroundCss(element, val) {
		const keys = [
			'background-color',
			'background-image',
			'background-repeat',
			'background-position',
			'background-size',
			'background-attachment',
		];
		let decls = '';
		keys.forEach((k) => {
			if (!val[k]) {
				return;
			}
			const v = k === 'background-image' ? "url('" + val[k] + "')" : val[k];
			decls += k + ':' + v + ';';
		});
		return decls ? element + '{' + decls + '}' : '';
	}

	// site_color_mode is not an `output` (CSS) setting - it swaps the
	// data-bx-mode attribute on <html>, which drives the dark-token cascade
	// (:root[data-bx-mode="dark"]{...}, already present in the page). Bind it
	// directly so the preview reflects Light/Dark/Auto live without a refresh.
	wp.customize('site_color_mode', (value) => {
		value.bind((newVal) => {
			let mode = newVal === 'dark' || newVal === 'auto' ? newVal : 'light';
			// Dark style preset: 'auto' resolves to 'dark' here too, matching the
			// PHP bootstrap script. Variation tokens only live in the dark cascade,
			// so auto + light-OS would show the white framework defaults instead.
			if (mode === 'auto' && window.buddyxVariationIsDark) {
				mode = 'dark';
			}
			document.documentElement.setAttribute('data-bx-mode', mode);
		});
	});

	if (window.buddyxCustomizerOutputs) {
		Object.entries(window.buddyxCustomizerOutputs).forEach(([settingId, payload]) => {
			const type = payload && payload._type ? payload._type : '';
			const rules = (payload && payload.rules) || [];
			wp.customize(settingId, (value) => {
				value.bind((newVal) => {
					let css = '';
					rules.forEach((r) => {
						if (newVal && typeof newVal === 'object' && !Array.isArray(newVal)) {
							if (type === 'background') {
								css += backgroundCss(r.element, newVal);
							} else {
								css += typographyCss(r.element, newVal);
							}
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
