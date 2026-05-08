<?php
/**
 * BuddyX\Buddyx\Starter_Content\Component class
 *
 * Two complementary demo-content paths:
 *
 * 1. WP Starter Content API (`add_theme_support('starter-content')`):
 *    On a fresh WP install (`fresh_site` option = 1), opening the
 *    Customizer shows a curated set of demo pages, widgets, and a
 *    primary navigation menu. Customer previews + clicks "Publish"
 *    to commit. WP-native, sanctioned by WP.org review process.
 *
 * 2. Admin "Set up demo site" notice + button:
 *    Works on any site state — fresh install OR existing site
 *    that wants the demo. Customer-triggered (button click), so
 *    still WP.org-compliant. Re-uses the same pattern compositions.
 *
 * Per WP.org guidelines:
 *   - No external HTTP calls.
 *   - No bundled images (zip-size friendly).
 *   - Pattern slugs reference theme-shipped patterns; if the customer
 *     is on a WP version that doesn't render `wp:pattern` blocks, the
 *     page exists but is empty (no fatal).
 *   - All copy is translation-ready via `__()`.
 *   - Customer-triggered, never automatic on activation.
 *   - Idempotent — clicking the button twice doesn't double-create.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Starter_Content;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function add_theme_support;

defined( 'ABSPATH' ) || exit;

/**
 * Starter content component.
 */
class Component implements Component_Interface {

	/**
	 * Option name flagging that demo content has been created (or dismissed).
	 */
	const FLAG_OPTION = 'buddyx_demo_setup_state';

	/**
	 * Slug.
	 */
	public function get_slug(): string {
		return 'starter_content';
	}

	/**
	 * Hook into after_setup_theme to register starter content + admin actions.
	 *
	 * Priority 12 runs after theme support registrations in the parent
	 * Base_Support component (priority 10) so we don't race the patterns.
	 */
	public function initialize(): void {
		add_action( 'after_setup_theme', array( $this, 'register' ), 12 );
		add_action( 'admin_notices',     array( $this, 'maybe_render_notice' ) );
		add_action( 'admin_post_buddyx_setup_demo',   array( $this, 'handle_setup' ) );
		add_action( 'admin_post_buddyx_dismiss_demo', array( $this, 'handle_dismiss' ) );
	}

	/**
	 * Register the starter-content config.
	 */
	public function register(): void {
		add_theme_support( 'starter-content', $this->config() );
	}

	/* ----- WP Starter Content config ------------------------------------- */

	/**
	 * Build the starter-content config array.
	 *
	 * @return array<string, mixed>
	 */
	protected function config(): array {
		return array(
			'posts'      => $this->posts(),
			'nav_menus'  => $this->nav_menus(),
			'widgets'    => $this->widgets(),
			'options'    => $this->options(),
			'theme_mods' => $this->theme_mods(),
		);
	}

	/**
	 * Page definitions. Each post key becomes a placeholder usable in
	 * nav_menus, options, and theme_mods (e.g. `{{home}}`).
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function posts(): array {
		// Pattern-driven demo pages render full-bleed sections (hero, CTA,
		// pricing tables, etc.) designed for the no-sidebar
		// `page-templates/full-width.php` template. Without this assignment,
		// new installs (and Playground previews) inherit the customizer's
		// default `sidebar_option = 'right'` so the demo pages render with
		// a sidebar squeezing the patterns — bad first-impression for a
		// theme that markets itself as a typography + pattern library.
		// The `blog` (Journal) page intentionally has NO template assignment
		// because it's used as the posts page (page_for_posts) and the
		// archive layout there benefits from the sidebar.
		return array(
			'home'     => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Home', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->compose(
					array(
						'buddyx/hero-typography-led',
						'buddyx/social-proof-logos',
						'buddyx/features-alternating',
						'buddyx/social-proof-stats',
						'buddyx/services-grid',
						'buddyx/social-proof-testimonials',
						'buddyx/general-pricing',
						'buddyx/general-faq',
						'buddyx/cta-fullbleed',
					)
				),
			),
			'about'    => array(
				'post_type'    => 'page',
				'post_title'   => __( 'About', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->compose(
					array(
						'buddyx/hero-split-screen',
						'buddyx/about-story',
						'buddyx/about-founder',
						'buddyx/team-grid',
					)
				),
			),
			'services' => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Services', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->compose(
					array(
						'buddyx/services-grid',
						'buddyx/features-alternating',
						'buddyx/cta-newsletter',
					)
				),
			),
			'pricing'  => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Pricing', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->compose(
					array(
						'buddyx/general-pricing',
						'buddyx/general-faq',
					)
				),
			),
			'blog'     => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Journal', 'buddyx' ),
				'post_content' => '',
			),
			'faq'      => array(
				'post_type'    => 'page',
				'post_title'   => __( 'FAQ', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->compose(
					array(
						'buddyx/general-faq',
						'buddyx/cta-newsletter',
					)
				),
			),
			'contact'  => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Contact', 'buddyx' ),
				'page_template' => 'page-templates/full-width.php',
				'post_content' => $this->contact_content(),
			),
		);
	}

	/**
	 * Compose a page from pattern slugs using `wp:pattern` block refs.
	 *
	 * @param array<int, string> $slugs Pattern slugs (e.g. "buddyx/hero-typography-led").
	 * @return string Block markup.
	 */
	protected function compose( array $slugs ): string {
		$blocks = array();
		foreach ( $slugs as $slug ) {
			$blocks[] = sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', \esc_attr( $slug ) );
		}
		return implode( "\n\n", $blocks );
	}

