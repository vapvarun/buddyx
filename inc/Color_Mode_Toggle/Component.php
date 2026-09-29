<?php
/**
 * BuddyX\Buddyx\Color_Mode_Toggle\Component class
 *
 * Visitor-facing color-mode toggle (light / dark / auto) that pairs with
 * the dark-mode token plumbing in inc/Tokens/Component.php.
 *
 * Admin enables this in Customizer → Skin → "Show color-mode toggle".
 * When enabled, a button renders in the header (and/or mobile menu)
 * letting visitors cycle Light → Dark → Auto. Choice is persisted in
 * localStorage so it survives navigations and reloads. Works for guest
 * visitors and logged-in members alike — no auth dependency.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Color_Mode_Toggle;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function buddyx_is_truthy;
use function get_theme_mod;
use function wp_enqueue_script;

defined( 'ABSPATH' ) || exit;

/**
 * Color mode toggle component.
 */
class Component implements Component_Interface {

	/**
	 * Slug.
	 */
	public function get_slug(): string {
		return 'color_mode_toggle';
	}

	/**
	 * Hook in render points + asset enqueue.
	 */
	public function initialize(): void {
		add_action( 'buddyx_header_actions', array( $this, 'render_header_toggle' ), 50 );
		add_action( 'buddyx_mobile_menu_actions', array( $this, 'render_mobile_toggle' ), 50 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 30 );
		add_action( 'wp_footer', array( $this, 'render_dokan_dashboard_toggle' ) );
	}

	/**
	 * Whether the toggle is enabled site-wide.
	 *
	 * Customizer_Framework's `switch` field sanitizes the saved value to
	 * int 1/0 via sanitize_bool_int(), but legacy 5.0.x DBs (and the
	 * fresh-install fallback default) carry the literal string 'on'. Route
	 * through buddyx_is_truthy() so both shapes resolve correctly — matches
	 * inc/extra.php:238 which gates the same setting for header rendering.
	 */
	protected function is_enabled(): bool {
		return buddyx_is_truthy( get_theme_mod( 'site_color_mode_toggle_show', 'on' ) );
	}

	/**
	 * Position setting (header | mobile_only | both).
	 */
	protected function position(): string {
		$pos = (string) get_theme_mod( 'site_color_mode_toggle_position', 'both' );
		return in_array( $pos, array( 'header', 'mobile_only', 'both' ), true ) ? $pos : 'both';
	}

	/**
	 * Server-side initial mode (matches site default; client may override
	 * from localStorage on bootstrap).
	 */
	protected function initial_mode(): string {
		$mode = (string) get_theme_mod( 'site_color_mode', 'light' );
		// Light + dark only; the "auto"/system option was retired in 5.1.4. A
		// legacy saved 'auto' resolves to light.
		return in_array( $mode, array( 'light', 'dark' ), true ) ? $mode : 'light';
	}

	/**
	 * Header render hook.
	 */
	public function render_header_toggle(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( 'mobile_only' === $this->position() ) {
			return;
		}
		$this->render( 'header' );
	}

	/**
	 * Mobile-menu render hook.
	 */
	public function render_mobile_toggle(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( 'header' === $this->position() ) {
			return;
		}
		$this->render( 'mobile' );
	}

	/**
	 * Dokan seller-dashboard render hook.
	 *
	 * The Dokan dashboard uses `dokan-dashboard-fullwidth-template`, which
	 * swaps out the BuddyX header entirely — so `buddyx_header_actions`
	 * never fires and a vendor sitting in the dashboard has no way to
	 * switch modes. Render a floating toggle (fixed, bottom-inline-end)
	 * on those routes only. Reuses the same `.bx-color-mode-toggle__btn`
	 * markup so color-mode-toggle.js binds to it via event delegation
	 * with no extra JS. Styling lives in assets/css/src/dokan.css
	 * (`.bx-color-mode-toggle-dokan`), which is already enqueued on
	 * seller-dashboard routes via buddyx_needs_dokan_assets().
	 */
	public function render_dokan_dashboard_toggle(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}
		if ( ! function_exists( 'dokan_is_seller_dashboard' ) || ! dokan_is_seller_dashboard() ) {
			return;
		}
		$this->render( 'dokan' );
	}

	/**
	 * Render the toggle markup.
	 *
	 * @param string $context 'header', 'mobile' or 'dokan'.
	 */
	protected function render( string $context ): void {
		$mode     = $this->initial_mode();
		$wrappers = array(
			'mobile' => 'bx-color-mode-toggle-mobile',
			'dokan'  => 'bx-color-mode-toggle-dokan',
			'header' => 'bx-color-mode-toggle-header',
		);
		$wrapper  = isset( $wrappers[ $context ] ) ? $wrappers[ $context ] : $wrappers['header'];

		$labels = array(
			'light' => __( 'Light mode (click to switch to dark)', 'buddyx' ),
			'dark'  => __( 'Dark mode (click to switch to light)', 'buddyx' ),
		);
		$label  = isset( $labels[ $mode ] ) ? $labels[ $mode ] : $labels['light'];
		?>
		<div class="bx-color-mode-toggle <?php echo esc_attr( $wrapper ); ?>">
			<button type="button"
				class="bx-color-mode-toggle__btn"
				data-mode="<?php echo esc_attr( $mode ); ?>"
				aria-label="<?php echo esc_attr( $label ); ?>"
				aria-pressed="<?php echo 'dark' === $mode ? 'true' : 'false'; ?>">
				<svg class="bx-icon bx-icon-sun" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41"/></svg>
				<svg class="bx-icon bx-icon-moon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
				<span class="screen-reader-text"><?php echo esc_html( $label ); ?></span>
			</button>
		</div>
		<?php
	}

	/**
	 * Enqueue toggle JS only when toggle is enabled.
	 */
	public function enqueue_assets(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$theme     = wp_get_theme();
		$theme_uri = get_template_directory_uri();
		$theme_dir = get_template_directory();
		$src       = $theme_uri . '/assets/js/color-mode-toggle.min.js';
		$path      = $theme_dir . '/assets/js/color-mode-toggle.min.js';
		$ver       = file_exists( $path ) ? filemtime( $path ) : $theme->get( 'Version' );
		wp_enqueue_script( 'buddyx-color-mode-toggle', $src, array(), $ver, true );
	}
}
