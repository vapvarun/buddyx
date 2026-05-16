<?php
/**
 * Skin/Color Customizer Fields
 *
 * Phase 3 (5.1.0) UX overhaul: the 45 color fields are organized into 9
 * navigable clusters (Mode & Master / Brand / Header / Surfaces / Text & Links
 * / Headings / Buttons / Footer / Copyright). Cluster heads use Lucide-style
 * inline SVG icons in a structured div, replacing the older `<hr>` dividers.
 *
 * Setting IDs and value shapes are unchanged — pure UX. Customer-saved values
 * flow through unmodified.
 *
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'buddyx_skin_cluster_icon' ) ) {
	/**
	 * Inline Lucide-style SVG (24x24, stroke 2, currentColor) for a cluster head.
	 * SVG markup is whitelisted in Custom_HTML's wp_kses allowed_html.
	 */
	function buddyx_skin_cluster_icon( $name ) {
		$open = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
		$close = '</svg>';
		switch ( $name ) {
			case 'moon':
				return $open . '<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>' . $close;
			case 'flame':
				return $open . '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>' . $close;
			case 'panel-top':
				return $open . '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/>' . $close;
			case 'layers':
				return $open . '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>' . $close;
			case 'type':
				return $open . '<polyline points="4 7 4 4 20 4 20 7"/><line x1="9" x2="15" y1="20" y2="20"/><line x1="12" x2="12" y1="4" y2="20"/>' . $close;
			case 'heading':
				return $open . '<path d="M6 12h12"/><path d="M6 20V4"/><path d="M18 20V4"/>' . $close;
			case 'mouse-pointer-2':
				return $open . '<path d="M4.037 4.688a.495.495 0 0 1 .651-.651l16 6.5a.5.5 0 0 1-.063.947l-6.124 1.58a2 2 0 0 0-1.438 1.435l-1.579 6.126a.5.5 0 0 1-.947.063z"/>' . $close;
			case 'panel-bottom':
				return $open . '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 15h18"/>' . $close;
			case 'copyright':
				return $open . '<circle cx="12" cy="12" r="10"/><path d="M14.83 14.83a4 4 0 1 1 0-5.66"/>' . $close;
		}
		return '';
	}
}

if ( ! function_exists( 'buddyx_skin_cluster_head' ) ) {
	/**
	 * Build a cluster-head div for the Site Skin section.
	 *
	 * @param string $key     Lowercase cluster key for `data-cluster=""`.
	 * @param string $title   Cluster label (already-translated).
	 * @param string $caption Sub-line caption (already-translated).
	 * @param string $icon    Inline SVG markup (returned by buddyx_skin_cluster_icon).
	 * @return string
	 */
	function buddyx_skin_cluster_head( $key, $title, $caption, $icon ) {
		return sprintf(
			'<div class="bx-cluster-head" data-cluster="%1$s">' .
				'<span class="bx-cluster-head__icon" aria-hidden="true">%4$s</span>' .
				'<div class="bx-cluster-head__text">' .
					'<span class="bx-cluster-head__title">%2$s</span>' .
					'<span class="bx-cluster-head__caption">%3$s</span>' .
				'</div>' .
			'</div>',
			esc_attr( $key ),
			esc_html( $title ),
			esc_html( $caption ),
			$icon
		);
	}
}

if ( ! function_exists( 'buddyx_skin_subcluster_head' ) ) {
	/**
	 * Build a sub-cluster head (smaller, used inside the Header cluster).
	 *
	 * @param string $title Sub-cluster label (already-translated).
	 * @return string
	 */
	function buddyx_skin_subcluster_head( $title ) {
		return sprintf(
			'<div class="bx-subcluster-head"><span class="bx-subcluster-head__title">%s</span></div>',
			esc_html( $title )
		);
	}
}

// Active-callback array reused by every field that respects the master toggle.
$buddyx_skin_master_gate = array(
	array(
		'setting'  => 'site_custom_colors',
		'operator' => '==',
		'value'    => true,
	),
);

