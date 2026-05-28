/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

interface BindCallback<T> {
	(to: T): void;
}

interface CustomizeValue<T> {
	bind: (callback: BindCallback<T>) => void;
	get?: () => T;
}

interface WPCustomize {
	<T>(id: string, callback: (value: CustomizeValue<T>) => void): void;
}

// Extend Window interface properly
declare global {
	interface Window {
		wp: { customize: WPCustomize };
		buddyxCustomizerDefaults?: Record<string, string>;
	}
}

// This export makes the file a module and allows declare global to work
export {};

/**
 * Registered field defaults injected from PHP (Customizer/Component.php).
 * When a setting's effective value still matches its default, the live-
 * preview skip writing an inline `!important` declaration on <body> so the
 * style-variation overlay continues to drive that token.
 */
const fieldDefaults: Record<string, string> =
	window.buddyxCustomizerDefaults || {};

/**
 * Normalize a CSS color string to a canonical lowercase 6-digit hex (or
 * "rgba()" for alpha values) so registered defaults declared in `#ef5455`
 * still compare equal to live-preview values that arrive as `rgb(239, 84,
 * 85)` or `rgba(239,84,85,1)`.
 * @param value
 */
function canonicalizeColor(value: string): string {
	const v = value.trim().toLowerCase();
	if (!v) {
		return '';
	}
	if (v.startsWith('#')) {
		const hex = v.slice(1);
		if (hex.length === 3) {
			return (
				'#' +
				hex
					.split('')
					.map((c) => c + c)
					.join('')
			);
		}
		if (hex.length === 6 || hex.length === 8) {
			return '#' + hex;
		}
		return v;
	}
	const rgb = v.match(
		/^rgba?\(\s*(\d+)[\s,]+(\d+)[\s,]+(\d+)(?:[\s,/]+([\d.]+))?\s*\)$/
	);
	if (rgb) {
		const r = parseInt(rgb[1], 10);
		const g = parseInt(rgb[2], 10);
		const b = parseInt(rgb[3], 10);
		const a = rgb[4] !== undefined ? parseFloat(rgb[4]) : 1;
		if (a === 1) {
			const toHex = (n: number) => n.toString(16).padStart(2, '0');
			return '#' + toHex(r) + toHex(g) + toHex(b);
		}
		return `rgba(${r},${g},${b},${a})`;
	}
	return v;
}

function valueMatchesRegisteredDefault(
	settingId: string,
	value: string
): boolean {
	const fallback = fieldDefaults[settingId];
	if (!fallback || !value) {
		return false;
	}
	return canonicalizeColor(value) === canonicalizeColor(fallback);
}

// Helper functions
function setTextContent(selector: string, text: string): void {
	const elements = document.querySelectorAll(selector);
	elements.forEach((element) => {
		element.textContent = text;
	});
}

function setStyle(
	selector: string,
	styles: Partial<CSSStyleDeclaration>
): void {
	const elements = document.querySelectorAll(selector);
	elements.forEach((element) => {
		// Cast zu HTMLElement für style-Zugriff
		Object.assign((element as HTMLElement).style, styles);
	});
}

function setBodyCssVariable(variableName: string, value?: string): void {
	const body = document.body;

	if (!body) {
		return;
	}

	if (value && value.length > 0) {
		body.style.setProperty(variableName, value, 'important');
		return;
	}

	body.style.removeProperty(variableName);
}