	/**
	 * Contact-page content (no pattern, just a heading + paragraph + button).
	 */
	protected function contact_content(): string {
		$heading = __( 'Get in touch', 'buddyx' );
		$body    = __( 'We read every message. Tell us about your project, your timeline, and what would make this collaboration successful.', 'buddyx' );
		$cta     = __( 'Email us', 'buddyx' );

		return sprintf(
			"<!-- wp:group {\"layout\":{\"type\":\"constrained\",\"contentSize\":\"680px\"}} -->\n"
			. "<div class=\"wp-block-group\">\n"
			. "<!-- wp:heading {\"level\":1} --><h1 class=\"wp-block-heading\">%s</h1><!-- /wp:heading -->\n"
			. "<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->\n"
			. "<!-- wp:buttons --><div class=\"wp-block-buttons\">\n"
			. "<!-- wp:button --><div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\" href=\"mailto:hello@example.com\">%s</a></div><!-- /wp:button -->\n"
			. "</div><!-- /wp:buttons -->\n"
			. "</div>\n"
			. "<!-- /wp:group -->",
			\esc_html( $heading ),
			\esc_html( $body ),
			\esc_html( $cta )
		);
	}

	/**
	 * Primary nav menu config.
	 *
	 * Top-level key MUST match a theme-registered menu location name
	 * (see register_nav_menus call in inc/Nav_Menus/Component.php).
	 * BuddyX registers `primary` and `user_menu`. Using `primary` here.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function nav_menus(): array {
		return array(
			'primary' => array(
				'name'  => __( 'Primary Menu', 'buddyx' ),
				'items' => array(
					'page_home',
					'page_about',
					'page_services',
					'page_pricing',
					'page_blog',
					'page_faq',
					'page_contact',
				),
			),
		);
	}

	/**
	 * Sidebar widgets — core only.
	 *
	 * @return array<string, array<int, mixed>>
	 */
	protected function widgets(): array {
		return array(
			'sidebar-1' => array(
				'search',
				'recent-posts',
				'recent-comments',
			),
		);
	}

