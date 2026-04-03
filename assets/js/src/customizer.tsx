/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

interface BindCallback< T > {
	( to: T ): void;
}

interface CustomizeValue< T > {
	bind: ( callback: BindCallback< T > ) => void;
	get?: () => T;
}

interface WPCustomize {
	< T >( id: string, callback: ( value: CustomizeValue< T > ) => void ): void;
}

// Extend Window interface properly
declare global {
	interface Window {
		wp: { customize: WPCustomize };
	}
}

// This export makes the file a module and allows declare global to work
export {};

// Helper functions
function setTextContent( selector: string, text: string ): void {
	const elements = document.querySelectorAll( selector );
	elements.forEach( ( element ) => {
		element.textContent = text;
	} );
}

function setStyle(
	selector: string,
	styles: Partial< CSSStyleDeclaration >
): void {
	const elements = document.querySelectorAll( selector );
	elements.forEach( ( element ) => {
		// Cast zu HTMLElement für style-Zugriff
		Object.assign( ( element as HTMLElement ).style, styles );
	} );
}

function setBodyCssVariable( variableName: string, value?: string ): void {
	const body = document.body;

	if ( ! body ) {
		return;
	}

	if ( value && value.length > 0 ) {
		body.style.setProperty( variableName, value, 'important' );
		return;
	}

	body.style.removeProperty( variableName );
}

const dynamicColorVars: Record< string, string > = {
	site_loader_bg: '--color-theme-loader',
	'site_title_typography_option[color]': '--color-site-title',
	site_title_hover_color: '--color-site-title-hover',
	'site_tagline_typography_option[color]': '--color-site-tagline',
	site_header_bg_color: '--color-header-bg',
	'menu_typography_option[color]': '--color-menu',
	menu_hover_color: '--color-menu-hover',
	menu_active_color: '--color-menu-active',
	body_background_color: '--color-theme-body',
	'typography_option[color]': '--global-font-color',
	content_background_color: '--color-layout-boxed',
	box_background_color: '--color-theme-white-box',
	secondary_background_color: '--global-body-lightcolor',
	site_primary_color: '--color-theme-primary',
	site_links_color: '--color-link',
	site_links_focus_hover_color: '--color-link-hover',
	'h1_typography_option[color]': '--color-h1',
	'h2_typography_option[color]': '--color-h2',
	'h3_typography_option[color]': '--color-h3',
	'h4_typography_option[color]': '--color-h4',
	'h5_typography_option[color]': '--color-h5',
	'h6_typography_option[color]': '--color-h6',
	site_buttons_background_color: '--button-background-color',
	site_buttons_background_hover_color: '--button-background-hover-color',
	site_buttons_text_color: '--button-text-color',
	site_buttons_text_hover_color: '--button-text-hover-color',
	site_buttons_border_color: '--button-border-color',
	site_buttons_border_hover_color: '--button-border-hover-color',
	site_footer_title_color: '--color-footer-title',
	site_footer_content_color: '--color-footer-content',
	site_footer_links_color: '--color-footer-link',
	site_footer_links_hover_color: '--color-footer-link-hover',
	site_copyright_background_color: '--color-copyright-bg',
	site_copyright_content_color: '--color-copyright-content',
	site_copyright_links_color: '--color-copyright-link',
	site_copyright_links_hover_color: '--color-copyright-link-hover',
};

const dynamicDimensionVars: Record< string, string > = {
	site_global_border_radius: '--global-border-radius',
	site_button_border_radius: '--button-border-radius',
	site_form_border_radius: '--form-border-radius',
};

let customColorsEnabled = true;

function updateDynamicColorVariable(
	settingId: string,
	value: string
): void {
	const variableName = dynamicColorVars[ settingId ];

	if ( ! variableName ) {
		return;
	}

	if ( ! customColorsEnabled ) {
		setBodyCssVariable( variableName );
		return;
	}

	setBodyCssVariable( variableName, value );
}

function syncAllDynamicColorVariables(): void {
	Object.keys( dynamicColorVars ).forEach( ( settingId ) => {
		window.wp.customize< string >( settingId, function ( value ) {
			updateDynamicColorVariable(
				settingId,
				typeof value.get === 'function' ? value.get() : ''
			);
		} );
	} );
}

function bindBodyCssVariable(
	settingId: string,
	variableName: string
): void {
	window.wp.customize< string >( settingId, function ( value ) {
		value.bind( function ( to ) {
			setBodyCssVariable( variableName, to );
		} );
	} );
}

window.wp.customize< boolean | string >( 'site_custom_colors', function ( value ) {
	customColorsEnabled = ! (
		typeof value.get === 'function' &&
		(
			value.get() === false ||
			value.get() === '0' ||
			value.get() === 'off' ||
			value.get() === ''
		)
	);
	syncAllDynamicColorVariables();

	value.bind( function ( to ) {
		customColorsEnabled = ! (
			to === false ||
			to === '0' ||
			to === 'off' ||
			to === ''
		);
		syncAllDynamicColorVariables();
	} );
} );

Object.entries( dynamicColorVars ).forEach( ( [ settingId, variableName ] ) => {
	window.wp.customize< string >( settingId, function ( value ) {
		value.bind( function ( to ) {
			updateDynamicColorVariable( settingId, to );
		} );
	} );
} );

Object.entries( dynamicDimensionVars ).forEach( ( [ settingId, variableName ] ) => {
	bindBodyCssVariable( settingId, variableName );
} );

// Site title and description.
window.wp.customize< string >( 'blogname', function ( value ) {
	value.bind( function ( to ) {
		setTextContent( '.site-title a', to );
	} );
} );

window.wp.customize< string >( 'blogdescription', function ( value ) {
	value.bind( function ( to ) {
		setTextContent( '.site-description', to );
	} );
} );

// Header text color.
window.wp.customize< string >( 'header_textcolor', function ( value ) {
	value.bind( function ( to ) {
		if ( 'blank' === to ) {
			setStyle( '.site-title, .site-description', {
				clipPath: 'inset(1px)',
				position: 'absolute',
			} );
		} else {
			setStyle( '.site-title, .site-description', {
				clipPath: 'none',
				position: 'relative',
			} );
			setStyle( '.site-title a, .site-description', {
				color: to,
			} );
		}
	} );
} );