/* =====================================================================
 * Cluster 1 — Mode & Master  (priority 5–7)
 * Always visible; the master toggle lives here so its head must render
 * even when `site_custom_colors` is off.
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings' => 'custom-mode-divider',
		'label'    => '',
		'section'  => 'site_skin_section',
		'priority' => 5,
		'default'  => buddyx_skin_cluster_head(
			'mode',
			esc_html__( 'Mode & Master', 'buddyx' ),
			esc_html__( 'Color mode and the master switch for custom colors', 'buddyx' ),
			buddyx_skin_cluster_icon( 'moon' )
		),
	)
);

// Color mode (light / dark / auto). New in 5.1.0. The dark token set
// is supplied by inc/Tokens/Component.php with WCAG AA defaults; an
// inline <head> bootstrap script applies the visitor's choice to
// <html data-bx-mode="..."> before render to prevent FOUC.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_buttonset',
	array(
		'settings'    => 'site_color_mode',
		'label'       => esc_html__( 'Color mode', 'buddyx' ),
		'description' => esc_html__( 'Default look for new visitors.', 'buddyx' ),
		'tooltip'     => esc_html__( 'Auto matches the visitor\'s device theme. Light or Dark keeps the site on one look for everyone.', 'buddyx' ),
		'section'     => 'site_skin_section',
		'default'     => 'light',
		'priority'    => 6,
		'choices'     => array(
			'light' => esc_html__( 'Light', 'buddyx' ),
			'dark'  => esc_html__( 'Dark', 'buddyx' ),
			'auto'  => esc_html__( 'Auto', 'buddyx' ),
		),
		'transport'   => 'refresh',
	)
);

// Visitor-facing color-mode toggle. When enabled, a button renders in
// the header (and optionally mobile menu) that lets visitors cycle
// light → dark → auto. Choice persists via localStorage so it survives
// page navigations and tab reloads. Works for guests AND logged-in
// members — no auth dependency.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
	array(
		'settings'    => 'site_color_mode_toggle_show',
		'label'       => esc_html__( 'Show color-mode toggle', 'buddyx' ),
		'description' => esc_html__( 'Let visitors switch the look.', 'buddyx' ),
		'tooltip'     => esc_html__( 'Adds a sun and moon button so visitors can pick Light, Dark, or match their device. Turn off to keep the look you set above.', 'buddyx' ),
		'section'     => 'site_skin_section',
		'default'     => 'on',
		'priority'    => 6,
		'choices'     => array(
			'on'  => esc_html__( 'Show', 'buddyx' ),
			'off' => esc_html__( 'Hide', 'buddyx' ),
		),
		'transport'   => 'refresh',
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_buttonset',
	array(
		'settings'        => 'site_color_mode_toggle_position',
		'label'           => esc_html__( 'Toggle position', 'buddyx' ),
		'description'     => esc_html__( 'Where the button appears.', 'buddyx' ),
		'tooltip'         => esc_html__( 'Header places it next to the menu icons. Mobile shows it only in the mobile menu. Both shows it in each.', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => 'both',
		'priority'        => 6,
		'choices'         => array(
			'header'      => esc_html__( 'Header', 'buddyx' ),
			'mobile_only' => esc_html__( 'Mobile', 'buddyx' ),
			'both'        => esc_html__( 'Both', 'buddyx' ),
		),
		'transport'       => 'refresh',
		'active_callback' => array(
			array(
				'setting'  => 'site_color_mode_toggle_show',
				'operator' => '==',
				'value'    => 'on',
			),
		),
	)
);

// Style variation. New in 5.1.0. Each variation is a styles/<slug>.json
// file shipping a palette + select typography overrides. When customer
// picks one, Tokens/Component.php reads the variation's palette and
// resolves --bx-color-* tokens against it. Per-customizer color saves
// still win over the variation's defaults — variation provides the
// "starting point" palette, customizer is the "fine tune" layer.
//
// Rendered as a swatch grid (radio_image with [image, label] choices) so
// the customer sees each variation's palette before picking, instead of
// guessing from a slug name.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'radio_image',
	array(
		'settings'    => 'site_style_variation',
		'label'       => esc_html__( 'Style preset', 'buddyx' ),
		'description' => esc_html__( 'Pick a ready-made starting look.', 'buddyx' ),
		'tooltip'     => esc_html__( 'Each card previews the preset\'s palette. Any colors you change below still take priority over the preset.', 'buddyx' ),
		'section'     => 'site_skin_section',
		'default'     => '',
		'priority'    => 7,
		'choices'     => buddyx_get_style_variation_swatch_choices(),
		'transport'   => 'refresh',
	)
);

/**
 * Build the radio_image `[ image, label ]` choices map for the Style
 * preset picker. Each card points at a static SVG file in
 * `assets/images/presets/<slug>.svg` so esc_url() passes the path through
 * unchanged and the browser can cache them. Mirrors buddyx-pro's
 * Color_Presets::get_swatch_choices shape so cross-theme docs cover both.
 *
 * Regenerate the SVG files via tools/generate-preset-swatches.php when
 * a variation's palette changes.
 *
 * @return array
 */
