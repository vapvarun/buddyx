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
			const sizeEl = root.querySelector('.buddyx-typo-size');
			const lhEl = root.querySelector('.buddyx-typo-line-height');
			const lsEl = root.querySelector('.buddyx-typo-letter-spacing');
			const ttEl = root.querySelector('.buddyx-typo-transform');

			// Populate family + weight selects from params.
			Object.entries(ctl.params.fontFamilies || {}).forEach(([slug, label]) => {
				familyEl.add(new Option(label, slug));
			});
			(ctl.params.weights || ['400']).forEach((w) => weightEl.add(new Option(w, w)));

			// Read current value from the hidden field. Tolerant of Kirki legacy
			// 'variant' key (Output_Builder normalizes it on the server side too).
			let initial = {};
			try {
				initial = JSON.parse(hidden.value || '{}') || {};
			} catch (e) {
				initial = {};
			}
			const initialWeight = initial['font-weight'] || initial.variant || '400';
			familyEl.value = initial['font-family'] || familyEl.options[0]?.value || '';
			weightEl.value = String(initialWeight) === 'regular' ? '400' : String(initialWeight) === 'bold' ? '700' : String(initialWeight);
			sizeEl.value = parseFloat(initial['font-size'] || '16') || 16;
			lhEl.value = parseFloat(initial['line-height'] || '1.5') || 1.5;
			lsEl.value = parseFloat(initial['letter-spacing'] || '0') || 0;
			ttEl.value = initial['text-transform'] || 'none';

			const sync = () => {
				const v = {
					'font-family': familyEl.value,
					'font-weight': weightEl.value,
					'font-size': sizeEl.value + 'px',
					'line-height': String(lhEl.value),
					'letter-spacing': lsEl.value + 'em',
					'text-transform': ttEl.value,
				};
				hidden.value = JSON.stringify(v);
				ctl.setting.set(v);
			};
			[familyEl, weightEl, sizeEl, lhEl, lsEl, ttEl].forEach((el) => el.addEventListener('change', sync));
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
			const initial = String(hidden.value || '0' + unit);
			const m = initial.match(/^(-?[\d.]+)/);
			const initialNum = m ? m[1] : '0';
			rangeEl.value = initialNum;
			numberEl.value = initialNum;

			const sync = (src) => {
				const n = src.value;
				rangeEl.value = n;
				numberEl.value = n;
				const v = n + unit;
				hidden.value = v;
				ctl.setting.set(v);
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
			let rows = [];
			try {
				rows = JSON.parse(hidden.value || '[]');
				if (!Array.isArray(rows)) {
					rows = [];
				}
			} catch (e) {
				rows = [];
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
			let value = [];
			try {
				value = JSON.parse(hidden.value || '[]');
				if (!Array.isArray(value)) {
					value = [];
				}
			} catch (e) {
				value = [];
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
})();
