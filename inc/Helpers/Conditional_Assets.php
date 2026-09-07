<?php
/**
 * Conditional Assets Helper Functions
 *
 * Single source of truth for "does the current request actually need this
 * integration's CSS", used by the enqueue gates in inc/Styles/Component.php.
 * Plugin-active (class_exists()/function_exists()) is not a sufficient gate
 * on its own: integration plugins are typically activated site-wide, so
 * gating purely on plugin-active loads the stylesheet on every front-end
 * request even when no related content is on the page. Each helper below
 * adds a real page-content check on top of the existing plugin-active check.
 *
 * Ported from buddyx-pro's inc/Helpers/Conditional_Assets.php so both themes
 * share the same gating logic for the integrations they have in common.
 *
 * @package buddyx
 * @subpackage Helpers
 * @since 5.1.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'buddyx_needs_event_calendar_assets' ) ) {
	/**
	 * Whether the current request needs The Events Calendar integration CSS
	 * (buddyx-eventscalendar).
	 *
	 * @return bool
	 */
	function buddyx_needs_event_calendar_assets(): bool {
		if ( ! class_exists( 'Tribe__Events__Main' ) ) {
			return false;
		}

		// Official TEC template tags: archive/list/month/day views, singular
		// event/venue/organizer, and event category/tag archives.
		if ( function_exists( 'tribe_is_event_query' ) && tribe_is_event_query() ) {
			return true;
		}
		if ( function_exists( 'tribe_is_event' ) && tribe_is_event() ) {
			return true;
		}
		if ( function_exists( 'tribe_is_venue' ) && tribe_is_venue() ) {
			return true;
		}
		if ( function_exists( 'tribe_is_organizer' ) && tribe_is_organizer() ) {
			return true;
		}
		if ( function_exists( 'tribe_is_event_category' ) && tribe_is_event_category() ) {
			return true;
		}

		$post = get_post();
		if ( $post instanceof WP_Post ) {
			// [tribe_events] shortcode or a tribe/* block embedded in content.
			if ( has_shortcode( $post->post_content, 'tribe_events' ) ) {
				return true;
			}
			if ( false !== strpos( $post->post_content, '<!-- wp:tribe/' ) ) {
				return true;
			}
		}

		// Events List widget rendered in an active sidebar.
		if ( is_active_widget( false, false, 'tribe-events-list-widget', true ) ) {
			return true;
		}

		/**
		 * Filters whether the current request needs Events Calendar assets,
		 * for cases this content sniff can't see (custom templates, PHP-only
		 * shortcode calls, third-party page builders, etc.).
		 *
		 * @param bool $needs_assets
		 */
		return (bool) apply_filters( 'buddyx_needs_event_calendar_assets', false );
	}
}

if ( ! function_exists( 'buddyx_needs_learndash_assets' ) ) {
	/**
	 * Whether the current request needs LearnDash integration CSS
	 * (buddyx-learndash).
	 *
	 * @return bool
	 */
	function buddyx_needs_learndash_assets(): bool {
		if ( ! class_exists( 'SFWD_LMS' ) ) {
			return false;
		}

		$ld_post_types = function_exists( 'learndash_get_post_types' )
			? learndash_get_post_types()
			: array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', 'sfwd-certificates', 'sfwd-assignment', 'sfwd-essays', 'groups' );

		if ( is_singular( $ld_post_types ) || is_post_type_archive( $ld_post_types ) ) {
			return true;
		}

		$post = get_post();
		if ( $post instanceof WP_Post ) {
			$content = $post->post_content;
			// LearnDash shortcodes are consistently ld_*/learndash_* prefixed,
			// plus a handful of legacy unprefixed tags (courseinfo, quizinfo,
			// groupinfo, course_content) still in wide use on older sites.
			if ( preg_match( '/\[(ld_|learndash_|courseinfo|course_content|quizinfo|groupinfo)/', $content )
				|| false !== strpos( $content, '<!-- wp:learndash/' ) ) {
				return true;
			}
		}

		// Core LearnDash widgets (id_base values from includes/widgets/*.php)
		// rendered in an active sidebar.
		$ld_widget_ids = array( 'sfwd-courses-widget', 'sfwd-lessons-widget', 'sfwd-quiz-widget', 'sfwd-certificates-widget', 'ldcourseinfo', 'lduserstatus', 'widget_ldcoursenavigation', 'ldcourseprogress' );
		foreach ( $ld_widget_ids as $widget_id_base ) {
			if ( is_active_widget( false, false, $widget_id_base, true ) ) {
				return true;
			}
		}

		/**
		 * Filters whether the current request needs LearnDash assets, for
		 * cases this content sniff can't see.
		 *
		 * @param bool $needs_assets
		 */
		return (bool) apply_filters( 'buddyx_needs_learndash_assets', false );
	}
}

if ( ! function_exists( 'buddyx_needs_bbpress_assets' ) ) {
	/**
	 * Whether the current request needs bbPress integration CSS
	 * (buddyx-bbpress).
	 *
	 * @return bool
	 */
	function buddyx_needs_bbpress_assets(): bool {
		// bbPress's own conditional tag: true on every bbPress query context
		// (forum/topic/reply archive + singular, tags, search, user profile
		// topics/replies tabs).
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			return true;
		}

		// BuddyBoss Platform bundles bbPress as its native forums UI and
		// surfaces it inside the group "Forum" tab, which isn't covered by
		// bbPress's own is_bbpress() query flags. Platform doesn't expose a
		// public conditional tag for that tab, so this branch is left broad
		// (pre-existing behaviour) rather than guessed at.
		if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
			return true;
		}

		/**
		 * Filters whether the current request needs bbPress assets.
		 *
		 * @param bool $needs_assets
		 */
		return (bool) apply_filters( 'buddyx_needs_bbpress_assets', false );
	}
}

if ( ! function_exists( 'buddyx_needs_dokan_assets' ) ) {
	/**
	 * Whether the current request needs Dokan integration CSS
	 * (buddyx-dokan).
	 *
	 * @return bool
	 */
	function buddyx_needs_dokan_assets(): bool {
		if ( ! class_exists( 'WeDevs_Dokan' ) ) {
			return false;
		}

		// Dokan's own conditional tags cover the vendor store, seller
		// dashboard, store review, store listing directory, and the
		// frontend product-edit form.
		if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
			return true;
		}
		if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
			return true;
		}
		if ( function_exists( 'dokan_is_store_review_page' ) && dokan_is_store_review_page() ) {
			return true;
		}
		if ( function_exists( 'dokan_is_store_listing' ) && dokan_is_store_listing() ) {
			return true;
		}
		if ( function_exists( 'dokan_is_product_edit_page' ) && dokan_is_product_edit_page() ) {
			return true;
		}

		/**
		 * Filters whether the current request needs Dokan assets.
		 *
		 * @param bool $needs_assets
		 */
		return (bool) apply_filters( 'buddyx_needs_dokan_assets', false );
	}
}