function buddyx_get_style_variation_swatch_choices() {
	$base_url = trailingslashit( get_template_directory_uri() ) . 'assets/images/presets/';

	$choices = array(
		'' => array(
			'image' => $base_url . 'default.svg',
			'label' => esc_html__( 'Default', 'buddyx' ),
		),
	);

	$labels = array(
		'cool'       => __( 'Cool', 'buddyx' ),
		'dark'       => __( 'Dark', 'buddyx' ),
		'editorial'  => __( 'Editorial', 'buddyx' ),
		'minimal'    => __( 'Minimal', 'buddyx' ),
		'monochrome' => __( 'Monochrome', 'buddyx' ),
		'pastel'     => __( 'Pastel', 'buddyx' ),
		'vibrant'    => __( 'Vibrant', 'buddyx' ),
		'warm'       => __( 'Warm', 'buddyx' ),
	);

	foreach ( $labels as $slug => $label ) {
		$choices[ $slug ] = array(
			'image' => $base_url . $slug . '.svg',
			'label' => $label,
		);
	}

	return $choices;
}

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'switch',
	array(
		'settings' => 'site_custom_colors',
		'label'    => esc_html__( 'Set Custom Colors?', 'buddyx' ),
		'section'  => 'site_skin_section',
		'default'  => 'on',
		'priority' => 7,
		'choices'  => array(
			'on'  => esc_html__( 'Yes', 'buddyx' ),
			'off' => esc_html__( 'No', 'buddyx' ),
		),
	)
);

// NOTE: 'custom-loader-divider' + 'site_loader_bg' moved to the
// dedicated Site Loader section in General_Fields.php (5.1.0).
// Setting ID `site_loader_bg` preserved — customers see their saved
// value carry over to the new location with no manual action.

