<?php
/**
 * BuddyX\Buddyx\Speculation_Rules\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Speculation_Rules;

use BuddyX\Buddyx\Component_Interface;
use function add_action;

/**
 * Class for emitting a Speculation Rules JSON document.
 *
 * Speculation Rules (https://wicg.github.io/nav-speculation/) is a browser
 * API that lets a site declaratively prerender same-origin links that the
 * user is likely to visit next. Chromium-based browsers consume the
 * <script type="speculationrules"> document and prerender matching links
 * on hover (with `eagerness: moderate`), making the next navigation feel
 * instant.
 *
 * BuddyX emits a conservative ruleset:
 *   - Prerender any internal link that matches /<wildcard>
 *   - Exclude wp-login.php, wp-admin/*, customizer URLs, and links the
 *     theme/site has explicitly marked as no-prerender (rel=nofollow,
 *     target=_blank, .no-prerender class).
 *   - Eagerness: `moderate` — fires after ~200ms of pointer hover, which
 *     trades some bandwidth for a near-instant next-page experience.
 *
 * Forward-compatibility: if WordPress core ships its own Speculation Rules
 * emitter (the Speculative Loading feature plugin is the foundation), this
 * component defers to core to avoid double-emission.
 *
 * Theme authors / site owners who want to disable or override the rules
 * can use the `buddyx_speculation_rules` filter.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'speculation_rules';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_footer', array( $this, 'emit_rules' ), 1 );
	}

	/**
	 * Emit the speculation-rules JSON document.
	 *
	 * Skips emission entirely when:
	 *   - The current request is the customizer preview (avoids prerender
	 *     conflicts with the live-preview iframe).
	 *   - WordPress core (or another plugin) already provides speculation
	 *     rules via wp_get_speculation_rules() — defer to that.
	 */
	public function emit_rules() {
		if ( is_customize_preview() ) {
			return;
		}
		if ( function_exists( 'wp_get_speculation_rules' ) ) {
			// Core / Speculative Loading plugin handles emission.
			return;
		}

		$rules = array(
			'prerender' => array(
				array(
					'source'    => 'document',
					'where'     => array(
						'and' => array(
							array( 'href_matches' => '/*' ),
							array(
								'not' => array(
									'href_matches' => array(
										'/wp-login.php',
										'/wp-admin/*',
										'/wp-admin',
										'/?customize_changeset_uuid=*',
										'/*\\?*preview=true*',
										'/*\\?*action=*',
									),
								),
							),
							array(
								'not' => array(
									'selector_matches' => 'a[rel~="nofollow"], a[target="_blank"], a.no-prerender',
								),
							),
						),
					),
					'eagerness' => 'moderate',
				),
			),
		);

		/**
		 * Filters the Speculation Rules JSON object before emission.
		 *
		 * Return an empty array to disable speculation rules entirely, or
		 * mutate the structure to tune `eagerness`, add `prefetch` rules,
		 * or extend the exclusion list.
		 *
		 * @param array $rules The Speculation Rules document, ready to be
		 *                     JSON-encoded into the <script> tag body.
		 */
		$rules = apply_filters( 'buddyx_speculation_rules', $rules );

		if ( empty( $rules ) ) {
			return;
		}

		printf(
			'<script type="speculationrules">%s</script>',
			wp_json_encode( $rules ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — JSON is safe in <script> with type="speculationrules".
		);
	}
}