// Each setting maps to AN ARRAY of CSS variable names that need updating.
// Pre-5.1.0 master used legacy `--color-*` / `--button-*` / `--global-*`
// names. 5.1.0 introduces canonical `--bx-color-*` tokens at :root which
// the new bx-tokens-applied.css rules read from. To keep live preview
// working for both legacy plugin-compat CSS AND new token-driven CSS,
// the customizer JS sets BOTH variable names on every change.
const dynamicColorVars: Record<string, string[]> = {
	site_loader_bg: ['--bx-color-loader-bg', '--color-theme-loader'],
	'site_title_typography_option[color]': [
		'--bx-color-site-title',
		'--color-site-title',
	],
	site_title_hover_color: [
		'--bx-color-site-title-hover',
		'--color-site-title-hover',
	],
	'site_tagline_typography_option[color]': [
		'--bx-color-site-tagline',
		'--color-site-tagline',
	],
	site_header_bg_color: ['--bx-color-header-bg', '--color-header-bg'],
	'menu_typography_option[color]': ['--bx-color-menu-fg', '--color-menu'],
	menu_hover_color: ['--bx-color-menu-hover', '--color-menu-hover'],
	menu_active_color: ['--bx-color-menu-active', '--color-menu-active'],
	body_background_color: ['--bx-color-bg', '--color-theme-body'],
	'typography_option[color]': ['--bx-color-fg', '--global-font-color'],
	content_background_color: ['--bx-color-bg-page', '--color-layout-boxed'],
	box_background_color: ['--bx-color-bg-elevated', '--color-theme-white-box'],
	secondary_background_color: [
		'--bx-color-bg-muted',
		'--global-body-lightcolor',
	],
	site_primary_color: ['--bx-color-accent', '--color-theme-primary'],
	site_links_color: ['--bx-color-link', '--color-link'],
	site_links_focus_hover_color: [
		'--bx-color-link-hover',
		'--color-link-hover',
	],
	'h1_typography_option[color]': ['--bx-color-h1', '--color-h1'],
	'h2_typography_option[color]': ['--bx-color-h2', '--color-h2'],
	'h3_typography_option[color]': ['--bx-color-h3', '--color-h3'],
	'h4_typography_option[color]': ['--bx-color-h4', '--color-h4'],
	'h5_typography_option[color]': ['--bx-color-h5', '--color-h5'],
	'h6_typography_option[color]': ['--bx-color-h6', '--color-h6'],
	site_buttons_background_color: [
		'--bx-color-button-bg',
		'--button-background-color',
	],
	site_buttons_background_hover_color: [
		'--bx-color-button-bg-hover',
		'--button-background-hover-color',
	],
	site_buttons_text_color: ['--bx-color-button-fg', '--button-text-color'],
	site_buttons_text_hover_color: [
		'--bx-color-button-fg-hover',
		'--button-text-hover-color',
	],
	site_buttons_border_color: [
		'--bx-color-button-border',
		'--button-border-color',
	],
	site_buttons_border_hover_color: [
		'--bx-color-button-border-hover',
		'--button-border-hover-color',
	],
	site_footer_title_color: [
		'--bx-color-footer-title',
		'--color-footer-title',
	],
	site_footer_content_color: [
		'--bx-color-footer-fg',
		'--color-footer-content',
	],
	site_footer_links_color: ['--bx-color-footer-link', '--color-footer-link'],
	site_footer_links_hover_color: [
		'--bx-color-footer-link-hover',
		'--color-footer-link-hover',
	],
	site_copyright_background_color: [
		'--bx-color-copyright-bg',
		'--color-copyright-bg',
	],
	site_copyright_content_color: [
		'--bx-color-copyright-fg',
		'--color-copyright-content',
	],
	site_copyright_links_color: [
		'--bx-color-copyright-link',
		'--color-copyright-link',
	],
	site_copyright_links_hover_color: [
		'--bx-color-copyright-link-hover',
		'--color-copyright-link-hover',
	],
};

const dynamicDimensionVars: Record<string, string> = {
	site_global_border_radius: '--global-border-radius',
	site_button_border_radius: '--button-border-radius',
	site_form_border_radius: '--form-border-radius',
};

let customColorsEnabled = true;

function updateDynamicColorVariable(settingId: string, value: string): void {
	const variableNames = dynamicColorVars[settingId];

	if (!variableNames) {
		return;
	}

	// Each setting maps to multiple CSS variable names (new bx-color tokens
	// + legacy aliases for plugin compat). Update all of them on every
	// change so live preview matches the saved-CSS output exactly.
	if (!customColorsEnabled) {
		variableNames.forEach((name) => setBodyCssVariable(name));
		return;
	}

	// When the value still equals the registered field default the variation
	// overlay should remain in control of the token. Removing any prior
	// inline declaration on <body> lets the :root cascade resolve.
	if (valueMatchesRegisteredDefault(settingId, value)) {
		variableNames.forEach((name) => setBodyCssVariable(name));
		return;
	}

	variableNames.forEach((name) => setBodyCssVariable(name, value));
}

function syncAllDynamicColorVariables(): void {
	Object.keys(dynamicColorVars).forEach((settingId) => {
		window.wp.customize<string>(settingId, function (value) {
			updateDynamicColorVariable(
				settingId,
				typeof value.get === 'function' ? value.get() : ''
			);
		});
	});
}

function bindBodyCssVariable(settingId: string, variableName: string): void {
	window.wp.customize<string>(settingId, function (value) {
		value.bind(function (to) {
			setBodyCssVariable(variableName, to);
		});
	});
}

window.wp.customize<boolean | string>('site_custom_colors', function (value) {
	customColorsEnabled = !(
		typeof value.get === 'function' &&
		(value.get() === false ||
			value.get() === '0' ||
			value.get() === 'off' ||
			value.get() === '')
	);
	syncAllDynamicColorVariables();

	value.bind(function (to) {
		customColorsEnabled = !(
			to === false ||
			to === '0' ||
			to === 'off' ||
			to === ''
		);
		syncAllDynamicColorVariables();
	});
});

Object.keys(dynamicColorVars).forEach((settingId) => {
	window.wp.customize<string>(settingId, function (value) {
		value.bind(function (to) {
			updateDynamicColorVariable(settingId, to);
		});
	});
});

Object.entries(dynamicDimensionVars).forEach(([settingId, variableName]) => {
	bindBodyCssVariable(settingId, variableName);
});

// Site title and description.
window.wp.customize<string>('blogname', function (value) {
	value.bind(function (to) {
		setTextContent('.site-title a', to);
	});
});

window.wp.customize<string>('blogdescription', function (value) {
	value.bind(function (to) {
		setTextContent('.site-description', to);
	});
});

// Header text color.
window.wp.customize<string>('header_textcolor', function (value) {
	value.bind(function (to) {
		if ('blank' === to) {
			setStyle('.site-title, .site-description', {
				clipPath: 'inset(1px)',
				position: 'absolute',
			});
		} else {
			setStyle('.site-title, .site-description', {
				clipPath: 'none',
				position: 'relative',
			});
			setStyle('.site-title a, .site-description', {
				color: to,
			});
		}
	});
});
