<?php
/**
 * BuddyX\Buddyx\Starter_Content\Component class
 *
 * Registers the WordPress Starter Content (`add_theme_support('starter-content')`)
 * for fresh-install demo experience.
 *
 * On a fresh WP install (`fresh_site` option = 1), activating BuddyX +
 * opening the Customizer shows a curated set of demo pages, widgets, and
 * a primary navigation menu. Customer can preview, then click "Publish"
 * to commit, or cancel to leave the site untouched.
 *
 * Existing customers (with content already published) NEVER see this —
 * `fresh_site` flips to 0 the moment any post is published. Updating
 * BuddyX from 5.0.x to 5.1.0 on a populated site is a no-op for this
 * component.
 *
 * Per WP.org guidelines:
 *   - No external HTTP calls.
 *   - No bundled images (zip-size friendly).
 *   - Pattern slugs reference theme-shipped patterns; if the customer
 *     is on a WP version that doesn't render `wp:pattern` blocks, the
 *     page exists but is empty (no fatal).
 *   - All copy is translation-ready via `__()`.
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
	 * Slug.
	 */
	public function get_slug(): string {
		return 'starter_content';
	}

	/**
	 * Hook into after_setup_theme to register starter content.
	 *
	 * Priority 12 runs after theme support registrations in the parent
	 * Base_Support component (priority 10) so we don't race the patterns.
	 */
	public function initialize(): void {
		add_action( 'after_setup_theme', array( $this, 'register' ), 12 );
	}

	/**
	 * Register the starter-content config.
	 */
	public function register(): void {
		add_theme_support( 'starter-content', $this->config() );
	}

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
	 * post_content uses block-pattern references that resolve via WP's
	 * `wp:pattern` block. On WP < 6.0 the page exists but renders empty.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function posts(): array {
		return array(
			'home'     => array(
				'post_type'    => 'page',
				'post_title'   => __( 'Home', 'buddyx' ),
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
				// Blog index — left empty so WP renders the post loop.
				'post_content' => '',
			),
			'faq'      => array(
				'post_type'    => 'page',
				'post_title'   => __( 'FAQ', 'buddyx' ),
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
				// Simple group with heading + paragraph + button. No pattern needed.
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
	 * Primary nav menu — references pages via `{{slug}}` placeholders that
	 * WP's starter-content engine resolves to live page IDs at publish time.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function nav_menus(): array {
		return array(
			'menu-1' => array(
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
	 * Sidebar widgets — core widgets only (no plugin dependencies).
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
	 * Site options — set the static front page + posts page.
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
	 * Theme_mods — surface the new 5.1.0 features in the demo experience.
	 *
	 * @return array<string, mixed>
	 */
	protected function theme_mods(): array {
		return array(
			// Show the visitor color-mode toggle so reviewers see the new
			// 5.1.0 dark-mode UX out of the box.
			'site_color_mode'              => 'auto',
			'site_color_mode_toggle_show'  => 'on',
			'site_color_mode_toggle_position' => 'both',
		);
	}
}
