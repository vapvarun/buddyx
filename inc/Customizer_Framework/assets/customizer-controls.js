/**
 * BuddyX Customizer Framework — control JS bundle.
 *
 * Wires up dynamic UI for our custom controls. Loaded on customize.php only.
 *
 * Controls handled here:
 *   - buddyx-typography  6-input sync, JSON-encoded value
 *   - buddyx-switch      checkbox -> int sync
 *   - buddyx-dimension   number + unit -> '120px' string
 *   - buddyx-slider      range + number sync, unit suffix
 *   - buddyx-repeater    add/remove/reorder rows, JSON-encoded array
 *   - buddyx-sortable    drag-reorder + checkbox toggle, JSON-encoded array
 *
 * No JS needed for: buddyx-color (handled by parent class), buddyx-checkbox,
 * buddyx-radio-image, buddyx-radio-buttonset, buddyx-custom-html, buddyx-upload.
 *
 * @package buddyx
 */
(function () {
	'use strict';

	if (typeof wp === 'undefined' || !wp.customize) {
		return;
	}

	/**
	 * Typography control
	 */
	wp.customize.controlConstructor['buddyx-typography'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const root = ctl.container[0].querySelector('.buddyx-typography-controls');
			const hidden = ctl.container[0].querySelector('.buddyx-typo-value');
			if (!root || !hidden) {
				return;
			}
			const familyEl = root.querySelector('.buddyx-typo-family');
			const weightEl = root.querySelector('.buddyx-typo-weight');
			const styleEl = root.querySelector('.buddyx-typo-style');
			const sizeEl = root.querySelector('.buddyx-typo-size');
			const lhEl = root.querySelector('.buddyx-typo-line-height');
			const lsEl = root.querySelector('.buddyx-typo-letter-spacing');
			const ttEl = root.querySelector('.buddyx-typo-transform');
			const alignEl = root.querySelector('.buddyx-typo-align');
			const decorEl = root.querySelector('.buddyx-typo-decoration');

			// Populate family + weight selects from params.
			Object.entries(ctl.params.fontFamilies || {}).forEach(([slug, label]) => {
				familyEl.add(new Option(label, slug));
			});
			(ctl.params.weights || ['400']).forEach((w) => weightEl.add(new Option(w, w)));

			// Read current value from wp.customize state (WP doesn't auto-serialize
			// structured/object values to hidden inputs, so hidden.value is unreliable).
			// Merge over the field's PHP-declared default so partial saves (e.g.
			// only the [color] sub-key was set in another section) don't blank out
			// typography keys. Tolerant of Kirki legacy 'variant' key.
			const settingVal = ctl.setting.get();
			const defaultVal = ctl.params.default || {};
			const merged = Object.assign({}, defaultVal,
				(settingVal && typeof settingVal === 'object' && !Array.isArray(settingVal)) ? settingVal : {}
			);
			const initial = merged;
			// Kirki legacy 'variant' may bake italic into the value (e.g. '700italic')
			let rawVariant = String(initial['font-weight'] || initial.variant || '400').toLowerCase();
			let initialStyle = initial['font-style'] || 'normal';
			if (rawVariant.indexOf('italic') !== -1) {
				initialStyle = 'italic';
				rawVariant = rawVariant.replace('italic', '').trim() || '400';
			}
			const weightMap = { regular: '400', bold: '700', '': '400' };
			const initialWeight = weightMap[rawVariant] || rawVariant;

			familyEl.value = initial['font-family'] || familyEl.options[0]?.value || '';
			weightEl.value = initialWeight;
			if (styleEl) styleEl.value = initialStyle;
			sizeEl.value = parseFloat(initial['font-size']) || 16;
			lhEl.value = parseFloat(initial['line-height']) || 1.5;
			lsEl.value = parseFloat(initial['letter-spacing']) || 0;
			ttEl.value = initial['text-transform'] || 'none';
			if (alignEl) alignEl.value = initial['text-align'] || '';
			if (decorEl) decorEl.value = initial['text-decoration'] || '';
			// Push the hydrated value back into the setting so saving without
			// changes still emits a coherent shape (Kirki preserved this).
			hidden.value = JSON.stringify(initial);

			const sync = () => {
				// Merge over the current setting so foreign sub-keys (e.g. [color])
				// that other sections may own — survive untouched.
				const current = ctl.setting.get();
				const base = (current && typeof current === 'object' && !Array.isArray(current)) ? current : {};
				const v = Object.assign({}, base, {
					'font-family':     familyEl.value,
					'font-weight':     weightEl.value,
					'font-style':      styleEl ? styleEl.value : (base['font-style'] || 'normal'),
					'font-size':       sizeEl.value + 'px',
					'line-height':     String(lhEl.value),
					'letter-spacing':  lsEl.value + 'em',
					'text-transform':  ttEl.value,
					'text-align':      alignEl ? alignEl.value : (base['text-align'] || ''),
					'text-decoration': decorEl ? decorEl.value : (base['text-decoration'] || ''),
				});
				hidden.value = JSON.stringify(v);
				ctl.setting.set(v);
			};
			[familyEl, weightEl, styleEl, sizeEl, lhEl, lsEl, ttEl, alignEl, decorEl]
				.filter(Boolean)
				.forEach((el) => el.addEventListener('change', sync));
		},
	});

	/**
	 * Background composite control — 6 sub-inputs, structured-array value.
	 */
	wp.customize.controlConstructor['buddyx-background'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const root = ctl.container[0].querySelector('.buddyx-background-controls');
			const hidden = ctl.container[0].querySelector('.buddyx-bg-value');
			if (!root || !hidden) {
				return;
			}
			const colorEl = root.querySelector('.buddyx-bg-color');
			const imageEl = root.querySelector('.buddyx-bg-image');
			const pickBtn = root.querySelector('.buddyx-bg-image-pick');
			const repeatEl = root.querySelector('.buddyx-bg-repeat');
			const positionEl = root.querySelector('.buddyx-bg-position');
			const sizeEl = root.querySelector('.buddyx-bg-size');
			const attachmentEl = root.querySelector('.buddyx-bg-attachment');

			// Same as Typography: read from wp.customize state and merge over defaults
			// so partial saves don't blank out other sub-keys.
			const bgSettingVal = ctl.setting.get();
			const bgDefault = ctl.params.default || {};
			const initial = Object.assign({}, bgDefault,
				(bgSettingVal && typeof bgSettingVal === 'object' && !Array.isArray(bgSettingVal)) ? bgSettingVal : {}
			);
			colorEl.value = initial['background-color'] || '';
			imageEl.value = initial['background-image'] || '';
			repeatEl.value = initial['background-repeat'] || 'repeat';
			positionEl.value = initial['background-position'] || 'center center';
			sizeEl.value = initial['background-size'] || 'auto';
			attachmentEl.value = initial['background-attachment'] || 'scroll';
			hidden.value = JSON.stringify(initial);

			const sync = () => {
				const current = ctl.setting.get();
				const base = (current && typeof current === 'object' && !Array.isArray(current)) ? current : {};
				const v = Object.assign({}, base, {
					'background-color': colorEl.value,
					'background-image': imageEl.value,
					'background-repeat': repeatEl.value,
					'background-position': positionEl.value,
					'background-size': sizeEl.value,
					'background-attachment': attachmentEl.value,
				});
				hidden.value = JSON.stringify(v);
				ctl.setting.set(v);
			};
			[imageEl, repeatEl, positionEl, sizeEl, attachmentEl].forEach((el) =>
				el.addEventListener('change', sync)
			);

			// Premium UX: wire wp-color-picker (iris) onto the color sub-input
			// so it renders a swatch + popover picker, not a plain text input.
			if (jQuery.fn && jQuery.fn.wpColorPicker) {
				jQuery(colorEl).wpColorPicker({
					change: () => sync(),
					clear: () => sync(),
				});
			} else {
				colorEl.addEventListener('change', sync);
			}

			// Wire the WP media frame to the image URL input.
			if (pickBtn && wp.media) {
				let frame = null;
				pickBtn.addEventListener('click', (e) => {
					e.preventDefault();
					if (!frame) {
						frame = wp.media({
							title: 'Select Background Image',
							library: { type: 'image' },
							multiple: false,
						});
						frame.on('select', () => {
							const att = frame.state().get('selection').first().toJSON();
							imageEl.value = att.url || '';
							sync();
						});
					}
					frame.open();
				});
			}
		},
	});

	/**
	 * Switch / Toggle control
	 */
	wp.customize.controlConstructor['buddyx-switch'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const cb = ctl.container[0].querySelector('.buddyx-switch-input');
			if (!cb) {
				return;
			}
			cb.addEventListener('change', () => {
				ctl.setting.set(cb.checked ? 1 : 0);
			});
		},
	});

	/**
	 * Dimension control
	 */
	wp.customize.controlConstructor['buddyx-dimension'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const numEl = ctl.container[0].querySelector('.buddyx-dimension-number');
			const unitEl = ctl.container[0].querySelector('.buddyx-dimension-unit');
			const hidden = ctl.container[0].querySelector('.buddyx-dimension-value');
			if (!numEl || !unitEl || !hidden) {
				return;
			}
			const initial = String(hidden.value || '0px');
			const m = initial.match(/^(-?[\d.]+)(px|em|rem|%|vh|vw)?$/i);
			if (m) {
				numEl.value = m[1];
				unitEl.value = m[2] || 'px';
			}
			const sync = () => {
				const v = (numEl.value || '0') + (unitEl.value || 'px');
				hidden.value = v;
				ctl.setting.set(v);
			};
			numEl.addEventListener('change', sync);
			unitEl.addEventListener('change', sync);
		},
	});

	/**
	 * Slider control
	 */
	wp.customize.controlConstructor['buddyx-slider'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const wrap = ctl.container[0].querySelector('.buddyx-slider-controls');
			const rangeEl = ctl.container[0].querySelector('.buddyx-slider-range');
			const numberEl = ctl.container[0].querySelector('.buddyx-slider-number');
			const hidden = ctl.container[0].querySelector('.buddyx-slider-value');
			if (!wrap || !rangeEl || !numberEl || !hidden) {
				return;
			}
			const unit = wrap.dataset.unit || 'px';
			const settingVal = String(ctl.setting.get() || '');
			const m = settingVal.match(/^(-?[\d.]+)/);
			const initialNum = m ? m[1] : (rangeEl.min || '0');
			rangeEl.value = initialNum;
			numberEl.value = initialNum;
			hidden.value = initialNum + unit;

			// Premium UX: paint the gradient track to visually reflect the range value.
			const updateFill = () => {
				const min = parseFloat(rangeEl.min) || 0;
				const max = parseFloat(rangeEl.max) || 100;
				const val = parseFloat(rangeEl.value) || min;
				const pct = ((val - min) / (max - min)) * 100;
				rangeEl.style.setProperty('--bx-fill', pct + '%');
			};
			updateFill();

			const sync = (src) => {
				const n = src.value;
				rangeEl.value = n;
				numberEl.value = n;
				const v = n + unit;
				hidden.value = v;
				ctl.setting.set(v);
				updateFill();
			};
			rangeEl.addEventListener('input', () => sync(rangeEl));
			numberEl.addEventListener('change', () => sync(numberEl));
		},
	});

	/**
	 * Repeater control
	 */
	wp.customize.controlConstructor['buddyx-repeater'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const root = ctl.container[0].querySelector('.buddyx-repeater-rows');
			const hidden = ctl.container[0].querySelector('.buddyx-repeater-value');
			const addBtn = ctl.container[0].querySelector('.buddyx-repeater-add');
			if (!root || !hidden || !addBtn) {
				return;
			}
			const fields = ctl.params.fields || {};
			// Read array value from wp.customize state. Setting may hold either
			// an array (live) or a JSON-encoded string (post-save round-trip via
			// sanitize_json_array).
			let rows = ctl.setting.get();
			if (typeof rows === 'string') {
				try { rows = JSON.parse(rows); } catch (e) { rows = []; }
			}
			if (!Array.isArray(rows)) {
				rows = ctl.params.default || [];
				if (!Array.isArray(rows)) rows = [];
			}

			function renderRow(rowData) {
				const wrap = document.createElement('div');
				wrap.className = 'buddyx-repeater-row';
				const handle = document.createElement('span');
				handle.className = 'buddyx-repeater-handle';
				handle.setAttribute('aria-label', 'Drag to reorder');
				handle.textContent = '⋮⋮';
				wrap.appendChild(handle);

				Object.entries(fields).forEach(([key, fdef]) => {
					const label = document.createElement('label');
					const span = document.createElement('span');
					span.textContent = (fdef && fdef.label) || key;
					label.appendChild(span);
					const input = document.createElement('input');
					input.type = (fdef && fdef.type === 'url') ? 'url' : 'text';
					input.value = (rowData && rowData[key]) || '';
					input.dataset.key = key;
					input.addEventListener('change', sync);
					label.appendChild(input);
					wrap.appendChild(label);
				});

				const trash = document.createElement('button');
				trash.type = 'button';
				trash.className = 'buddyx-repeater-trash';
				trash.textContent = 'Remove';
				trash.addEventListener('click', () => {
					wrap.remove();
					sync();
				});
				wrap.appendChild(trash);

				return wrap;
			}

			function sync() {
				const out = [];
				root.querySelectorAll('.buddyx-repeater-row').forEach((rowEl) => {
					const o = {};
					rowEl.querySelectorAll('input[data-key]').forEach((i) => {
						o[i.dataset.key] = i.value;
					});
					out.push(o);
				});
				hidden.value = JSON.stringify(out);
				ctl.setting.set(out);
			}

			rows.forEach((r) => root.appendChild(renderRow(r)));
			addBtn.addEventListener('click', () => {
				root.appendChild(renderRow({}));
				sync();
			});

			if (window.jQuery && window.jQuery.fn.sortable) {
				window.jQuery(root).sortable({
					handle: '.buddyx-repeater-handle',
					update: sync,
				});
			}
		},
	});

	/**
	 * Sortable control
	 */
	wp.customize.controlConstructor['buddyx-sortable'] = wp.customize.Control.extend({
		ready: function () {
			const ctl = this;
			const root = ctl.container[0].querySelector('.buddyx-sortable-list');
			const hidden = ctl.container[0].querySelector('.buddyx-sortable-value');
			if (!root || !hidden) {
				return;
			}
			const choices = ctl.params.choices || {};
			// Read array from wp.customize state, not hidden input.
			let value = ctl.setting.get();
			if (typeof value === 'string') {
				try { value = JSON.parse(value); } catch (e) { value = []; }
			}
			if (!Array.isArray(value)) {
				value = ctl.params.default || [];
				if (!Array.isArray(value)) value = [];
			}
			// First-time render: synthesize from choices in declared order, all enabled.
			if (!value.length) {
				value = Object.keys(choices).map((slug) => ({ slug, enabled: true }));
			}

			value.forEach((item) => {
				const li = document.createElement('li');
				li.dataset.slug = item.slug;
				const handle = document.createElement('span');
				handle.className = 'buddyx-sortable-handle';
				handle.setAttribute('aria-label', 'Drag to reorder');
				handle.textContent = '⋮⋮';
				li.appendChild(handle);
				const lab = document.createElement('label');
				const cb = document.createElement('input');
				cb.type = 'checkbox';
				cb.checked = !!item.enabled;
				cb.addEventListener('change', sync);
				lab.appendChild(cb);
				const txt = document.createElement('span');
				txt.textContent = choices[item.slug] || item.slug;
				lab.appendChild(txt);
				li.appendChild(lab);
				root.appendChild(li);
			});

			function sync() {
				const out = [];
				root.querySelectorAll('li').forEach((li) => {
					out.push({
						slug: li.dataset.slug,
						enabled: !!li.querySelector('input[type="checkbox"]').checked,
					});
				});
				hidden.value = JSON.stringify(out);
				ctl.setting.set(out);
			}

			if (window.jQuery && window.jQuery.fn.sortable) {
				window.jQuery(root).sortable({
					handle: '.buddyx-sortable-handle',
					update: sync,
				});
			}
		},
	});

	/**
	 * Color control — alpha (rgba) extension.
	 *
	 * WP's bundled Iris/wp-color-picker does not support an alpha channel. When
	 * a Customizer Framework Color field is registered with
	 * `'choices' => array('alpha' => true)`, params.alpha is true and we render
	 * an extra opacity slider next to the picker. The value emitted to the
	 * customize setting is `rgba(r, g, b, a)`. Without alpha, the original
	 * WP_Customize_Color_Control behavior is preserved unchanged.
	 *
	 * Pairs with PHP-side Field::sanitize_color_alpha which preserves the
	 * rgba string on save (commit d77f114).
	 */
	(function () {
		var OriginalColor = wp.customize.controlConstructor.color;
		if (!OriginalColor) {
			return;
		}
		wp.customize.controlConstructor.color = OriginalColor.extend({
			ready: function () {
				var ctl = this;
				if (!ctl.params || !ctl.params.alpha) {
					OriginalColor.prototype.ready.apply(ctl, arguments);
					return;
				}
				var $ = window.jQuery;
				var $input = ctl.container.find('.color-picker-hex');
				var initial = String(ctl.setting.get() || '');
				var initialHex = initial;
				var initialAlpha = 1;
				var rgba = initial.match(/^rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)(?:\s*,\s*([\d.]+))?\s*\)$/i);
				if (rgba) {
					initialHex =
						'#' +
						[rgba[1], rgba[2], rgba[3]]
							.map(function (n) {
								return ('0' + parseInt(n, 10).toString(16)).slice(-2);
							})
							.join('');
					initialAlpha = rgba[4] !== undefined ? parseFloat(rgba[4]) : 1;
				}
				var $wrap = $('<div class="buddyx-color-alpha"></div>');
				var $label = $('<label class="buddyx-color-alpha-label">Opacity: <span class="buddyx-color-alpha-value"></span></label>');
				var $slider = $('<input type="range" min="0" max="1" step="0.01" class="buddyx-color-alpha-slider" />').val(initialAlpha);
				$wrap.append($label).append($slider);
				$input.closest('.customize-control-content').append($wrap);

				function hexToRgb(hex) {
					var h = String(hex || '').replace(/^#/, '');
					if (h.length === 3) {
						h = h[0] + h[0] + h[1] + h[1] + h[2] + h[2];
					}
					if (!/^[0-9a-f]{6}$/i.test(h)) {
						return null;
					}
					return [parseInt(h.slice(0, 2), 16), parseInt(h.slice(2, 4), 16), parseInt(h.slice(4, 6), 16)];
				}

				function computeOutput() {
					var hexVal = $input.val() || initialHex || '#000000';
					var rgb = hexToRgb(hexVal);
					var alphaVal = parseFloat($slider.val());
					if (isNaN(alphaVal)) {
						alphaVal = 1;
					}
					$wrap.find('.buddyx-color-alpha-value').text(Math.round(alphaVal * 100) + '%');
					if (!rgb) {
						return null;
					}
					return alphaVal >= 1
						? 'rgb(' + rgb[0] + ', ' + rgb[1] + ', ' + rgb[2] + ')'
						: 'rgba(' + rgb[0] + ', ' + rgb[1] + ', ' + rgb[2] + ', ' + alphaVal + ')';
				}

				function syncToSetting() {
					var out = computeOutput();
					if (out === null) {
						return;
					}
					// Don't write the same value back — that marks the setting
					// dirty on init and persists the default to theme_mods on
					// save, clobbering the style-variation overlay downstream.
					if (out === String(ctl.setting.get() || '')) {
						return;
					}
					ctl.setting.set(out);
				}

				$input.val(initialHex).wpColorPicker({
					change: function () {
						setTimeout(syncToSetting, 0);
					},
					clear: function () {
						ctl.setting.set('');
					},
				});
				$slider.on('input change', syncToSetting);
				// Update the alpha-value indicator label only — no setting.set()
				// on initial render. The picker is a passive observer of the
				// saved value until the customer actually changes it.
				computeOutput();
			},
		});
	})();
})();