	/**
	 * Site options.
	 *
	 * @return array<string, mixed>
	 */
	protected function options(): array {
		return array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
		);
	}

	/**
	 * Theme_mods.
	 *
	 * @return array<string, mixed>
	 */
	protected function theme_mods(): array {
		return array(
			'site_color_mode'                 => 'auto',
			'site_color_mode_toggle_show'     => 'on',
			'site_color_mode_toggle_position' => 'both',
			// Pages default to no sidebar — the demo content is composed
			// from full-bleed patterns that need the full content width.
			// Customers can flip back to right/left sidebar in
			// Customizer → Site Sidebar.
			'sidebar_option'                  => 'none',
		);
	}

	/* ----- Admin notice + button ----------------------------------------- */

	/**
	 * Show the admin notice when the customer hasn't yet set up demo
	 * content and hasn't dismissed the offer.
	 */
	public function maybe_render_notice(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			return;
		}
		$state = \get_option( self::FLAG_OPTION, '' );
		if ( in_array( $state, array( 'done', 'dismissed' ), true ) ) {
			return;
		}

		$setup_url   = \wp_nonce_url(
			\admin_url( 'admin-post.php?action=buddyx_setup_demo' ),
			'buddyx_setup_demo'
		);
		$dismiss_url = \wp_nonce_url(
			\admin_url( 'admin-post.php?action=buddyx_dismiss_demo' ),
			'buddyx_dismiss_demo'
		);
		?>
		<div class="notice notice-info is-dismissible buddyx-demo-notice">
			<h3 style="margin-bottom:6px;"><?php \esc_html_e( 'Want to start with a BuddyX demo site?', 'buddyx' ); ?></h3>
			<p style="margin:0 0 12px;"><?php \esc_html_e( 'Creates 7 demo pages (Home, About, Services, Pricing, Journal, FAQ, Contact) using the theme\'s built-in patterns, plus a primary navigation menu and sidebar widgets. You can edit or delete any of it afterward.', 'buddyx' ); ?></p>
			<p style="margin:0 0 12px;">
				<a href="<?php echo \esc_url( $setup_url ); ?>" class="button button-primary"><?php \esc_html_e( 'Set up demo site', 'buddyx' ); ?></a>
				<a href="<?php echo \esc_url( $dismiss_url ); ?>" class="button button-secondary"><?php \esc_html_e( 'No thanks', 'buddyx' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Handle the "Set up demo site" button click.
	 */
	public function handle_setup(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			\wp_die( \esc_html__( 'You do not have permission to do this.', 'buddyx' ), '', array( 'response' => 403 ) );
		}
		\check_admin_referer( 'buddyx_setup_demo' );

		$result = $this->create_demo_content();

		\update_option( self::FLAG_OPTION, 'done' );

		$redirect = \admin_url( 'themes.php?page=' );
		// Add a transient so a success notice shows on the next admin page load.
		\set_transient( 'buddyx_demo_setup_result', $result, 60 );

		\wp_safe_redirect( \admin_url( 'edit.php?post_type=page' ) );
		exit;
	}

	/**
	 * Handle the "No thanks" dismissal.
	 */
	public function handle_dismiss(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			\wp_die( \esc_html__( 'You do not have permission to do this.', 'buddyx' ), '', array( 'response' => 403 ) );
		}
		\check_admin_referer( 'buddyx_dismiss_demo' );
		\update_option( self::FLAG_OPTION, 'dismissed' );
		\wp_safe_redirect( \wp_get_referer() ?: \admin_url() );
		exit;
	}

	/**
	 * Create the 7 demo pages, the primary nav menu, sidebar widgets,
	 * and seed the relevant options + theme_mods.
	 *
	 * Idempotent — checks existing post slugs before creating, so calling
	 * this twice doesn't double-create. Uses each post key as the slug.
	 *
	 * @return array<string, mixed>
	 */
	public function create_demo_content(): array {
		$created = array();
		$page_ids = array();

		foreach ( $this->posts() as $key => $page ) {
			$existing = \get_page_by_path( $key );
			if ( $existing ) {
				$page_ids[ $key ] = $existing->ID;
				continue;
			}
			$post_id = \wp_insert_post(
				array(
					'post_type'    => $page['post_type'],
					'post_title'   => $page['post_title'],
					'post_name'    => $key,
					'post_content' => $page['post_content'],
					'post_status'  => 'publish',
				),
				true
			);
			if ( ! \is_wp_error( $post_id ) && $post_id > 0 ) {
				$page_ids[ $key ] = $post_id;
				$created[]        = $key;
				// Apply the optional page template (full-width.php for
				// pattern-driven demo pages — see posts() docblock).
				if ( ! empty( $page['page_template'] ) ) {
					\update_post_meta( $post_id, '_wp_page_template', $page['page_template'] );
				}
			}
		}

		// Set static front + posts page if pages exist.
		if ( ! empty( $page_ids['home'] ) ) {
			\update_option( 'show_on_front', 'page' );
			\update_option( 'page_on_front', $page_ids['home'] );
		}
		if ( ! empty( $page_ids['blog'] ) ) {
			\update_option( 'page_for_posts', $page_ids['blog'] );
		}

		// Create primary nav menu.
		$menu_name = __( 'Primary Menu', 'buddyx' );
		$menu      = \wp_get_nav_menu_object( $menu_name );
		if ( ! $menu ) {
			$menu_id = \wp_create_nav_menu( $menu_name );
			if ( ! \is_wp_error( $menu_id ) ) {
				foreach ( array( 'home', 'about', 'services', 'pricing', 'blog', 'faq', 'contact' ) as $slug ) {
					if ( empty( $page_ids[ $slug ] ) ) {
						continue;
					}
					\wp_update_nav_menu_item( $menu_id, 0, array(
						'menu-item-title'     => \get_the_title( $page_ids[ $slug ] ),
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page_ids[ $slug ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					) );
				}
				// Assign menu to the theme's primary location.
				$locations             = (array) \get_theme_mod( 'nav_menu_locations', array() );
				$locations['primary']  = $menu_id;
				\set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		// Seed theme_mods that surface the new 5.1.0 features.
		foreach ( $this->theme_mods() as $mod => $val ) {
			if ( ! \get_theme_mod( $mod ) ) {
				\set_theme_mod( $mod, $val );
			}
		}

		return array(
			'created'    => $created,
			'page_ids'   => $page_ids,
			'menu_name'  => $menu_name,
		);
	}
}