/* =====================================================================
 * Cluster 2 — Brand  (priority 10–11)
 * The single most-used color (theme/accent) gets prominence as its
 * own cluster, extracted from the older lump-Body group.
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-brand-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 10,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'brand',
			esc_html__( 'Brand', 'buddyx' ),
			esc_html__( 'Primary accent used across links, buttons, and highlights', 'buddyx' ),
			buddyx_skin_cluster_icon( 'flame' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_primary_color',
		'label'           => esc_html__( 'Theme Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 11,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 3 — Header  (priority 20–31)
 * 8 fields across 3 sub-clusters (Header surface / Site title / Menu).
 * Subheader title color relocated from old Body cluster — it belongs
 * with the rest of the header-area colors.
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-header-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 20,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'header',
			esc_html__( 'Header', 'buddyx' ),
			esc_html__( 'Logo, navigation, and the top of every page', 'buddyx' ),
			buddyx_skin_cluster_icon( 'panel-top' )
		),
	)
);

// Sub-cluster: Header surface.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-header-surface-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 21,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_subcluster_head( esc_html__( 'Header surface', 'buddyx' ) ),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_header_bg_color',
		'label'           => esc_html__( 'Header Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ffffff',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 22,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

// Sub-cluster: Site title.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-header-title-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 23,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_subcluster_head( esc_html__( 'Site title', 'buddyx' ) ),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_title_typography_option[color]',
		'label'           => esc_html__( 'Site Title Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 24,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_title_hover_color',
		'label'           => esc_html__( 'Site Title Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 25,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_tagline_typography_option[color]',
		'label'           => esc_html__( 'Site Tagline Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#757575',
		'priority'        => 26,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

// Sub-cluster: Menu.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-header-menu-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 27,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_subcluster_head( esc_html__( 'Menu', 'buddyx' ) ),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'menu_typography_option[color]',
		'label'           => esc_html__( 'Menu Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 28,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'menu_hover_color',
		'label'           => esc_html__( 'Menu Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 29,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'menu_active_color',
		'label'           => esc_html__( 'Menu Active Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 30,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

// Subheader title color — relocated from old Body cluster (Phase 3 UX).
// Setting ID `site_sub_header_typography[color]` is unchanged.
\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_sub_header_typography[color]',
		'label'           => esc_html__( 'Subheader Title Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 31,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 4 — Surfaces  (priority 35–39)
 * Background colors that paint big areas of the page.
 * The 'custom-body-divider' setting ID is preserved for backward
 * compatibility (it was always a Custom_HTML control with no stored
 * value, so the rename only affects label/markup).
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-body-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 35,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'surfaces',
			esc_html__( 'Surfaces', 'buddyx' ),
			esc_html__( 'Background colors for the page, content area, and cards', 'buddyx' ),
			buddyx_skin_cluster_icon( 'layers' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'body_background_color',
		'label'           => esc_html__( 'Body Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#f7f7f9',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 36,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'content_background_color',
		'label'           => esc_html__( 'Content Background Color', 'buddyx' ),
		'description'     => esc_html__( 'Note: This setting will only be used if the box layout is selected.', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#f7f7f9',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 37,
		'active_callback' => array(
			array(
				'setting' => 'site_layout',
				'value'   => 'boxed',
			),
			array(
				'setting'  => 'site_custom_colors',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'box_background_color',
		'label'           => esc_html__( 'Box Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ffffff',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 38,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'secondary_background_color',
		'label'           => esc_html__( 'Secondary Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#fafafa',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 39,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 5 — Text & Links  (priority 45–48)
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-textlinks-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 45,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'text-links',
			esc_html__( 'Text & Links', 'buddyx' ),
			esc_html__( 'Body text and inline link colors', 'buddyx' ),
			buddyx_skin_cluster_icon( 'type' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'typography_option[color]',
		'label'           => esc_html__( 'Body Text Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#505050',
		'priority'        => 46,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_links_color',
		'label'           => esc_html__( 'Link Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 47,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_links_focus_hover_color',
		'label'           => esc_html__( 'Link Hover', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 48,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 6 — Headings  (priority 50–56)
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-headings-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 50,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'headings',
			esc_html__( 'Headings', 'buddyx' ),
			esc_html__( 'H1 through H6 — section titles across posts, pages, and widgets', 'buddyx' ),
			buddyx_skin_cluster_icon( 'heading' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h1_typography_option[color]',
		'label'           => esc_html__( 'H1 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 51,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h2_typography_option[color]',
		'label'           => esc_html__( 'H2 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 52,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h3_typography_option[color]',
		'label'           => esc_html__( 'H3 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 53,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h4_typography_option[color]',
		'label'           => esc_html__( 'H4 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 54,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h5_typography_option[color]',
		'label'           => esc_html__( 'H5 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 55,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'h6_typography_option[color]',
		'label'           => esc_html__( 'H6 Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'priority'        => 56,
		'choices'         => array( 'alpha' => true ),
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 7 — Buttons  (priority 60–66)
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-button-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 60,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'buttons',
			esc_html__( 'Buttons', 'buddyx' ),
			esc_html__( 'Background, text, and border in default and hover states', 'buddyx' ),
			buddyx_skin_cluster_icon( 'mouse-pointer-2' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_background_color',
		'label'           => esc_html__( 'Button Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 61,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_background_hover_color',
		'label'           => esc_html__( 'Button Background Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#f83939',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 62,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_text_color',
		'label'           => esc_html__( 'Button Text Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ffffff',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 63,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_text_hover_color',
		'label'           => esc_html__( 'Button Text Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ffffff',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 64,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_border_color',
		'label'           => esc_html__( 'Button Border Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 65,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_buttons_border_hover_color',
		'label'           => esc_html__( 'Button Border Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#f83939',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 66,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 8 — Footer  (priority 70–74)
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-footer-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 70,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'footer',
			esc_html__( 'Footer', 'buddyx' ),
			esc_html__( 'Widget area at the bottom of every page', 'buddyx' ),
			buddyx_skin_cluster_icon( 'panel-bottom' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_footer_title_color',
		'label'           => esc_html__( 'Footer Title Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 71,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_footer_content_color',
		'label'           => esc_html__( 'Footer Content Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#505050',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 72,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_footer_links_color',
		'label'           => esc_html__( 'Footer Link Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 73,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_footer_links_hover_color',
		'label'           => esc_html__( 'Footer Link Hover', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 74,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

/* =====================================================================
 * Cluster 9 — Copyright  (priority 80–85)
 * Setting ID `custom-coyright-divider` (sic — original misspelling) is
 * preserved for backward-compatibility; it stores no value so the typo
 * is harmless and renaming would risk doubling the field.
 * ===================================================================== */

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'custom',
	array(
		'settings'        => 'custom-coyright-divider',
		'label'           => '',
		'section'         => 'site_skin_section',
		'priority'        => 80,
		'active_callback' => $buddyx_skin_master_gate,
		'default'         => buddyx_skin_cluster_head(
			'copyright',
			esc_html__( 'Copyright', 'buddyx' ),
			esc_html__( 'Copyright bar shown below the footer widgets', 'buddyx' ),
			buddyx_skin_cluster_icon( 'copyright' )
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_copyright_background_color',
		'label'           => esc_html__( 'Copyright Background Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ffffff',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 81,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_copyright_border_color',
		'label'           => esc_html__( 'Copyright Border Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#e8e8e8',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 82,
		'active_callback' => $buddyx_skin_master_gate,
		'output'          => array(
			array(
				'element'  => '.site-info',
				'property' => 'border-color',
			),
		),
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_copyright_content_color',
		'label'           => esc_html__( 'Copyright Content Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#505050',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 83,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_copyright_links_color',
		'label'           => esc_html__( 'Copyright Link Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#111111',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 84,
		'active_callback' => $buddyx_skin_master_gate,
	)
);

\BuddyX\Buddyx\Customizer_Framework\Field::add( 'color',
	array(
		'settings'        => 'site_copyright_links_hover_color',
		'label'           => esc_html__( 'Copyright Link Hover Color', 'buddyx' ),
		'section'         => 'site_skin_section',
		'default'         => '#ef5455',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 85,
		'active_callback' => $buddyx_skin_master_gate,
	)
);
